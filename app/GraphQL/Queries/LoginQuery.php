<?php

namespace App\GraphQL\Queries;

use App\Exceptions\CustomJWTException;
use GraphQL\Type\Definition\ResolveInfo;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;
use Tymon\JWTAuth\Exceptions\JWTException;

class LoginQuery
{
    public function __invoke($rootValue, array $args, GraphQLContext $context, ResolveInfo $resolveInfo)
    {
        $credentials = [
            'email' => $args['input']['email'],
            'password' => $args['input']['password'],
        ];

        try {
            // attempt to verify the credentials and create a token for the user
            if (!$token = auth()->guard('api')->attempt($credentials)) {
                throw new CustomJWTException("The email or password is incorrect.", 401);
            }
        } catch (JWTException $e) {
            // something went wrong whilst attempting to encode the token
            throw new CustomJWTException("The token could not be created.", 500);
        }

        // all good so return the token
        $user = auth()->guard('api')->user();

        // return response
        return [
            'data' => $user,
            'token' => $token,
        ];
    }
}
