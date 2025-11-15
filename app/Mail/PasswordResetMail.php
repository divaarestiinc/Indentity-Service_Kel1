<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class PasswordResetMail extends Mailable
{
    use Queueable, SerializesModels;

    public $token;
    public $email;

    public function __construct($token, $email)
    {
        $this->token = $token;
        $this->email = $email;
    }

    public function build()
    {
        return $this->subject('Password Reset Request')
                    ->view('emails.password_reset')
                    ->with([
                        'token' => $this->token,
                        'email' => $this->email,
                        'reset_url' => url('/password-reset?email='.$this->email.'&token='.$this->token)
                    ]);
    }
}
