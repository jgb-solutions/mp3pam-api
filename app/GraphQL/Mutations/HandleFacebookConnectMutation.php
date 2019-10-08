<?php

namespace App\GraphQL\Mutations;

use Socialite;
use App\Models\User;
use App\Http\Resources\UserResource;
use GraphQL\Type\Definition\ResolveInfo;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;

class HandleFacebookConnectMutation
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
        $code = $args['code'];

        // making the code sent to graphql available to socialite
        request()->request->add(['code' => $code]);

        try {
            $fb_user = Socialite::driver('facebook')->stateless()->user();

            // for example we might do something like... Check if a user exists with the email and if so, log them in.
            // $user = User::where()
            // $user = User::orWhere('email', $fb_user->email)->firstOrCreate([
            //    'facebook_id'     => $fb_user->id,
            // ], [
            //  'name'              => $fb_user->name,
            //  'avatar'            => $fb_user->avatar,
            //  'facebook_link'     => $fb_user->profileUrl
            // ]);

            $user = User::where('facebook_id', $fb_user->id)->orWhere('email', $fb_user->email)->first();

            if ($user) {
                if (empty($user->facebook_id)) {
                    $user->facebook_id = $fb_user->id;
                }

                if (empty($user->name)) {
                    $user->name = $fb_user->name;
                }

                if (empty($user->avatar)) {
                    $user->avatar = $fb_user->avatar;
                }

                if (empty($user->facebook_link)) {
                    $user->facebook_link = $fb_user->profileUrl;
                }

                $user->save();
            } else {
                $user = User::create([
                    'facebook_id'       => $fb_user->id,
                    'name'              => $fb_user->name,
                    'avatar'            => $fb_user->avatar,
                    'facebook_link'     => $fb_user->profileUrl
                ]);
            }

            $first_login = false;

            if ($user->firstLogin) {
                $user->first_login = true;
                $first_login = true;

                $user->save();
            }

            $token = auth()->guard('api')->login($user);

            return [
                'token'         => $token,
                'data'          => new UserResource($user),
                'firstLogin'    => (boolean) $first_login
            ];
        } catch (\GuzzleHttp\Exception\ClientException $e) {
            return response()->json(['message' => 'Nou pa rive konekte w ak Facebook, tanpri eseye ankò'], 500);
        }
    }
}
