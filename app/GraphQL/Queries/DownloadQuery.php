<?php

  namespace App\GraphQL\Queries;

  use App\Models\Track;
//  use App\Models\Episode;
  use GraphQL\Type\Definition\ResolveInfo;
  use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;

  class DownloadQuery
  {
    /**
     * Return a value for the field.
     *
     * @param  null $rootValue Usually contains the result returned from the parent field. In this case, it is always `null`.
     * @param  mixed[] $args The arguments that were passed into the field.
     * @param  \Nuwave\Lighthouse\Support\Contracts\GraphQLContext $context Arbitrary data that is shared between all fields of a single query.
     * @param  \GraphQL\Type\Definition\ResolveInfo $resolveInfo Information about the query itself, such as the execution state, the field name, path to the field from the root, and more.
     * @return mixed
     */
    public function __invoke($rootValue, array $args, GraphQLContext $context, ResolveInfo $resolveInfo)
    {
      extract($args);

      switch ($type) {
        case "track":
          $track = Track::with('artist')->byHash($hash)->firstOrFail();
          $filename = $track->audio_name;
          $bucket = $track->audio_bucket;
          $name = $track->artist->name . ' - ' . $track->title . '.mp3';
          break;
//        case "podcast":
//          $episode = Episode::byHash($hash)->firstOrFail();
//          $filename = $episode->audio_name;
//          $bucket = $episode->audio_bucket;
//          break;
      }

      $wasabi   = \Storage::disk('wasabi');
      $client   = $wasabi->getDriver()->getAdapter()->getClient();
      $command  = $client->getCommand('GetObject', [
        'Bucket' => $bucket,
        'Key' => $filename,
        'ResponseContentDisposition' => 'attachment; filename=' . $name,
        'ContentType' => 'audio/mpeg'
//        'ACL' => 'public-read',
      ]);


      $request = $client->createPresignedRequest($command, "+10 minutes");

      $url = (string) $request->getUri();

      return [
        'url' => $url,
      ];
    }

  }
