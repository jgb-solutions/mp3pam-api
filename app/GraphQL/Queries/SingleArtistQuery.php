<?php

  namespace App\GraphQL\Queries;

  use App\Models\Artist;
  use GraphQL\Type\Definition\ResolveInfo;
  use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;

  class SingleArtistQuery
  {
    public function __invoke($rootValue, array $args, GraphQLContext $context, ResolveInfo $resolveInfo)
    {
      return Artist::whereHash($args['hash'])->with(['tracks', 'albums' => function ($query) {
        return $query->has('tracks');
      }])->first();
    }
  }
