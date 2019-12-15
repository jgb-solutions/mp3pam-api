<?php

namespace App\GraphQL\Mutations;

use App\Models\Track;
use GraphQL\Type\Definition\ResolveInfo;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;

class UpdatePlayCountMutation
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
      extract($args);

      switch ($type) {
        case "track":
          $track = Track::byHash($hash)->firstOrFail();
          $track->play_count = $track->play_count + 1;
          $track->save();
          break;
//        case "podcast":
//          $episode = Episode::byHash($hash)->firstOrFail();
//          $filename = $episode->audio_name;
//          $bucket = $episode->audio_bucket;
//          break;
      }
    }
}
