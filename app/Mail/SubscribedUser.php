<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class SubscribedUser extends Mailable
{
    use Queueable, SerializesModels;

    private $messageBody;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($messageBody)
    {
        $this->messageBody  = $messageBody;
        $this->subject("Thanks for your subscription!");
        $this->from("otruvez@gmail.com", 'Otruvez');
    }


    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $url = getAccountNotificationsUrl();

        return $this->markdown('emails.account.subscribed-user')->with([
            'body'      => $this->messageBody,
            'url'       => $url
        ]);
    }
}
