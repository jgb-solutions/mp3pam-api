<?php
group(['prefix' => 'v1'], function() {
	// Auth routes.
	post('register', 'Auth\AuthController@register')->name('auth.register');
 	post('login', 'Auth\AuthController@login')->name('auth.login');
 	get('/auth/facebook', 'Auth\AuthController@redirectToFacebook')->name('auth.facebook');
	get('/auth/facebook/handle', 'Auth\AuthController@handleFacebookConnect')->name('auth.facebook.handle');

 	// Recover Password
 	post('recover', 'Auth\ResetPasswordController@recover')->name('password.recover');
 	post('verify', 'Auth\ResetPasswordController@verify')->name('password.verify');
 	post('reset-password', 'Auth\ResetPasswordController@resetPassword')->name('password.reset');

	group(['middleware' => ['auth:api']], function() {
		// protected API routes go here
		get('/', 'HomeController@index');

		// auth
    get('me', 'Auth\AuthController@me');
	   post('logout', 'Auth\AuthController@logout')->name('auth.logout');
	   post('refresh', 'AuthController@refresh')->name('auth.refresh');

		//  Users' routes
		get('/users', 'UsersController@index')->name('users.index');
		get('/users/{user}', 'UsersController@show')->name('users.show');
		get('/users/liked/tracks', 'UsersController@tracksLiked')->name('users.tracks.liked');
		get('/users/{id}/tracks', 'UsersController@tracks')->name('users.tracks');
		post('/users/toggle-like/{track}', 'UsersController@toggleLike')->name('users.tracks.toggle-like');
		get('/users/has-liked/{track}', 'UsersController@hasLiked')->name('users.tracks.has-liked');

		// Tracks routes
		get('tracks', 'TracksController@index')->name('api.tracks');
		post('tracks', 'TracksController@store')->name('api.tracks.store');
		get('tracks/{hash}', 'TracksController@show')->name('api.tracks.show');
		get('tracks/{track}/edit', 'TracksController@edit')->name('api.tracks.edit');
		put('tracks/{track}/edit', 'TracksController@update')->name('api.tracks.edit');
		del('efase/tracks/{track}', 'TracksController@destroy')->name('api.tracks.destroy');

		// Categories routes
		get('categories', 'CategoriesController@index')->name('category.index');
		get('categories/{slug}', 'CategoriesController@show')->name('category.show');

		// Artists routes
		get('artists', 'ArtistsController@index')->name('artists');
		get('artists/{hash}', 'ArtistsController@show')->name('artists.show');
		get('artists/{hash}/tracks', 'ArtistsController@tracks')->name('artists.tracks');
		get('artists/{artist}/edit', 'ArtistsController@edit')->name('artists.edit');
		put('artists/{artist}/edit', 'ArtistsController@update')->name('artists.update');
		del('artists/{artist}', 'ArtistsController@destroy')->name('tracks.destroy');
	});
});