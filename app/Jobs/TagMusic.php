<?php

namespace App\Jobs;

use App\Models\Music;
use App\Helpers\MP3Pam;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class TagMusic implements ShouldQueue
{
	use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

	protected $music;
	/**
	 * Create a new job instance.
	 *
	 * @return void
	 */
	public function __construct(Music $music)
	{
		$this->music = $music;
	}

	/**
	 * Execute the job.
	 *
	 * @return void
	 */
	public function handle()
	{
		return MP3Pam::tag($this->music);
	}
}
