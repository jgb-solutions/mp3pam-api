<?php

namespace App\GraphQL\Mutations;

use GraphQL\Type\Definition\ResolveInfo;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;
use \App\Helpers\MP3Pam;
use \App\Models\Track;

class CreateTrackMutation
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

        $trackData = [
            'title' => $title,
            'audio_name' => $audioName,
            'poster' => $poster,
            'detail' => $detail,
            'lyrics' => $lyrics,
            'audio_file_size' => $audioFileSize,
            'genre_id' => $genreId,
            'artist_id' => $artistId,
            'hash' => MP3Pam::getHash(Track::class),
        ];

        $track = auth()->user()->tracks()->create($trackData);

        return $track;
    }
}
