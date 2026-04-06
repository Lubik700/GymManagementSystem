<?php

namespace App\Mail;

use Illuminate\Mail\Mailable;

class OtpMail extends Mailable
{
    public function __construct(public string $otp) {}

    public function build()
    {
        return $this->subject('Your OTP - Gym Registration')
                    ->view('emails.otp');
    }
}