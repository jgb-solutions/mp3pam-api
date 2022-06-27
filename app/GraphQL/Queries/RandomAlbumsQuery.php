<?php

namespace App\GraphQL\Queries;

use App\Models\Album;
use GraphQL\Type\Definition\ResolveInfo;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;

class RandomAlbumsQuery
{
    public function __invoke($rootValue, array $args, GraphQLContext $context, ResolveInfo $resolveInfo)
    {
      extract($args['input']);

      return Album::random($hash)->take($first)->get();
    }
}
