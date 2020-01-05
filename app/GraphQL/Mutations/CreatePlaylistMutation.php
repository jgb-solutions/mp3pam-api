<?php

namespace App\GraphQL\Mutations;

use App\Models\Playlist;
use App\Helpers\MP3Pam;
use GraphQL\Type\Definition\ResolveInfo;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;

class CreatePlaylistMutation
{

    public function __invoke($rootValue, array $args, GraphQLContext $context, ResolveInfo $resolveInfo)
    {
      $title = $args['title'];

      $playlist = auth()->user()->playlists()->firstOrNew([
        'title' => $title
      ]);

      if (!$playlist->hash) {
        $playlist->hash = MP3Pam::getHash(Playlist::class);
      }

      $playlist->save();

      return $playlist;
    }
}
