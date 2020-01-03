<?php

  namespace App\GraphQL\Mutations;

  use App\Models\Playlist;
  use App\Models\Track;
  use GraphQL\Type\Definition\ResolveInfo;
  use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;

  class DeletePlaylistTrackMutation
  {
    public function __invoke($rootValue, array $args, GraphQLContext $context, ResolveInfo $resolveInfo)
    {
      $trackHash    = $args['trackHash'];
      $playlistHash = $args['playlistHash'];

      $track    = Track::whereHash($trackHash)->firstOrFail();
      $playlist = Playlist::whereHash($playlistHash)->firstOrFail();

      $auth_user = auth()->user();

      if ($auth_user->admin) {
        $track->playlists()->detach($playlist->id);
        $track->save();

        return ['success' => true];
      }

      if ($track->user_id === $auth_user->id) {
        $track->playlists()->detach($playlist->id);
        $track->save();

        return ['success' => true];
      }

      return ['success' => false];
    }
  }
