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
		get('/users/liked/musics', 'UsersController@musicsLiked')->name('users.musics.liked');
		get('/users/{id}/musics', 'UsersController@musics')->name('users.musics');
		post('/users/toggle-like/{music}', 'UsersController@toggleLike')->name('users.musics.toggle-like');
		get('/users/has-liked/{music}', 'UsersController@hasLiked')->name('users.musics.has-liked');

		// Musics routes
		get('musics', 'MusicsController@index')->name('api.musics');
		post('musics', 'MusicsController@store')->name('api.musics.store');
		get('musics/{hash}', 'MusicsController@show')->name('api.musics.show');
		get('musics/{music}/edit', 'MusicsController@edit')->name('api.musics.edit');
		put('musics/{music}/edit', 'MusicsController@update')->name('api.musics.edit');
		del('efase/musics/{music}', 'MusicsController@destroy')->name('api.musics.destroy');

		// Categories routes
		get('categories', 'CategoriesController@index')->name('category.index');
		get('categories/{slug}', 'CategoriesController@show')->name('category.show');

		// Artists routes
		get('artists', 'ArtistsController@index')->name('artists');
		get('artists/{hash}', 'ArtistsController@show')->name('artists.show');
		get('artists/{hash}/musics', 'ArtistsController@musics')->name('artists.musics');
		get('artists/{artist}/edit', 'ArtistsController@edit')->name('artists.edit');
		put('artists/{artist}/edit', 'ArtistsController@update')->name('artists.update');
		del('artists/{artist}', 'ArtistsController@destroy')->name('musics.destroy');
	});
});