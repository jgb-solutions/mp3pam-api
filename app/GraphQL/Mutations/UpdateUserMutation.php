<?php

  namespace App\GraphQL\Mutations;

  use App\Models\User;
  use GraphQL\Type\Definition\ResolveInfo;
  use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;

  class UpdateUserMutation
  {
    public function __invoke($rootValue, array $args, GraphQLContext $context, ResolveInfo $resolveInfo)
    {
      $input = $args['input'];
      $id    = $input['id'];

      $auth_user = auth()->user();

      if ($auth_user->id == $id || $auth_user->admin) {
        if ($auth_user->id == $id) {
         $user = $auth_user;
        } else {
          $user = User::find($id);
        }

        $user->update($input);

        return $user;
      }

      return null;
    }
  }
