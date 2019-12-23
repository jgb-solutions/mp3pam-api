<?php

  namespace App\GraphQL\Mutations;

  use App\Models\Album;
  use App\Helpers\MP3Pam;
  use GraphQL\Type\Definition\ResolveInfo;
  use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;

  class CreateAlbumMutation
  {
    public function __invoke($rootValue, array $args, GraphQLContext $context, ResolveInfo $resolveInfo)
    {
      extract($args['input']);

      $album = auth()->user()->albums()->firstOrNew([
        'title' => $title,
        'release_year' => $release_year,
        'artist_id' => $artist_id,
        'img_bucket' => $img_bucket,
      ]);

      if (isset($cover)) {
        $album->cover = $cover;
      }

      if (isset($detail)) {
        $album->detail = $detail;
      }

      if (!$album->hash) {
        $album->hash = MP3Pam::getHash(Album::class);
      }

      $album->save();

      return $album;
    }
  }
