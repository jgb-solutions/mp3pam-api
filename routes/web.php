<?php

get('/', 'PagesController@index');

// Musics routes
get('t/{music}', 'API\MusicsController@download')->name('musics.get');

get('get', function() {
	return Cache::get('data');
});

get('set/{data}', function($data) {
	Cache::put('data', $data, 2);
});

get('ff', function() {
	FFMpeg::fromDisk('public')
		->open('music.mp3')
		->addFilter(function($filters) {
			$filters->addMetadata([
				'artwork' => storage_path('app/public/final.png'),
				'title' => 'my title'
			]);
		})
		->export()
		// ->toDisk('public')
		->inFormat(new \FFMpeg\Format\Audio\Mp3)
		->save('music3.mp3');
});

get('fg', function() {
	$ffmpeg = FFMpeg\FFMpeg::create(array(
	    'ffmpeg.binaries'  => '/usr/local/bin/ffmpeg',
	    'ffprobe.binaries' => '/usr/local/bin/ffprobe',
	    'timeout'  => 3600, // The timeout for the underlying process
	    'ffmpeg.threads'   => 12,   // The number of threads that FFMpeg should use
	));

	$audio = $ffmpeg->open(storage_path('app/public/hello.mp3'));
	$audio->filters()->addMetadata();
	// $audio->filters()->addMetadata([
	// 	'artwork' => storage_path('app/public/final.png')
	// ]);

	// Set an audio format
	$audio_format = new FFMpeg\Format\Audio\Mp3();
	$audio->save($audio_format, storage_path('app/public/hello2.mp3'));

	// $waveform = $audio->waveform();
	// $waveform->save(storage_path('app/public/waveform.png' ));
});