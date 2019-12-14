<?php

  namespace App\GraphQL\Queries;

  use App\Models\Track;
  use App\Models\Artist;
  use App\Models\Album;
  use GraphQL\Type\Definition\ResolveInfo;
  use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;

  class SearchQuery
  {
    /**
     * Return a value for the field.
     *
     * @param  null $rootValue Usually contains the result returned from the parent field. In this case, it is always `null`.
     * @param  mixed[] $args The arguments that were passed into the field.
     * @param  \Nuwave\Lighthouse\Support\Contracts\GraphQLContext $context Arbitrary data that is shared between all fields of a single query.
     * @param  \GraphQL\Type\Definition\ResolveInfo $resolveInfo Information about the query itself, such as the execution state, the field name, path to the field from the root, and more.
     * @return mixed
     */
    public function __invoke($rootValue, array $args, GraphQLContext $context, ResolveInfo $resolveInfo)
    {
      $query = $args['query'];
      $take  = 5;

      return [
        'tracks' => Track::search($query)->take($take)->get(),
        'artists' => Artist::search($query)->take($take)->get(),
        'albums' => Album::search($query)->take($take)->get(),
      ];
    }
  }
