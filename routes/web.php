<?php
// Tracks routes
get('t/{track}', 'TracksController@download')->name('tracks.get');
get('tracks/{hash}', 'TracksController@show')->name('tracks.show');
get('play/{track}', 'TracksController@play')->name('tracks.play');

get('ff', function() {
	FFMpeg::fromDisk('public')
		->open('track.mp3')
		->addFilter(function($filters) {
			$filters->addMetadata([
				'artwork' => storage_path('app/public/final.png'),
				'title' => 'my title'
			]);
		})
		->export()
		// ->toDisk('public')
		->inFormat(new \FFMpeg\Format\Audio\Mp3)
		->save('track3.mp3');
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

post('do-spaces', function()
{
	$path = request()->file('image')->store('images', 'spaces');

	return \Storage::disk('spaces')->url($path);
});

post('wasabi', function()
{
	$path = request()->file('image')->store('images', 'wasabi');

	return \Storage::disk('wasabi')->url($path);
});

get('do-spaces-url', function()
{
	$spaces = \Storage::disk('spaces');
	$client = $spaces->getDriver()->getAdapter()->getClient();

	$command = $client->getCommand('PutObject', [
			'Bucket'	=> config('filesystems.disks.spaces.bucket'),
			'Key'			=> 'images/' . time() . request('filename'),
			'ACL'			=> 'public-read',
	]);

	$request = $client->createPresignedRequest($command, "+60 minutes");

	return $signed_url = (string) $request->getUri();
	return compact('signed_url');
});

get('wasabi-url', function()
{
	$wasabi = \Storage::disk('wasabi');
	$bucket = config('filesystems.disks.wasabi.bucket');
	$client = $wasabi->getDriver()->getAdapter()->getClient();
	$filename = 'images/' . time() . '.' . pathinfo(request('filename'), PATHINFO_EXTENSION);
	$url = "https://{$bucket}/{$filename}";
	$command = $client->getCommand('PutObject', [
			'Bucket'	=> $bucket,
			'Key'			=> $filename,
			// 'ResponseContentDisposition' => 'attachment; filename=' . request('filename'),
	]);

	$request = $client->createPresignedRequest($command, "+60 minutes");

	$signed_url = (string) $request->getUri();
	return compact('signed_url', 'url');
});
// Catch all routes
// Route::view('/{any}', 'pages.spa')->where('any', '^(?!api).*$');