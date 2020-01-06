<?php

  namespace App\GraphQL\Mutations;

  use App\Models\Track;
  use GraphQL\Type\Definition\ResolveInfo;
  use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;

  class DeleteTrackMutation
  {
    public function __invoke($rootValue, array $args, GraphQLContext $context, ResolveInfo $resolveInfo)
    {
      $trackHash = $args['hash'];
      $track     = Track::whereHash($trackHash)->firstOrFail();

      $auth_user = auth()->user();

      if ($auth_user->admin || $track->user_id === $auth_user->id) {
        $track->playlists()->delete();
        $track->delete();

        return ['success' => true];
      }

      return ['success' => false];
    }
  }
