<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Auth\AuthenticationException;
use \Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;

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
				return response()->json(['message' => 'Tokenn ou an ekspire.'], $exception->getStatusCode());
			}

			if ($exception instanceof \Tymon\JWTAuth\Exceptions\TokenInvalidException) {
				return response()->json(['message' => 'Tokenn ou an envalid.'], $exception->getStatusCode());
			}

			if ($exception instanceof \Tymon\JWTAuth\Exceptions\TokenMismatchException) {
				return response()->json(['message' => 'TokenMismatchException'], $exception->getStatusCode());
			}

			if ($exception instanceof ModelNotFoundException && $request->ajax()) {
				return response()->json(['message' => 'Nou pa jwenn sa w mande a.'], 404);
			}

   //   	if ($request->expectsJson()) {
			// 	return response()->json(['error' => 'Unauthenticated.'], 401);
			// }

     	return parent::render($request, $exception);
    }
}
