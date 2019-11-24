<?php

namespace App\GraphQL\Mutations;
use App\Helpers\MP3Pam;
use App\Models\Artist;
use GraphQL\Type\Definition\ResolveInfo;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;

class CreateArtistMutation
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
        extract($args['input']);

        $artistData = [
            'name' => $name,
            'stage_name' => $stage_name,
            'poster' => $poster ?? null,
            'bio' => $bio ?? null,
            'hash' => MP3Pam::getHash(Artist::class),
        ];

        $artist = auth()->user()->artists()->create($artistData);

        return $artist;
    }
}
