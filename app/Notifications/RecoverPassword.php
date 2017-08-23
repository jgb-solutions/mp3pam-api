<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class RecoverPassword extends Notification
{
	use Queueable;

	protected $code;

	/**
	 * Create a new notification instance.
	 *
	 * @return void
	 */
	public function __construct($code)
	{
		$this->code = $code;
	}

	/**
	 * Get the notification's delivery channels.
	 *
	 * @param  mixed  $notifiable
	 * @return array
	 */
	public function via($notifiable)
	{
		return ['mail'];
	}

	/**
	 * Get the mail representation of the notification.
	 *
	 * @param  mixed  $notifiable
	 * @return \Illuminate\Notifications\Messages\MailMessage
	 */
	public function toMail($notifiable)
	{
		return (new MailMessage)
			->subject('Rekiperasyon Modpas')
			->line('Kòd pou reyinisyalize modpas ou a se: <b>' . $this->code . '<b>')
			->line('Mèsi paske w chwazi itilize ' . config('app.name') . ' !');
	}

	/**
	 * Get the array representation of the notification.
	 *
	 * @param  mixed  $notifiable
	 * @return array
	 */
	public function toArray($notifiable)
	{
		return [
			//
		];
	}
}
