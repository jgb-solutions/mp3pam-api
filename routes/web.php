<?php

get('/', 'PagesController@index');

// Musics routes
get('t/{music}', 'API\MusicsController@download')->name('musics.get');

get('cache', function() {
	return Cache::get('_musics_index_2');
});

get('path', function() {
	return public_path(config('site.defaultThumbnail'));
});