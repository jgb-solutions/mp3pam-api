<?php

  namespace App\GraphQL\Mutations;

  use App\Models\Artist;
  use GraphQL\Type\Definition\ResolveInfo;
  use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;

  class DeleteArtistMutation
  {
    public function __invoke($rootValue, array $args, GraphQLContext $context, ResolveInfo $resolveInfo)
    {
      $artistHash = $args['hash'];
      $artist     = Artist::whereHash($artistHash)->firstOrFail();

      $auth_user = auth()->user();

      if ($auth_user->admin || $artist->user_id === $auth_user->id) {
        $artist->tracks()->delete();
        $artist->albums()->delete();

        $artist->delete();

        return ['success' => true];
      }

      return ['success' => false];
    }
  }
