<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class UserCreatedEmail extends Mailable
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
            'organization' => $this->maildata['organization'],
            'email' => $this->maildata['email'],
            'admin' => $this->maildata['admin']
        );

        // dd($data);
        return $this->markdown('emails.user.new-user')
                    ->with(['data' => $data])
                    ->subject('Your Account Has Been Created');
    }
}
