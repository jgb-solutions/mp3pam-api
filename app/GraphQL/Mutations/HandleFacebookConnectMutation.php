<?php

  namespace App\GraphQL\Mutations;

  use Socialite;
  use App\Models\User;
  use GraphQL\Type\Definition\ResolveInfo;
  use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;

  class HandleFacebookConnectMutation
  {
    public function __invoke($rootValue, array $args, GraphQLContext $context, ResolveInfo $resolveInfo)
    {
      $code = $args['code'];

      // making the code sent to graphql available to socialite
      request()->request->add(['code' => $code]);

      try {
        $fb_user = Socialite::driver('facebook')->stateless()->user();
        $email   = $fb_user->getEmail();

        $user = User::where('facebook_id', $fb_user->id)->orWhere('email', $email)->first();

        if ($user) {
          if (empty($user->avatar) && empty($user->fb_avatar)) {
            $user->fb_avatar = $fb_user->avatar;
          }

          $user->save();
        } else {
          $user = User::create([
            'facebook_id' => $fb_user->id,
            'name' => $fb_user->name,
            'email' => $email,
            'fb_avatar' => $fb_user->avatar,
            'facebook_link' => $fb_user->profileUrl,
          ]);
        }

        $token = auth()->guard('api')->login($user);

        $response = [
          'token' => $token,
          'data' => $user,
        ];

        if ($user->first_login) {
          $user->update(['first_login' => false]);
        }

        return $response;
      } catch (\GuzzleHttp\Exception\ClientException $e) {
        return response()->json(['message' => 'Unable to connect to Facebook. Please try again.'], 500);
      }
    }
  }
