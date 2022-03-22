<?php

namespace App\Services\Loan;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Input;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Hash;
use App\User;
use App\Models\General\Wallet;
use App\Models\Organization\OrgUser;
use Illuminate\Support\Facades\Mail;
use App\Models\Organization\OrgLoan;
use App\Models\Organization\OrgLoanDetail;
use Illuminate\Support\Facades\Auth;

class OrgLoanService
{
	// Process Loan
	public function processLoan(Request $request)
	{
		// Calculations
        $principle = $request->amount;
        $lengthOfLoan = $request->length_of_loan;
        $paymentFrequency = $request->paymentFrequency;
        $annualInterestRate = $request->interest_rate / 100; // Convert to decimal for simplicity

        // USE PERIOD IN YEARS FOR SIMPLICITY
        // A = P(1 + rt) where A is total amount payable, r is interest rate as a decimal, time period in years.

        switch ($paymentFrequency) {
            case 'WEEKLY':
                $time = round($paymentFrequency / 52, 1);
                $rate = $annualInterestRate / 52;
                break;
            case 'BI-WEEKLY':
                $time = round($paymentFrequency / 26, 1);
                $rate = $annualInterestRate / 26;
                break;
            case 'MONTHLY':
                $time = round($paymentFrequency / 12, 1);
                $rate = $annualInterestRate / 12;
                break;
        }
        $valueOne = $rate * $time;
        $valueTwo = 1 + $valueOne;
        $amountPayable = $principle * $valueTwo;
        $interest = $amountPayable - $principal;

        // Calculate charge per installment
        $installmentAmount = $amountPayable / $paymentFrequency;
	}
}