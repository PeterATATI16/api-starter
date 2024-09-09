<?php
namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class GenericPasswordMail extends Mailable
{
    use Queueable, SerializesModels;

    public $user;
    public $password;

    /**
     * Create a new message instance.
     *
     * @param $user
     * @param string|null $password
     */
    public function __construct($user, $password = null)
    {
        $this->user = $user;
        $this->password = $password;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject('Welcome to the platform')
            ->view('emails.generic-password')
            ->with([
                'name' => $this->user->name,
                'email' => $this->user->email,
                'password' => $this->password,
            ]);
    }
}
