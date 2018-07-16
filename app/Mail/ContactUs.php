<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class ContactUs extends Mailable
{
    use Queueable, SerializesModels;

    private $business;
    private $text;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($subject, $text, $emailAddress)
    {
        $this->text  = $text;
        $this->subject($subject);
        $this->from($emailAddress);
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {

        return $this->markdown('emails.general.contact-us')->with([
            'emailAddress'  => $this->from,
            'text'      => $this->text
        ]);
    }
}
