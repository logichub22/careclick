<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class NotifyLenderEmail extends Mailable implements ShouldQueue
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
        /*
        $data = array(
            'principal' => $this->maildata['principal'],
            'date' => $this->maildata['date'],
            'interest' => $this->maildata['interest'],
            'interest_due' => $this->maildata['interest_due'],
            'package' => $this->maildata['package'],
            'installments' => $this->maildata['installments'],
            'interest_charge_frequency' => $this->maildata['interest_charge_frequency'],
            'total' => $this->maildata['total'],
            'currency' => $this->maildata['currency'],
        );
        */
        $data = $this->maildata;

        return $this->markdown('emails.loan.notify-lender')
                    ->with(['data' => $data])
                    ->subject('New Loan Request');;
    }
}
