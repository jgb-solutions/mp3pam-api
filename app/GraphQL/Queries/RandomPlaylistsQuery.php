<?php

namespace App\GraphQL\Queries;


use App\Models\Playlist;
use GraphQL\Type\Definition\ResolveInfo;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;

class RandomPlaylistsQuery
{
    public function __invoke($rootValue, array $args, GraphQLContext $context, ResolveInfo $resolveInfo)
    {
      extract($args['input']);

      return Playlist::random($hash)->take($first)->get();
    }
}
