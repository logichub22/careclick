<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class GroupBulkEmail extends Mailable implements ShouldQueue
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
        $data = [
            'subject' => $this->maildata['subject'],
            'message' => $this->maildata['message'],
            'email' => $this->maildata['email'],
            'name' => $this->maildata['name'],
            'group' => $this->maildata['group'],
        ];
        return $this->markdown('emails.group.bulk-email')
                    ->with(['data' => $data])
                    ->subject($data['subject']);
    }
}
