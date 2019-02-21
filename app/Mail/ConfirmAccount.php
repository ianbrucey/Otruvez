<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class ConfirmAccount extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    private $token;
    private $email;
    private $fname;

    public function __construct($fname ,$email, $token)
    {
        $this->token = $token;
        $this->email = $email;
        $this->fname = ucfirst($fname);
        $this->subject("Please confirm your new account");
        $this->from("otruvez@gmail.com", 'Otruvez');
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {

//        $activateUrl = "/user/activateUserAccount";
//        $confirmAccountURL = sprintf("%s%s?email=%s&token=%s",env('APP_URL'),$activateUrl, $this->email,$this->token);
        return $this->markdown('emails.account.confirm-account')
            ->with('name', $this->fname)
            ->with('token', $this->token);
    }
}
