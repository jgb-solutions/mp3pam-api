<?php

namespace App\GraphQL\Queries;

use GraphQL\Type\Definition\ResolveInfo;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;

use App\Http\Resources\UserResource;
use Laravel\Socialite\Facades\Socialite;
use App\Http\Requests\RegisterFormRequest;
use Tymon\JWTAuth\Exceptions\JWTException;

class LoginQuery
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
        $credentials = [
            'email' => $args['input']['email'],
            'password' => $args['input']['password']
        ];

		try {
		   	// attempt to verify the credentials and create a token for the user
		   	if (! $token = auth()->guard('api')->attempt($credentials)) {
		       		return response()->json(['message' => 'Imel oubyen Modpas la pa bon.'], 401);
		   	}
		} catch (JWTException $e) {
		   	// something went wrong whilst attempting to encode the token
		   	return response()->json(['message' => 'Nou pa rive kreye kòd tokenn nan.'], 500);
		}

		// all good so return the token
		$user = auth()->guard('api')->user();

		// return response
		return [
			'user' => new UserResource($user),
			'token' => $token
		];
    }
}
