<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class LoanBorrowedEmail extends Mailable implements ShouldQueue
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
        /*
        $data = array(
            'name' => $this->maildata['name'],
            'loanName' => $this->maildata['loanName'],
            'principal' => $this->maildata['principal'],
            'date' => $this->maildata['date'],
            'interest' => $this->maildata['interest'],
            'installments' => $this->maildata['installments'],
            'interest_charge_frequency' => $this->maildata['interest_charge_frequency'],
            'total' => $this->maildata['total'],
            'borrower_name' => $this->maildata['borrower_name']
        );
        */
        $data = $this->maildata;

        return $this->markdown('emails.loan.loan-borrowed')
                    ->with(['data' => $data])
                    ->subject('Your Loan Request Has Been Approved');
    }
}
