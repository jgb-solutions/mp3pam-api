<?php

// Route::middleware('auth:api')->get('/user', function (Request $request) {
//     // return $request->user();
//     return 'yeah';
// });

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

	group(['middleware' => []], function() {
		// protected API routes go here
		get('/', 'HomeController@index');

		//  Users' routes
		get('users', 'UsersController@index')->name('users.index');
		get('users/{user}', 'UsersController@show')->name('users.show');
		get('users/{id}/musics', 'UsersController@musics')->name('users.musics');

		// Musics routes
		get('musics', 'MusicsController@index')->name('api.musics');
		post('musics', 'MusicsController@store')->name('api.musics.store');
		get('play/{music}', 'MusicsController@play')->name('api.musics.play');
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

		get('search', 'SearchController@search');

		// // Admin routes
		// Route::group(['prefix' => 'admin', 'as' => 'admin.'], function() {
		// 	get('kategori', ['as' => 'cat','uses' => 'CategoryController@getCreate']);
		// 	post('kategori', [	'as' => 'cat','uses' => 'CategoryController@postCreate']);
		// 	delete('kategori/efase/{id}', [	'as' => 'cat.delete','uses' => 'CategoryController@destroy']);
		// 	get('kategori/{category}/modifye', [	'as' => 'cat.edit','uses' => 'CategoryController@edit'])
		// ;
		// 	put('kategori/{category}/modifye', [	'as' => 'cat.update','uses' => 'CategoryController@update']);
		// 	get('/', [	'as' => 'index','uses' => 'AdminController@index']);
		// 	get('mizik', [	'as' => 'music','uses' => 'AdminController@musics']);
		// 	get('videyo', [	'as' => 'video','uses' => 'AdminController@videos']);
		// 	get('lis', [	'as' => 'playlists','uses' => 'AdminController@playlists']);
		// 	get('itilizate', ['as' => 'users','uses' => 'AdminController@users']);
		// });
	});


	// Pages
	// get('/', ['as'=>'home','uses'=>'PagesController@index']);
	// get('dekouvri', ['as'=>'discover','uses'=>'PagesController@discover']);
	// get('dekouvri/mizik', ['as'=>'discover.music','uses'=>'PagesController@discoverMusic']);
	// get('dekouvri/videyo', ['as'=>'discover.video','uses'=>'PagesController@discoverVideo']);
	// get('/cheche', ['as' => 'search','uses' => 'SearchController@getIndex']);
	// get('/kondisyon-itilizasyon', 'PagesController@index');
	// get('/tem', 'PagesController@index');

	// // Playlists
	// get('lis', ['as'=>'playlists','uses' => 'PlaylistsController@index']);
	// get('lis/kreye', ['as'=>'playlists.create','uses' => 'PlaylistsController@getCreate']);
	// get('lis/{playlist}/modifye', ['as'=>'playlist.edit','uses' => 'PlaylistsController@edit']);
	// get('lis/{playlist}/mizik', ['as'=>'playlist.musics','uses' => 'PlaylistsController@listMusics']);
	// get('lis/{id}/{slug}', ['as'=>'playlist.show','uses' => 'PlaylistsController@show']);
	// post('lis/kreye', ['as'=>'playlist.create','uses' => 'PlaylistsController@postCreate']);
	// post('lis/{playlist}/ajoute/{music}', ['as'=>'playlist.add','uses' => 'PlaylistsController@postAddMusic']);
	// put('lis/{playlist}/modifye', ['as'=>'playlist.update','uses' => 'PlaylistsController@update']);
	// delete('lis/{playlist}', ['as'=>'playlist.delete','uses' => 'PlaylistsController@destroy']);
	// delete('lis/{playlist}/{music}', ['as'=>'playlist.removeMusic','uses' => 'PlaylistsController@removeMusic']);

	// // Registration / Login
	// get('koneksyon', ['as' => 'login','uses' => 'UsersController@getLogin']);
	// post('koneksyon', ['as' => 'login','uses' => 'UsersController@postLogin']);
	// get('koneksyon/facebook', ['as'=>'facebook.login','uses'=>'UsersController@facebookRedirect']);
	// get('koneksyon/twitter', ['as'=>'twitter.login','uses' => 'UsersController@twitterRedirect']);
	// get('koneksyon/facebook/callback', 'UsersController@handleFacebookCallback');
	// get('koneksyon/twitter/callback', 'UsersController@handleTwitterCallback');
	// get('dekoneksyon', ['as' => 'logout','uses' => 'UsersController@getLogout']);
	// get('anrejistre', ['as' => 'register','uses' => 'UsersController@getRegister']);
	// post('anrejistre', ['as'=>'post.register','uses' => 'UsersController@store']);

	// // Password resets routes
	// get('modpas/reyinisyalize/{token?}',['as' => 'password.reset.init','uses' => 'Auth\PasswordController@showResetForm']);
	// post('modpas/imel', ['as' => 'password.reset.email','uses' => 'Auth\PasswordController@sendResetLinkEmail']);
	// post('modpas/reyinisyalize', ['as' => 'password.reset.process','uses' => 'Auth\PasswordController@reset']);

	// // Feed routes
	// get('feed/mizik', ['as' => 'feed.music','uses' => 'FeedController@musics']);
	// get('feed/videyo', ['as'=>'feed.video','uses' =>'FeedController@videos']);

	// // Users routes
	// get('tout-itilizate-yo', ['as' => 'users','uses' => 'UsersController@index']);
	// Route::group(['prefix' => 'kont','as' =>'user.'], function() {
	// 	get('/', [		'as' => 'index','uses' => 'UsersController@getUser']);
	// 	get('mizik', ['as' => 'musics','uses' => 'UsersController@getUserMusics']);
	// 	get('videyo', ['as' => 'videos','uses' => 'UsersController@getUserVideos']);
	// 	get('lis', ['as' => 'playlists','uses' => 'UsersController@playlists']);
	// 	get('modifye/{user?}', ['as' => 'edit','uses' => 'UsersController@edit']);
	// 	put('modifye/{user?}', ['as' => 'update','uses' => 'UsersController@update']);
	// 	delete('efase/{user}', ['as' => 'delete','uses' => 'UsersController@destroy']);
	// 	get('mizik-mwen-achte', ['as' => 'bought','uses' => 'UsersController@boughtMusics']);
	// });
	// get('@{id}', ['as' => 'user.public','uses' => 'UsersController@getUserPublic']);
	// get('@{username}', ['as' => 'user.public','uses' => 'UsersController@getUserName']);
	// get('@{id}/mizik', ['as' => 'user.public.musics','uses' => 'UsersController@getUserMusics']);
	// get('@{username}/mizik', ['as' => 'user.public.musics','uses' => 'UsersController@getUserMusics']);
	// get('@{id}/videyo', ['as' => 'user.public.videos','uses' => 'UsersController@getUserVideos']);
	// get('@{username}/videyo', ['as' => 'user.public.videos','uses' => 'UsersController@getUserVideos']);
	// get('@{id}/lis', ['as' => 'user.public.playlists','uses' => 'UsersController@playlists']);
	// get('@{username}/lis', ['as' => 'user.public.playlists','uses' => 'UsersController@playlists']);
});