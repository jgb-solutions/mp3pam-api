<?php

namespace App\Http\Middleware;

use Closure;
use JWTAuth;
use App\Exceptions\CustomJWTException;
use Tymon\JWTAuth\Exceptions\JWTException;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;


class VerifyJWTToken
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        try {
            $user = JWTAuth::parseToken()->authenticate();
        } catch (JWTException $exception) {
            if ($exception instanceof \Tymon\JWTAuth\Exceptions\TokenExpiredException) {
                throw new CustomJWTException("Your session has expired.", $exception->getStatusCode());
            }

            if ($exception instanceof \Tymon\JWTAuth\Exceptions\TokenInvalidException) {
                throw new CustomJWTException("Your token is invalid.", $exception->getStatusCode());
            }

            if ($exception instanceof \Tymon\JWTAuth\Exceptions\TokenMismatchException) {
                throw new CustomJWTException("We could not authenticate you.", $exception->getStatusCode());
            }

            if ($exception instanceof ModelNotFoundException) {
                throw new CustomJWTException("The resource was not found.", $exception->getStatusCode());
            }

            if ($exception instanceof UnauthorizedHttpException) {
                throw new CustomJWTException("You need to login to access this resource.", $exception->getStatusCode());
            }

            throw new CustomJWTException("You need to login to access this resource.", 401);
        }
       return $next($request);
    }
}