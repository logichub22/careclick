<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class SendMemberEmail extends Mailable 
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
        // Array
        $data = array(
            'name' => $this->maildata['name'],
            'subject' => $this->maildata['subject'],
            'message' => $this->maildata['message'],
            'email' => $this->maildata['email'],
            'sender' => $this->maildata['sender'],
        );

        return $this->markdown('emails.group.message-member')
                    ->with(['data' => $data])
                    ->subject($data['subject']);
    }
}
