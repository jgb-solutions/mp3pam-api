<?php

function get($path, $controllerOrFunction) {
	return Route::get($path, $controllerOrFunction);
}

function post($path, $controllerOrFunction) {
	return Route::post($path, $controllerOrFunction);
}

function put($path, $controllerOrFunction) {
	return Route::put($path, $controllerOrFunction);
}

function patch($path, $controllerOrFunction) {
	return Route::patch($path, $controllerOrFunction);
}

function del($path, $controllerOrFunction) {
	return Route::delete($path, $controllerOrFunction);
}

function group(array $array = [], $fun) {
	return Route::group($array, $fun);
}

