<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class ContactEmail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public $maildata;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($maildata)
    {
        $this->maildata = $maildata;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $data = array(
            'name' => $this->maildata['name'],
            'email' => $this->maildata['email'],
            'message' => $this->maildata['message'],
            'company' => $this->maildata['company'],
            'telephone' => $this->maildata['telephone'],
        );

        return $this->markdown('emails.contact.send-message')
                    ->with(['data' => $data])
                    ->subject('New Enquiry');
    }
}
