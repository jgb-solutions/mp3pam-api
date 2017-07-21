<?php

get('/', 'PagesController@index');

// Musics routes
get('t/{music}', 'API\MusicsController@download')->name('musics.get');

get('cache', function() {
	return Cache::get('_musics_index_2');
});

get('mail', function() {
	Mail::to('john@johndoe.com')
		->queue(new App\Mail\WelcomeEmail);
});