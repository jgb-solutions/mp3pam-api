<?php

get('/', function () {
	return view('pages.home');
});

// Musics routes
get('t/{music}', ['as' => 'music.get','uses' => 'MusicsController@download']);