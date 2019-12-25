<?php

namespace App\GraphQL\Queries;

use App\Models\Track;
use GraphQL\Type\Definition\ResolveInfo;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;

class RelatedTracksQuery
{
    public function __invoke($rootValue, array $args, GraphQLContext $context, ResolveInfo $resolveInfo)
    {
      extract($args['input']);

      $track = Track::whereHash($hash)->first();

      if ($track) {
        $relatedTracks = Track::related($track)->take($take)->get();

        return $relatedTracks;
      }

      return null;
    }
}
