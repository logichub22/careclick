<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\User;

class UserActivatedEmail extends Mailable 
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
        );

        return $this->markdown('emails.user.activated')
                    ->with(['data' => $data])
                    ->subject('Your Account Has Been Activated');
    }
}
