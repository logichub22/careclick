<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class DeleteMemberEmail extends Mailable implements ShouldQueue
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
            'group' => $this->maildata['group'],
            'email' => $this->maildata['email'],
        );
        return $this->markdown('emails.group.delete-member')
                    ->with(['data' => $data])
                    ->subject('Membership Terminated');
    }
}
