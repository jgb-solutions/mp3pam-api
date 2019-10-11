<?php

namespace App\Exceptions;

use Exception;
use App\Exceptions\CustomJWTException;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;


class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that are not reported.
     *
     * @var array
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed for validation exceptions.
     *
     * @var array
     */
    protected $dontFlash = [
        'password',
        'password_confirmation',
    ];

    /**
     * Report or log an exception.
     *
     * @param  \Exception  $exception
     * @return void
     */
    public function report(Exception $exception)
    {
        parent::report($exception);
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Exception  $exception
     * @return \Illuminate\Http\Response
     */
    public function render($request, Exception $exception)
    {
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

     	return parent::render($request, $exception);
    }

    protected function unauthenticated($request, AuthenticationException $exception)
{
    return $request->expectsJson()
        ? response()->json(['message' => $exception->getMessage()], 401)
        : redirect()->guest(route('ROUTENAME'));
}
}
