<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use \Illuminate\Database\Eloquent\ModelNotFoundException;

class Handler extends ExceptionHandler
{
	/**
	 * A list of the exception types that should not be reported.
	 *
	 * @var array
	 */
	protected $dontReport = [
		\Illuminate\Auth\AuthenticationException::class,
		\Illuminate\Auth\Access\AuthorizationException::class,
		\Symfony\Component\HttpKernel\Exception\HttpException::class,
		\Illuminate\Database\Eloquent\ModelNotFoundException::class,
		\Illuminate\Session\TokenMismatchException::class,
		\Illuminate\Validation\ValidationException::class,
	];

	/**
	 * Report or log an exception.
	 *
	 * This is a great spot to send exceptions to Sentry, Bugsnag, etc.
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
	public function render($request, Exception $e)
	{
		if ($e instanceof \Tymon\JWTAuth\Exceptions\TokenExpiredException) {
			return response()->json(['message' => 'Tokenn ou an ekspire.'], $e->getStatusCode());
		}

		if ($e instanceof \Tymon\JWTAuth\Exceptions\TokenInvalidException) {
			return response()->json(['message' => 'Tokenn ou an envalid.'], $e->getStatusCode());
		}

		if ($e instanceof \Tymon\JWTAuth\Exceptions\TokenMismatchException) {
			return response()->json(['message' => 'TokenMismatchException'], $e->getStatusCode());
		}

		if ($e instanceof ModelNotFoundException && $request->ajax()) {
			return response()->json(['message' => 'Nou pa jwenn sa w mande a.'], 404);
		}

		return parent::render($request, $e);
	}

	/**
	 * Convert an authentication exception into an unauthenticated response.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @param  \Illuminate\Auth\AuthenticationException  $exception
	 * @return \Illuminate\Http\Response
	 */
	protected function unauthenticated($request, AuthenticationException $exception)
	{
		if ($request->expectsJson()) {
			return response()->json(['error' => 'Unauthenticated.'], 401);
		}

		return redirect()->guest(route('login'));
	}
}
