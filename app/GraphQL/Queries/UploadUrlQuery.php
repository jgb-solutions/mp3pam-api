<?php

namespace App\GraphQL\Queries;

use JWTAuth;
use GraphQL\Type\Definition\ResolveInfo;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;

class UploadUrlQuery
{
    /**
     * Return a value for the field.
     *
     * @param  null  $rootValue Usually contains the result returned from the parent field. In this case, it is always `null`.
     * @param  mixed[]  $args The arguments that were passed into the field.
     * @param  \Nuwave\Lighthouse\Support\Contracts\GraphQLContext  $context Arbitrary data that is shared between all fields of a single query.
     * @param  \GraphQL\Type\Definition\ResolveInfo  $resolveInfo Information about the query itself, such as the execution state, the field name, path to the field from the root, and more.
     * @return mixed
     */
    public function __invoke($rootValue, array $args, GraphQLContext $context, ResolveInfo $resolveInfo)
    {
        $name = $args['name'];
        $type = $args['type'];
        $wasabi = \Storage::disk('wasabi');
        $bucket = $type . '.mp3pam.com';
        $client = $wasabi->getDriver()->getAdapter()->getClient();
        $filename = static::makeUploadFilePath($name);
        $file_url = static::makeFileUrl($bucket, $filename);
        $command = $client->getCommand('PutObject', [
            'Bucket' => $bucket,
            'Key'    => static::makeUploadFilePath($name),
            // 'ResponseContentDisposition' => 'attachment; filename=' . request('filename'),
            'ACL'    => 'public-read',
        ]);

        $request = $client->createPresignedRequest($command, "+30 minutes");

        $signed_url = (string) $request->getUri();

        return [
            'signedUrl' => $signed_url,
            'fileUrl' => $file_url
        ];
    }

    public static function makeUploadFolder()
    {
        $user = auth()->guard('api')->user();
        return 'user_' . $user->id . '/' . date('Y/m/d');
    }

    public static function makeUploadFileName($name)
    {
        return time() . '.' . pathinfo($name, PATHINFO_EXTENSION);
    }

    public static function makeUploadFilePath($name)
    {
        return static::makeUploadFolder() . '/' . static::makeUploadFileName($name);
    }

    public static function makeFileUrl($bucket, $filename)
    {
        return "https://{$bucket}/{$filename}";
    }
}
