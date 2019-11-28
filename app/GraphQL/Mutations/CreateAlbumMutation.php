<?php

namespace App\GraphQL\Mutations;

use App\Models\Album;
use App\Helpers\MP3Pam;
use GraphQL\Type\Definition\ResolveInfo;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;

class CreateAlbumMutation
{
    /**
     * Return a value for the field.
     *
     * @param  null  $rootValue Usually contains the result returned from the parent field. In this case, it is always `null`.
     * @param  mixed[]  $args The arguments that were passed into the field.
     * @param  \Nuwave\Lighthouse\Support\Contracts\GraphQLContext  $context Arbitrary data that is shared between all fields of a single query.
     * @param  \GraphQL\Type\Definition\ResolveInfo  $resolveInfo Information about the query itself, such as the execution state, the field name, path to the field from the root, and more.
     * @return mixed
     */
    public function __invoke($rootValue, array $args, GraphQLContext $context, ResolveInfo $resolveInfo)
    {
      extract($args['input']);

      $album = auth()->user()->albums()->firstOrNew([
          'title' => $title,
          'year' => $year,
          'artist_id' => $artist_id,
          'img_bucket' => $img_bucket,
      ]);

      if (isset($poster)) {
          $album->poster = $poster;
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
