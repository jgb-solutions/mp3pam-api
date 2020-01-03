<?php

  namespace App\GraphQL\Mutations;

  use App\Models\Playlist;
  use GraphQL\Type\Definition\ResolveInfo;
  use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;

  class DeletePlaylistMutation
  {
    public function __invoke($rootValue, array $args, GraphQLContext $context, ResolveInfo $resolveInfo)
    {
      $playlistHash = $args['hash'];
      $playlist     = Playlist::whereHash($playlistHash)->firstOrFail();

      $auth_user = auth()->user();

      if ($auth_user->admin) {
        $playlist->delete();

        return ['success' => true];
      }

      if ($playlist->user_id === $auth_user->id) {
        $playlist->delete();

        return ['success' => true];
      }

      return ['success' => false];
    }
  }
