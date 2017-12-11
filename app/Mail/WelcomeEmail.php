<?php

namespace App\Mail;

use App\Models\Music;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class WelcomeEmail extends Mailable
{
    use Queueable, SerializesModels;

    protected $music;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(Music $music)
    {
        $this->music = $music;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('emails.welcome')
            ->subject($this->music->title);
    }
}
