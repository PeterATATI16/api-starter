<?php
namespace App\Services;

use Illuminate\Support\Facades\Mail;
use Illuminate\Mail\Mailable;

class GenericMailService
{
    /**
     * Méthode générique pour envoyer un email.
     *
     * @param string $email
     * @param Mailable $mailable
     */
    public function sendEmail($email, Mailable $mailable)
    {
        Mail::to($email)->send($mailable);
    }
}
