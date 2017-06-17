<?php

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

// Route::middleware('auth:api')->get('/user', function (Request $request) {
//     // return $request->user();
//     return 'yeah';
// });

group([
	'prefix' => 'v1',
	], function() {
	//  get all the users
	get('/user/{user}', 'MusicController@sayHello');
	get('/users', 'UsersController@index');
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


// // Categories routes
// Route::group(['prefix' => 'kategori'], function() {
// 	get('{slug}', [	'as' => 'cat.show','uses' => 'CategoryController@show']);
// 	get('{slug}/mizik', [	'as' => 'cat.music','uses' => 'CategoryController@musics']);
// 	get('{slug}/videyo', [	'as' => 'cat.video','uses' => 'CategoryController@videos']);
// });

// // Musics routes
// get('mizik', ['as' => 'music','uses' => 'MusicController@index']);
// get('mizik/{music}/modifye',['as' => 'music.edit','uses' => 'MusicController@edit']);
// put('mizik/{music}/modifye',['as' => 'music.update','uses' => 'MusicController@update']);
// post('mizik/{id}/imel-twit',['as' => 'music.emailAndTweet','uses' => 'MusicController@emailAndTweet']);
// get('mizik/{id}/{slug?}',['as' => 'music.show','uses' => 'MusicController@show']);
// get('telechaje/mizik/{music}', ['as' => 'music.get','uses' => 'MusicController@getMusic']);
// delete('efase/mizik/{music}', ['as' => 'music.delete','uses' => 'MusicController@destroy']);
// get('mete/mizik', ['as' => 'music.upload','uses' => 'MusicController@upload']);
// get('jwe/mizik/{music}', ['as' => 'music.play','uses' => 'MusicController@play']);
// get('achte/mizik', ['as' => 'buy.list', 'uses' => 'MusicController@listBuy']);
// get('achte/mizik/{music}', ['as' => 'buy.show', 'uses' => 'MusicController@getBuy']);
// post('achte/mizik/{music}', ['as' => 'buy.post','uses' => 'MusicController@postBuy']);
// post('mete/mizik', ['as' => 'music.store', 'uses' => 'MusicController@store']);

// // Video routes
// get('videyo', ['as' => 'video','uses' => 'VideoController@index']);
// get('videyo/{video}/modifye',['as' => 'video.edit','uses' => 'VideoController@edit']);
// put('videyo/{video}/modifye',['as' => 'video.update','uses' => 'VideoController@update']);
// get('videyo/{id}/{slug?}', ['as' => 'video.show','uses' => 'VideoController@show']);
// get('telechaje/videyo/{id}', ['as' => 'video.get','uses' => 'VideoController@getVideo']);
// delete('efase/videyo/{video}', ['as' => 'video.delete','uses' => 'VideoController@destroy']);
// get('mete/videyo', ['as' => 'video.upload','uses' => 'VideoController@upload']);
// post('videyo', ['as' => 'video.store','uses' => 'VideoController@store']);

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

// // get('test', function() {
// // 	$music = App\Models\Music::find(198);
// // 	return $music->load('user');
// // });