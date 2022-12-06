<?php

namespace App\Notifications;

use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Support\Facades\Lang;

class ResetPasswordNotification extends ResetPassword
{
	protected function buildMailMessage($url)
	{
		return (new MailMessage)
			->subject(Lang::get('Reset Password Notification'))
			->line(Lang::get('You are receiving this email because we received a password reset request for your account.'))
			->view('mail.reset', ['url' => $url])
			->action(Lang::get('Reset Password'), $url);
	}
}
