<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class MessageToCustomers extends Mailable
{
    use Queueable, SerializesModels;

    private $business;
    private $messageBody;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($business, $messageBody)
    {
        $this->business     = $business;
        $this->messageBody  = $messageBody;
        $this->subject($business->name . " has a message for you");
        $this->from("Otruvez@gmail.com");
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $url = getAccountNotificationsUrl();

        return $this->markdown('emails.account.message-to-customers')->with([
            'business'  => $this->business,
            'body'      => $this->messageBody,
            'url'       => $url
        ]);
    }
}
