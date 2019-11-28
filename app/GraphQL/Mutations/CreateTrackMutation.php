<?php

namespace App\GraphQL\Mutations;

use App\Models\Track;
use App\Helpers\MP3Pam;
use GraphQL\Type\Definition\ResolveInfo;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;

class CreateTrackMutation
{
    public function __invoke($rootValue, array $args, GraphQLContext $context, ResolveInfo $resolveInfo)
    {
        extract($args['input']);

        $trackData = [
            'title' => $title,
            'audio_name' => $audioName,
            'gits' => $poster,
            'detail' => $detail,
            'lyrics' => $lyrics,
            'audio_file_size' => $audioFileSize,
            'genre_id' => $genreId,
            'artist_id' => $artistId,
            'img_bucket' => $img_bucket,
            'audio_bucket' => $audio_bucket,
            'hash' => MP3Pam::getHash(Track::class),
        ];

        $track = auth()->user()->tracks()->create($trackData);

        return $track;
    }
}
