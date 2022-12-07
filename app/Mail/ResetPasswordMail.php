<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ResetPasswordMail extends Mailable
{
	use Queueable, SerializesModels;

	/**
	 * Create a new message instance.
	 *
	 * @return void
	 */
	public function __construct(public $user, public $token)
	{
	}

	public function build()
	{
		return $this->from(address: 'epic@moviequotes.com', name: 'Epic Movie Quotes')
		->view('mail.reset')
		->subject('Reset Password');
	}
}
