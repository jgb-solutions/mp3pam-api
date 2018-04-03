<?php

get('/', 'PagesController@index');

// Musics routes
get('t/{music}', 'API\MusicsController@download')->name('musics.get');
get('musics/{hash}', 'MusicsController@show')->name('musics.show');
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

get('b2-put', function()
{
	\Storage::disk('b2')->put('jgb.txt', 'web developer');
});

get('b2-get', function()
{
	return \Storage::disk('b2')->url('musics/0Es1ghj4ftd9.mp3');
});

post('b2-store', function()
{
	$path = request()->file('music')->store(
    'musics/'.time(), 'b2'
	);
	$path = request()->file('music')->storeAs(
    	'musics', str_random(12) . '.mp3', 'b2', [
    		'X-Bz-Info-b2-content-disposition'	 => 'attachment'
    	]
	);

	return $path;
});

get('b2-at', function()
{
	$path = \Storage::disk('b2')->getDriver()->put('music/index4.txt', 'test', [
		// 'visibility' => 'public',
		'Expires' => 'Expires, Fri, 30 Oct 1998 14:19:41 GMT',
		'ContentDisposition' => 'attachment'
	]);

	dd($path);
});

get('b2-del', function()
{
	\Storage::disk('b2')->delete('jgb.txt');
});

get('b2-stream', function()
{
	// return \Storage::disk('b2')->response("avatars/1520745144/us8i5AUeXS6zOwBei9W7bT617rDQJLlulECpOaHY.jpeg");
	$file = "avatars/1520745144/us8i5AUeXS6zOwBei9W7bT617rDQJLlulECpOaHY.jpeg";
	$fs = Storage::disk('b2')->getDriver();
	$stream = $fs->readStream($file);

	return Response::stream(function() use($stream) {
	    fpassthru($stream);
	}, 200, [
	    "Content-Type" => $fs->getMimetype($file),
	    "Content-Length" => $fs->getSize($file),
	    "Content-disposition" => "attachment; filename=\"" . basename($file) . "\"",
	]);
});