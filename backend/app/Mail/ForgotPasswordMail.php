<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ForgotPasswordMail extends Mailable
{
    use Queueable, SerializesModels;

    public $username;
    public $new_password;

    public function __construct($username, $new_password)
    {
        $this->username = $username;
        $this->new_password = $new_password;
    }

    public function build()
    {
        return $this->subject('Reset Password Akun Anda')
                    ->view('emails.forgot_password')
                    ->with([
                        'username' => $this->username,
                        'password' => $this->new_password,
                    ]);
    }
}
