<?php

namespace App\Jobs;

use App\Models\Track;
use App\Helpers\MP3Pam;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class TagTrack implements ShouldQueue
{
	use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

	protected $track;
	/**
	 * Create a new job instance.
	 *
	 * @return void
	 */
	public function __construct(Track $track)
	{
		$this->track = $track;
	}

	/**
	 * Execute the job.
	 *
	 * @return void
	 */
	public function handle()
	{
		MP3Pam::tag($this->track);
	}
}
