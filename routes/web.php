<?php

get('/', function () {
	return view('pages.home');
});

// Musics routes
get('telechaje/{music}', ['as' => 'music.get','uses' => 'MusicController@download']);