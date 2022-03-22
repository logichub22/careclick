<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class GroupMemberCreated extends Mailable 
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
            'password' => $this->maildata['password'],
            'group' => $this->maildata['group'],
            'email' => $this->maildata['email'],
            'trainer' => $this->maildata['trainer']
        );

        return $this->markdown('emails.group.member-created')
                    ->with(['data' => $data])
                    ->subject($data['trainer'] ? 'Your Account Has Been Created' : 'Your Group Account Has Been Created');
    }
}
