<?php

  namespace App\GraphQL\Mutations;

  use App\Models\Album;
  use GraphQL\Type\Definition\ResolveInfo;
  use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;

  class DeleteAlbumMutation
  {
    public function __invoke($rootValue, array $args, GraphQLContext $context, ResolveInfo $resolveInfo)
    {
      $albumHash = $args['hash'];
      $album     = Album::whereHash($albumHash)->firstOrFail();

      $auth_user = auth()->user();

      if ($auth_user->admin) {
        $album->delete();
        return ['success' => true];
      }

      if ($album->user_id === $auth_user->id) {
        $album->delete();
        return ['success' => true];
      }

      return ['success' => false];
    }
  }
