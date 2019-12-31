<?php

namespace App\GraphQL\Mutations;

use App\Models\Track;
use App\Exceptions\CustomJWTException;
use GraphQL\Type\Definition\ResolveInfo;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;

class AddTrackToAlbumMutation
{
    public function __invoke($rootValue, array $args, GraphQLContext $context, ResolveInfo $resolveInfo)
    {
      extract($args['input']);

      $track = Track::whereHash($track_hash)->firstOrFail();

      if ($track->number == $track_number && $track->album_id == $album_id) {
        throw new CustomJWTException("This track already exists in the album.");
      }

      $auth_user = auth()->user();

      if ($auth_user->admin) {
        $track->update([
          'album_id' => $album_id,
          'number' => $track_number
        ]);

        return ['success' => true];
      }

      if ($track->user_id === $auth_user->id) {
        $track->update([
          'album_id' => $album_id,
          'number' => $track_number
        ]);

        return ['success' => true];
      }

      return ['success' => false];
    }
}
