<?php

namespace  App\Services\Mail;

use Illuminate\Support\Facades\Mail;
use App\Mail\RegisterMail;

class MailService
{
    public function sendWelcomeMail($user)
    {
        Mail::to($user->email)->send(new RegisterMail($user));
    }
}
