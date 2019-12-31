<?php

  namespace App\GraphQL\Mutations;

  use App\Models\Track;
  use GraphQL\Type\Definition\ResolveInfo;
  use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;

  class DeleteAlbumTrackMutation
  {
    public function __invoke($rootValue, array $args, GraphQLContext $context, ResolveInfo $resolveInfo)
    {
      $trackHash = $args['hash'];
      $track     = Track::whereHash($trackHash)->firstOrFail();

      $auth_user = auth()->user();

      if ($auth_user->admin) {
        $track->update(['album_id' => null]);

        return ['success' => true];
      }

      if ($track->user_id === $auth_user->id) {
        $track->update(['album_id' => null]);

        return ['success' => true];
      }

      return ['success' => false];
    }
  }
