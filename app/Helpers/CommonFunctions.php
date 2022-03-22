<?php
namespace App\Helpers;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Carbon\Carbon;
use Carbon\CarbonInterval;


use Mail;
use App\Mail\NotifyLenderEmail;
use App\Mail\LoanBorrowedEmail;

use App\Services\Loan\CreditScoreService;

use App\Models\General\Wallet;
use App\Models\General\Saving;
use App\SavingsWallet;
use App\Models\General\GroupWallet;
use App\Models\Organization\OrganizationWallet;

use App\Models\General\GroupContribution;

use App\Models\General\Currency;
use App\Models\General\Loan;
use App\Models\General\LoanDetail;
use App\Models\General\LoanPackage;
use App\Models\General\LoanDeduction;
use App\Models\General\Transaction;

use App\User;
use App\Models\General\UserDetail;
use MicrosoftAzure\Storage\Blob\BlobRestProxy;
use MicrosoftAzure\Storage\Blob\Models\CreateContainerOptions;
use MicrosoftAzure\Storage\Common\Exceptions\ServiceException;
use MicrosoftAzure\Storage\Blob\Models\PublicAccessType;
use MicrosoftAzure\Storage\Blob\Models\ListBlobsOptions;


use GuzzleHttp\Client as GuzzleClient;

class CommonFunctions
{
    public static function userCurrency($user){
        $currency = DB::table('user_details')
            ->join('currencies', 'country_id', '=', 'user_details.country')
            ->where('user_details.user_id', '=', $user->id)
            ->select('currencies.prefix')
            ->first();

        return $currency->prefix;
    }

    public static function showLoanPackages($user){
        // Users can only access loan packages created by the organizations they belong to,
        // and in their home currency

        $currency = DB::table('user_details')
                    ->join('currencies', 'country_id', '=', 'user_details.country')
                    ->where('user_details.user_id', '=', $user->id)
                    ->select('currencies.prefix')
                    ->first();

        // Users are added by default to the first group created by the organization
        $org = DB::table('group_members')
            ->join('groups', 'groups.id', 'group_members.group_id')
            ->where('group_members.user_id', $user->id)
            ->select('groups.org_id as id')
            ->first();

        // $organization = Organization::where('admin_id', $user->id)->first();
        $packages = $org != null ? DB::table('loan_packages')
            ->where('status', true)
            ->where(function ($query) use ($user) {
                $query
                    ->where('user_id', null)
                    ->orWhere('user_id', '!=', $user->id);
            })
            ->where('currency', '=', $currency->prefix)
            ->where('org_id', $org->id)
            ->get()
            :
            DB::table('loan_packages')
            ->where('status', true)
            ->where('user_id', '!=', $user->id)
            ->where('currency', '=', $currency->prefix)
            ->get()
            ;

        return $packages;
    }

    public static function userHasActiveLoan($user){
        $loans = Loan::where('user_id', $user->id)
            ->whereIn('status', [0, 1]) //pending or approved
            ->count();

        return $loans > 0;
    }

    public static function generateLoanData($user, $package_id, $principal, $lengthOfLoan, $titleOfLoan){
        //
        $serviceFee = 1000;
        $creditScoreService = new CreditScoreService();

        $score = $creditScoreService->calculateCreditScore($user);

        //user wallet balance
        $wallet = Wallet::where('user_id', $user->id)->first();
        $walletBalance = $wallet->balance;

        //the loan package details
        $package = DB::table('loan_packages')->where('status', true)->where('id',$package_id)->first();

        // Calculations
        $paymentFrequency = strtoupper($package->repayment_plan);
        $annualInterestRate = $package->interest_rate / 100; // Convert to decimal for simplicity
        $creditScore = $package->min_credit_score;  //to be changed according to the credit score engine

        // $processingFee = 0.02*principal;
        $rate = 0;
        $time = 0;
        $currency = $package->currency;

        // USE PERIOD IN YEARS FOR SIMPLICITY
        // A = P(1 + rt) where A is total amount payable, r is interest rate as a decimal, t is time in years.

        switch ($paymentFrequency) {
            case 'WEEKLY':
                $time = round($lengthOfLoan / 52, 1);
                //$rate = $annualInterestRate / 52;
                break;
            case 'BI-WEEKLY':
                $time = round($lengthOfLoan / 26, 1);
                //$rate = $annualInterestRate / 26;
                break;
            case 'MONTHLY':
                $time = round($lengthOfLoan / 12, 1);
                //$rate = $annualInterestRate / 12;
                break;
        }

        $valueOne = $annualInterestRate * $time;
        $valueTwo = 1 + $valueOne;
        $initialPayable = $principal * $valueTwo; // No service fee here
        $amountPayable = $initialPayable + $serviceFee;
        // $totalAmountPayable = $processingFee + $amountPayable;
        $interest = $initialPayable - $principal;

        // Calculate charge per installment
        $installmentAmount = $initialPayable / $lengthOfLoan;

        $request = [
            'principal' => floatval($principal),
            'subTotal' => floatval($initialPayable),
            'totalAmount' => floatval(round($amountPayable, 2)),
            'totalInterest' => floatval(round($interest, 2)),
            'installmentAmount' => floatval(round($installmentAmount, 2)),
            'titleOfLoan' => $titleOfLoan,
            'lengthOfLoan' => $lengthOfLoan,
            'expectedScore' => $creditScore,
            'score' => $score,
            'walletBalance' => $walletBalance,
            'serviceFee' => $serviceFee,
            'paymentFrequency' => $paymentFrequency,
            'annualInterest' =>$package->interest_rate,
            'currencyUnit' => $currency,
        ];

        return $request;
    }

    public static function requestLoan($user, $package, $amount, $duration, $loan_title){
        // $serviceFee = 1000;
        // $creditScoreService = new CreditScoreService();

        // $score = $creditScoreService->calculateCreditScore($user);

        if(is_null($package->org_id)) {
            $wallet = Wallet::where('user_id', $package->user_id)->first();
            $lender = User::where('id', $package->user_id)->first();
        }
        else {
            $wallet = OrganizationWallet::where('org_id', $package->org_id)->first();
            $lender = DB::table('users')
                    ->join('organizations', 'organizations.admin_id','=','users.id')
                    ->where('organizations.id','=',$package->org_id)
                    ->select('users.*')
                    ->first();
        }

        if($amount > $wallet->balance) {
            // Lender's balance is too small
            return [false, "Loan Denied! This loan cannot be lend out at the moment."];
        }
        else {
            // Generate loan data
            $loan_data = self::generateLoanData($user, $package->id, $amount, $duration, $loan_title);
            $score = $loan_data['score'];
            $interest = $loan_data['totalInterest'];
            $initialPayable = $loan_data['subTotal'];
            $installmentAmount = $loan_data['installmentAmount'];
            $lengthOfLoan = $duration;
            $paymentFrequency = $loan_data['paymentFrequency'];

            // Store into loans table
            $loan = new Loan();
            $loan->loan_title = $loan_title;
            $loan->user_id = $user->id;
            $loan->loan_package_id = $package->id;
            $loan->amount = $amount;
            $loan->borrower_credit_score = $score;
            $loan->status = 0;
            $loan->save();

            /*
            // Interest Calculations
            $lengthOfLoan = $duration;
            $paymentFrequency = strtoupper($package->repayment_plan);
            $annualInterestRate = $package->interest_rate / 100;

            $rate = 0;
            $time = 0;
            $currency = $package->currency;

            $principal = $amount;

            switch ($paymentFrequency) {
                case 'WEEKLY':
                    $time = round($lengthOfLoan / 52, 1);
                    //$rate = $annualInterestRate / 52;
                    break;
                case 'BI-WEEKLY':
                    $time = round($lengthOfLoan / 26, 1);
                    //$rate = $annualInterestRate / 26;
                    break;
                case 'MONTHLY':
                    $time = round($lengthOfLoan / 12, 1);
                    //$rate = $annualInterestRate / 12;
                    break;
            }

            $valueOne = $annualInterestRate * $time;
            $valueTwo = 1 + $valueOne;
            $initialPayable = $principal * $valueTwo;
            $amountPayable = $initialPayable + $serviceFee;
            // $totalAmountPayable = $processingFee + $amountPayable;
            $interest = $initialPayable - $principal;

            // Calculate charge per installment
            $installmentAmount = $initialPayable / $lengthOfLoan;
            */

            $payDate = $date = Carbon::today()->toDateString();

            // Store into Loan Details Table
            $detail = new LoanDetail();
            $detail->loan_id = $loan->id;
            $detail->package_name = $package->name;
            $detail->principal_due = $loan->amount;
            $detail->interest_due = $interest;
            $detail->amount_payable = $initialPayable;
            $detail->balance = $initialPayable;
            $detail->charge_per_installment = $installmentAmount;
            $detail->no_of_installments = $lengthOfLoan;
            $detail->interest_charge_frequency = $paymentFrequency;
            $detail->payback_date = $date;
            $detail->next_payment_date = $payDate;
            $detail->save();

            $maildata = [
              'principal' => $amount,
              'interest' => $package->interest_rate,
              'interest_due' => $interest,
              'package' => $package->name,
              'loanName' => $loan_title,
              'name' => $user->name,
              'installments' => $lengthOfLoan,
              'interest_charge_frequency' => $paymentFrequency,
              'total' => $initialPayable,
              'currency' => $package->currency,
              'date' => $date
            ];

            // Send mail to lender
            Mail::to($lender->email)->send(new NotifyLenderEmail($maildata));

            // Successfully
            return [
                true,
                "Your loan application has been submitted successfully and will be reviewed by the lender for approval.",
                $loan
            ];
        }
    }

    public static function calcPaymentDate($installments, $payment_frequency)
    {
    	$today = Carbon::today();

    	if ($payment_frequency === "WEEKLY") {
    		$newDay = $today->addWeeks($installments);
    		$date = $newDay->toDateString();
    	} elseif ($payment_frequency === "BI-WEEKLY") {
    		$newDay = $today->addWeeks($installments*2);
    		$date = $newDay->toDateString();
    	} else{
    		$newDay = $today->addMonths($installments);
    		$date = $newDay->toDateString();
    	}

    	return $date;
    }


    public static function loanRepaymentScheduler($id){
        $loan = Loan::findOrFail($id);

        $details = DB::table('loans')
                ->join('loan_details', 'loan_details.loan_id', '=', 'loans.id')
                ->where('loans.id', $loan->id)
                ->select('loan_details.*', 'loans.currency')
                ->first();

        if(is_null($details->loan_schedule)){

            $payback_date = $details->payback_date;
            $bdate = Carbon::parse($details->created_at);

            if($details->interest_charge_frequency === "MONTHLY") {
                $interval = CarbonInterval::months(1);
            } elseif($details->interest_charge_frequency === "WEEKLY") {
                $interval = CarbonInterval::weeks(1);
            } else {
                $interval = CarbonInterval::weeks(2);
            }

            $period = new \DatePeriod($bdate, $interval, new Carbon($payback_date));

            $schedules = [];

            $installment = round($details->charge_per_installment, 2);
            $pendingBalance = round($details->amount_payable, 2);

            foreach($period as $key=>$date) {
                $date = $date->format("Y-m-d");

                $schedule = [
                    "scheduled_date" => $date,
                    "amount" => $installment,
                    "status" => 0,
                    "payment_date" => "",
                    "amount_paid" => 0.00,
                ];

                array_push($schedules, $schedule);

                // handle possible 2 d.p difference for final instalment
                $pendingBalance -= $installment;
                // $pendingBalance = $key === \array_key_last($period) ? $installment : $pendingBalance - $installment;
            }

            // Final installment was not included in the above loop
            $schedule = [
                "scheduled_date" => $details->payback_date,
                "amount" => round($pendingBalance + $installment, 2),
                "status" => 0,
                "payment_date" => "",
                "amount_paid" => 0.00,
            ];

            array_shift($schedules);
            array_push($schedules, $schedule);

            DB::table('loan_details')->where('id', $details->id)->update([
                'loan_schedule' => serialize($schedules),
            ]);
        }
        else{
            $schedules = unserialize($details->loan_schedule);
        }

        $data = [
            'currency' => $details->currency,
            'principal'=> number_format($details->principal_due, 2),
            'amount_to_pay' => number_format($details->amount_payable, 2),
            'balance' => number_format($details->balance, 2),
            'payment_frequency' => $details->interest_charge_frequency,
            'installments' => $details->no_of_installments,
            'start_date' => date('Y-m-d', strtotime($details->created_at)),
            'payback_date' => $details->payback_date,
            'schedule' => $schedules
        ];

        return $data;

    }

    public static function getOutstandingInstallments($loan_id){
        $loan_scheduler = self::loanRepaymentScheduler($loan_id);
        $loan_schedule = $loan_scheduler['schedule'];

        $outstanding_installments = 0;
        foreach ($loan_schedule as $schedule){
            if($schedule['status'] == 0) $outstanding_installments ++;
        }

        return $outstanding_installments;
    }

    public static function calculateAmountPayable($loan_id, $installments){
        $loan_scheduler = self::loanRepaymentScheduler($loan_id);
        $loan_schedule = $loan_scheduler['schedule'];

        $amount_payable = 0.00;
        foreach ($loan_schedule as $schedule){
            if($installments > 0){
                if($schedule['status'] == 0) $amount_payable += $schedule['amount'];
                $installments--;
            }
            else{
                break;
            }
        }

        return $amount_payable;
    }

    public static function getUserWalletBalance($user_id){
        $user_wallet = Wallet::where('user_id', $user_id)->first();
        $balance = floatval($user_wallet->balance);

        return $balance;
    }

    public static function getUserSavingsBalance($user_id){
        $savings_wallet = SavingsWallet::where('user_id', $user_id)->first();
        $balance = floatval($savings_wallet->balance);

        return $balance;
    }

    public static function repayLoan($user, $loan_id, $installments){
        $amount = self::calculateAmountPayable($loan_id, $installments);
        $today = Carbon::now()->toDateString();

        $loan = DB::table('loans')
                ->join('loan_details', 'loan_details.loan_id', '=', 'loans.id')
                ->where('loans.id', $loan_id)
                ->select('loan_details.*', 'loans.*')
                ->first();

        $package = LoanPackage::where('id', $loan->loan_package_id)->first();

        $loanAmount = $loan->amount_payable;
        $pendingBalance = $loan->balance;
        // $monthlyCharge = $loan->charge_per_installment;

        $walletBalance = floatval(DB::table('wallets')->where('user_id', $user->id)->first()->balance);

        if($walletBalance < $amount){
            return [false, 'Your wallet balance is too low. Top Up your wallet and try again!'];
        }
        //this second condition may not be necessary
        elseif($amount > $pendingBalance){
            return [false, "Repayment amount is too large for this loan!"];
        }
        else{
            $loanSchedule = unserialize($loan->loan_schedule);
            $deductions = 0.0;
            $payment_frequency = $loan->interest_charge_frequency;

            if($loan->org_id == null){
                if($installments < 1) $installments = ceil($installments);

                $totalPayments = 0;
                for($i=1; $i<=$installments; $i++){
                    foreach($loanSchedule as $index=>$schedule){
                        if($schedule['status'] == 0){
                            $monthlyCharge = $schedule['amount'];

                            $status = ($today <= $schedule['scheduled_date']) ? 1 : 2;
                            $pendingBalance -= $monthlyCharge;

                            $loanSchedule[$index]['status'] = $status;
                            $loanSchedule[$index]['payment_date'] = $today;
                            $loanSchedule[$index]['amount_paid'] = $monthlyCharge;
                            $deductions += $monthlyCharge;

                            $totalPayments++;

                            $thisPaymentDate = Carbon::parse($schedule['scheduled_date']);

                            if ($payment_frequency === "WEEKLY") {
                                $nextDate = $thisPaymentDate->addWeeks(1)->toDateString();
                            } elseif ($payment_frequency === "BI-WEEKLY") {
                                $nextDate = $thisPaymentDate->addWeeks(1*2)->toDateString();
                            } else{
                                $nextDate = $thisPaymentDate->addMonths(1)->toDateString();
                            }

                            //New Deduction
                            $deduction = new LoanDeduction();
                            $deduction->loan_id = $loan_id;
                            $deduction->user_id = $user->id;
                            $deduction->amount = $monthlyCharge;
                            $deduction->pending_balance = $pendingBalance;
                            $deduction->status = $status;
                            $deduction->save();

                            break;
                        } // endif
                    } // end foreach
                } // end for loop


                //Initiate Debit Transaction -----------
                $transaction = new Transaction();
                $transaction->user_id = $user->id;
                $transaction->txn_code = strtoupper(Str::random(10));
                $transaction->txn_type = 2;
                $transaction->transaction_type_id = DB::table('transaction_types')->where('name', 'loan_repayment')->value('id');
                $transaction->amount = $deductions;
                $transaction->save();

                $newWalletBalance = $walletBalance - $deductions;

                //Debit Borrower Wallet
                DB::table('wallets')->where('user_id', $user->id)->update(['balance' => $newWalletBalance]);


                // Credit lender -----------

                //Initiate Credit Transaction.
                $transaction = new Transaction();

                if(is_null($package->org_id)) {
                    $lender_wallet = Wallet::where('user_id', $package->user_id)->first();

                    $transaction->user_id = $package->user_id;
                }
                else {
                    $lender_wallet = OrganizationWallet::where('org_id', $package->org_id)->first();

                    $transaction->org_id = $package->org_id;
                }

                $lender_wallet_balance = floatval($lender_wallet->balance);

                $newBal = $lender_wallet_balance + $deductions;
                $lender_wallet->balance = $newBal;
                $lender_wallet->save();

                $transaction->txn_code = strtoupper(Str::random(10));
                $transaction->txn_type = 1;
                $transaction->transaction_type_id = DB::table('transaction_types')->where('name', 'loan_repayment')->value('id');
                $transaction->amount = $deductions;
                $transaction->save();


                // Serialize and update schedule
                DB::table('loan_details')->where('loan_id', $loan_id)->update([
                    'balance' => $pendingBalance,
                    'loan_schedule' => serialize($loanSchedule)
                ]);


                //Update Loan Details
                $message = "Loan repayment of {$loan->currency} " . number_format($deductions, 2) . " was successful. Well done!";

                if(end($loanSchedule)['status'] != 0){
                    $loanStatus = 3;
                    foreach($loanSchedule as $index=>$schedule){
                        if($schedule['status'] == 2){
                            $loanStatus = 4;
                            break;
                        }
                    }

                    DB::table('loans')->where('id', $loan_id)->update([
                        'status' => $loanStatus
                    ]);

                    $message = "Congratulations. You have completed your loan repayment! Thank you for using our service.";
                }

                return [true, $message];
            }
            else{
                return [false, 'Loan repayment was unsuccessful! Try Again!'];
            }
        }
    }

    public static function getUserGroups($user){
        $groups = DB::table('groups')
                ->join('group_members', 'group_members.group_id', '=', 'groups.id')
                ->join('group_contributions_settings', 'group_contributions_settings.group_id', '=', 'groups.id')
                ->where('group_members.user_id', '=', $user->id)
                ->select('groups.*', 'group_contributions_settings.*', 'group_members.status as membershipstatus', 'group_members.is_admin as is_admin')
                ->get();

        return $groups;
    }

    public static function makeContribution($user, $group_id){
        $group = DB::table('groups')->where('id', $group_id)->first();

        $group_settings = DB::table('group_contributions_settings')
                        ->where('group_id', $group_id)
                        ->first();

        $amount = $group_settings->amount;

        $user_wallet = Wallet::where('user_id', $user->id)->first();
        $balance = floatval($user_wallet->balance);

        $group_wallet = GroupWallet::where('group_id', $group_id)->first();
        $group_balance = floatval($group_wallet->balance);

        if($amount <= $balance){
            $transaction_type = DB::table('transaction_types')->where('name', 'contribution')->value('id');
            //Debit Main Wallet
            $transaction = new Transaction();
            $transaction->user_id = $user->id;
            $transaction->txn_code = strtoupper(Str::random(10));
            $transaction->txn_type = 2;
            $transaction->transaction_type_id = $transaction_type;
            $transaction->amount = $amount;
            $transaction->save();

            $nb = $balance - $amount;
            $user_wallet->balance = $nb;
            $user_wallet->save();

            //Credit Savings Wallet
            $transaction = new Transaction();
            $transaction->org_id = $group->org_id;
            $transaction->group_id = $group_id;
            $transaction->txn_code = strtoupper(Str::random(10));
            $transaction->txn_type = 1;
            $transaction->transaction_type_id = $transaction_type;
            $transaction->amount = $amount;
            $transaction->save();

            $newBalance = $group_balance + $amount;
            $group_wallet->balance = $newBalance;
            $group_wallet->save();

            $user_cont = GroupContribution::where('user_id', $user->id)
                ->where('group_id', $group_id)
                ->first();

            if ($user_cont) {
                $newAmount = $amount + $user_cont->amount;
                $newFreq = $user_cont->frequency + 1;

                $user_cont->amount = $newAmount;
                $user_cont->frequency = $newFreq;
                $user_cont->save();
            }
            else {
                $group_contribution = new GroupContribution();
                $group_contribution->user_id = $user->id;
                $group_contribution->group_id = $group_id;
                $group_contribution->amount = $amount;
                $group_contribution->frequency = 1;
                $group_contribution->status = 1;
                $group_contribution->save();  ;
            }

            return [true, 'Your group has been deposited with NGN' . ' ' . number_format($amount)];
        }
        else {
            return [false, 'Insufficient Funds. Please, fund your wallet and try again.'];
        }
    }

    public static function creditSavings($user, $amount, $duration){
        $user_wallet = Wallet::where('user_id', $user->id)->first();
        $balance = $user_wallet->balance;

        $savings_wallet = SavingsWallet::where('user_id', $user->id)->first();
        $savings_balance = $savings_wallet->balance;

        //Debit Main Wallet
        if($amount <= $balance) {
            // Create debit trasaction record
            $transaction = new Transaction();
            $transaction->user_id = $user->id;
            $transaction->txn_code = strtoupper(Str::random(10));
            $transaction->txn_type = 2;
            $transaction->transaction_type_id = DB::table('transaction_types')->where('name', 'savings')->value('id');
            $transaction->amount = $amount;
            $transaction->save();

            // Update wallet balance
            $new_balance = $balance - $amount;
            $user_wallet->balance = $new_balance;
            $user_wallet->save();

            /* **Not necessary (debit transaction record is sufficient)
            //Credit Savings Wallet
            $transaction = new Transaction();
            $transaction->user_id = $user->id;
            $transaction->txn_code = strtoupper(Str::random(10));
            $transaction->txn_type = 1;
            $transaction->transaction_type_id = DB::table('transaction_types')->where('name', 'savings')->value('id');
            $transaction->amount = $amount;
            $transaction->save();
            */

            // Update savings wallet balance
            $new_savings_balance = $savings_balance + $amount;
            $savings_wallet->balance = $new_savings_balance;
            $savings_wallet->duration = $duration;
            $savings_wallet->save();

            // Create savings record
            $now = Carbon::now();
            $start_date = $now->toDateString();
            $end_date = $now->addDay($duration)->toDateString();

            $savings = new Saving();
            $savings->user_id = $user->id;
            $savings->amount = $amount;
            $savings->start_date = $start_date;
            $savings->end_date = $end_date;
            $savings->duration = $duration;
            $savings->balance = $amount;
            $savings->save();

            $currency = CommonFunctions::userCurrency($user);
            $formatted_amount = number_format($amount, 2);

            return [
                true,
                "Your savings wallet has been deposited with {$currency} {$formatted_amount}.",
                $end_date
            ];
        }
        else {
            return [false, 'You cannot save more than what is in your main wallet.'];
        }
    }

    public static function debitSavings($user, $amount){
        $withdrawable_balance = CommonFunctions::withdrawableSavingsBalance($user);

        $user_wallet = Wallet::where('user_id', $user->id)->first();
        $balance = $user_wallet->balance;

        if($amount <= $withdrawable_balance) {
            $total_amount = $amount;

            $withdrawable_savings = CommonFunctions::withdrawableSavings($user);

            foreach($withdrawable_savings as $saving){
                $this_balance = $saving->balance;
                if($this_balance >= $total_amount){
                    // only or final iteration
                    DB::table('savings')->where('id', $saving->id)->update([
                        'balance' => $this_balance - $total_amount
                    ]);
                    break;
                }
                else{
                    DB::table('savings')->where('id', $saving->id)->update([
                        'balance' => 0
                    ]);
                    $total_amount -= $this_balance;
                }
            }

            //Credit Savings Wallet
            $transaction = new Transaction();
            $transaction->user_id = $user->id;
            $transaction->txn_code = strtoupper(Str::random(10));
            $transaction->txn_type = 1;
            $transaction->transaction_type_id = DB::table('transaction_types')->where('name', 'savings')->value('id');
            $transaction->amount = $amount;
            $transaction->save();

            // total balance has been deducted from iteration
            $savings_wallet = SavingsWallet::where('user_id', $user->id)->first();

            $new_savings_balance = $savings_wallet->balance - $amount;
            $savings_wallet->balance = $new_savings_balance;
            $savings_wallet->save();

            $user_wallet->balance = $balance + $amount;
            $user_wallet->save();

            $currency = CommonFunctions::userCurrency($user);
            $formatted_amount = number_format($amount, 2);

            return [true, "A withdrawal of {$currency} {$formatted_amount} from your savings wallet was successful."];
        }
        else {
            return [false, 'You cannot withdraw more than the available balance.'];
        }
    }

    public static function roleRoute($user){
        $route = "";
        $user_roles = $user->roles;

        foreach($user_roles as $role){
            // if(in_array($role->name, ['normal-user', 'group-member', 'organization-user', 'group-admin'])){
            if(in_array($role->name, ['admin', 'service-provider', 'super-organization-admin'])){
                $route = "organization";
                break;
            }
            elseif(in_array($role->name, ['normal-user', 'group-member', 'organization-user', 'group-admin'])){
                $route = "user";
            }
        }
    }

    public static function approveLoan($id, $user){
        $loan = Loan::findOrFail($id);
        $loan->status = 1;
        $loan->save();

        $principal = $loan->amount;
        $package = LoanPackage::where('id', $loan->loan_package_id)->first();
        $detail = LoanDetail::where('loan_id', $loan->id)->first();
        $lender = User::where('id', $package->org_id)->first();

        // Calculate Repyament Dates
        $date = self::calcPaymentDate($detail->no_of_installments, $detail->interest_charge_frequency);

        $today = Carbon::now();

        if ($detail->interest_charge_frequency === "WEEKLY") {
            $nextPayment = $today->addWeek(1);
            $payDate = $nextPayment->toDateString();
        }
        elseif ($detail->interest_charge_frequency === "BI-WEEKLY") {
            $nextPayment = $today->addWeeks(2);
            $payDate = $nextPayment->toDateString();
        }
        else{
            $nextPayment = $today->addMonth(1);
            $payDate = $nextPayment->toDateString();
        }

        $detail->payback_date = $date;
        $detail->next_payment_date = $payDate;

        $detail->loan_schedule = self::loanRepaymentScheduler($id);

        $detail->save();

        // Debit Lender (Owner of package) => Cash has reduced but he expects more money
        $transaction = new Transaction();
        if(is_null($package->org_id)) { // if a user owns the package
            $transaction->user_id = $package->user_id;
        } else {
            $transaction->org_id = $package->org_id;
        }
        $transaction->txn_code = strtoupper(Str::random(10));
        $transaction->txn_type = 2;
        $transaction->transaction_type_id = DB::table('transaction_types')->where('name', 'loan_request')->value('id');
        $transaction->amount = $detail->principal_due;
        $transaction->save();

        if(is_null($package->org_id)) {
            $balance = DB::table('wallets')->where('user_id', $package->user_id)->pluck('balance')->toArray();
            $mybal = implode($balance);
            $new = floatval($mybal);
            $nb = $new - $principal;

            DB::table('wallets')->where('user_id', $package->user_id)->update([
                'balance' => $nb,
            ]);
        }
        else {
            $balance = DB::table('org_wallets')->where('org_id', $package->org_id)->pluck('balance')->toArray();
            $mybal = implode($balance);
            $new = floatval($mybal);
            $nb = $new - $principal;

            DB::table('org_wallets')->where('org_id', $package->org_id)->update([
                'balance' => $nb,
            ]);
        }

        // Credit Borrower (Organization) Account ==> The debt has increased
        $transaction = new Transaction();
        $transaction->user_id = $loan->user_id;
        $transaction->txn_code = strtoupper(Str::random(10));
        $transaction->txn_type = 1;
        $transaction->transaction_type_id = DB::table('transaction_types')->where('name', 'loan_request')->value('id');
        $transaction->amount = $detail->principal_due;
        $transaction->save();

        // Deposit into wallet and make necessary deductions
        $bl = DB::table('wallets')->where('user_id', $loan->user_id)->pluck('balance')->toArray();
        $bal = implode($bl);
        $balance = floatval($bal);
        $newBalance = $balance + $principal;

        DB::table('wallets')->where('user_id', $loan->user_id)->update(['balance' => $newBalance]);

        $borrower = User::where('id', $loan->user_id)->first();

        $maildata = [
            'currency' => $package->currency,
            'principal' => $loan->amount,
            'interest' => $package->interest_rate,
            'interest_due' => $detail->interest_due,
            'package' => $package->name,
            'date' => $date,
            'loanName' => $loan->loan_title,
            // 'name' => $request->name,
            'installments' => $detail->no_of_installments,
            'interest_charge_frequency' => $detail->interest_charge_frequency,
            'total' => $detail->balance,
            'borrower_name' => $borrower->name
        ];

        // Send mail to borrower
        Mail::to($borrower->email)->send(new LoanBorrowedEmail($maildata));

        return ['success', 'Loan has been approved successfully and ' . number_format($loan->amount) . ' has been deducted from your wallet'];
    }

    public static function declineLoan($id){
        $loan = Loan::findOrFail($id);

        $loan->status = 2;
        $loan->save();

        return [true, 'Loan Request declined'];
    }

    public static function savingsBalance($user){
        $savings_wallet = SavingsWallet::where('user_id', $user->id)->first();
        return $savings_wallet->balance;
    }

    public static function withdrawableSavingsObject($user){

        $today = Carbon::now()->toDateString();

        $savings = Saving::where('user_id', $user->id)
                        ->where('end_date', '<=', $today)
                        ->where('balance', '>', 0);

        return $savings;
    }

    public static function withdrawableSavingsBalance($user){
        $savings = self::withdrawableSavingsObject($user);
                        // ->sum('balance');

        return $savings->sum('balance');
    }

    public static function withdrawableSavings($user){
        // $today = Carbon::now()->toDateString();

        $savings = self::withdrawableSavingsObject($user);

        return $savings->get();
    }

    public static function verifyAccountNumber($payload){

        $req_url = 'https://api.flutterwave.com/v3/accounts/resolve';
        $flw_key = env('FLW_SECRET_KEY');
        $header = [
            "Authorization" => "Bearer {$flw_key}",
            "Content-Type" => "application/json",
        ];

        $request_payload = [
            "account_bank" => $payload['bank'],
            "account_number" => $payload['account_number'],
        ];

        $client = new GuzzleClient(['http_errors' => false]);
        $response = $client->request('POST', $req_url, [
            "headers" => $header,
            "body" => json_encode($request_payload)
        ]);

        $amount = $payload['amount'];
        $charges = round(self::calculateFlutterwaveCharges($amount), 2);

        $request_response = json_decode($response->getBody());
        return [
            'fw_response' => $request_response,
            'charges' => $charges,
            'total' => round($charges + $amount, 2)
        ];
    }


    public static function getAllBanks($currency){
        $curr = "";
        if($currency == "NGN"){
            $curr = "NG";
        }
        else if($currency == "KES"){
            $curr = "KE";
        }

        $req_url = 'https://api.flutterwave.com/v3/banks/'.$curr;
        $flw_key = env('FLW_SECRET_KEY');
        $header = [
            "Authorization" => "Bearer {$flw_key}",
            "Content-Type" => "application/json",
        ];

        $client = new GuzzleClient();
        $response = $client->request('GET', $req_url, [
            "headers" => $header,
        ]);

        $request_response = json_decode($response->getBody());
        return $request_response->data;
    }

    public static function calculateFlutterwaveCharges($amount){
        $amount = floatval($amount);
        $cap = 2000;
        $percentage = 1.4;

        $charge = ($amount * $percentage) / 100;

        return $charge <= $cap ? $charge : $cap;
    }

    public static function TransferViaFlutterwave($payload)
    {
        $req_url = 'https://api.flutterwave.com/v3/transfers';
        $flw_key = env('FLW_SECRET_KEY');
        $header = [
            "Authorization" => "Bearer {$flw_key}",
            "Content-Type" => "application/json",
        ];

        $is_staging = env('APP_ENV') == "local";

        $request_payload = [
            "account_bank" => $payload['bank_name'],
            "account_number" => $payload['account_no'],
            "amount"=> $payload['amount'],
            "narration" => $payload["narration"],
            "currency" => $payload['currency'],
            "reference" => "new-ghs-momo-transfer",
            "beneficiary_name" =>  $payload['name']
        ];

        if($is_staging){
            $mock_success = "DU_1";
            $mock_fail = "_ST_FDU_1";
            $request_payload['reference'] = "withdraw_" . Str::random(10) . '_PMCK' . $mock_success;
        }
        else{
            $request_payload['reference'] = "withdraw_" . Str::random(14);
        }

        $client = new GuzzleClient(['http_errors' => false]);
        $response = $client->request('POST', $req_url, [
            "headers" => $header,
            "body" => json_encode($request_payload)
        ]);

        return json_decode($response->getBody());
    }

    public static function billsPaymentViaFlutterwave($payload)
    {


        $req_url = 'https://api.flutterwave.com/v3/bills';
        $flw_key = env('FLW_SECRET_KEY');
        $header = [
            "Authorization" => "Bearer {$flw_key}",
            "Content-Type" => "application/json",
        ];

        $is_staging = env('APP_ENV') == "local";

        $country = substr($payload['currency'], 0, 2);

        $request_payload = [
            "country" => "NG",
            "customer" => "+23490803840303",
            "amount" => 50,
            "recurrence" => "ONCE",
            "type" => "AIRTIME",
            "reference"  => "9300049404444"
        ];


        if($is_staging){
            $mock_success = "DU_1";
            $mock_fail = "_ST_FDU_1";
            $request_payload['reference'] = "bills-payment_" . Str::random(10) . '_PMCK' . $mock_success;
        }
        else{
            $request_payload['reference'] = "bills-payment_" . Str::random(14);
        }

        $client = new GuzzleClient(['http_errors' => true]);
        $response = $client->request('POST', $req_url, [
            "headers" => $header,
            "body" => json_encode($request_payload)
        ]);

        return json_decode($response->getBody());
    }



    public static function  uploadImageToAzureStorage($request){

        if(request('image')){

            $connectionString = "DefaultEndpointsProtocol=https;AccountName=".getenv('ACCOUNT_NAME').";AccountKey=".getenv('ACCOUNT_KEY');
            $blobClient = BlobRestProxy::createBlobService($connectionString);
            $createContainerOptions = new CreateContainerOptions();
            $createContainerOptions->setPublicAccess(PublicAccessType::CONTAINER_AND_BLOBS);
            $createContainerOptions->addMetaData("key1", "value1");
            $createContainerOptions->addMetaData("key2", "value2");
            // random string
            $length = 10;
            $characters = 'abcdefghijklmnopqrstuvwxyz';
            $charactersLength = strlen($characters);
            $randomString = '';
            for ($i = 0; $i < $length; $i++) {
                $randomString .= $characters[rand(0, $charactersLength - 1)];
            }

            $containerName = "new".$randomString;
            try {
                // Create container.
                $blobClient->createContainer($containerName, $createContainerOptions);

                $file = request('image');
                $filename = strtoupper(Str::random(5)) . '.' . $file->getClientOriginalExtension();
                $request->image->move(
                    base_path() . '/public/azure', $filename
                );

                $content =  fopen( base_path() . '/public/azure/' . $filename, "r");

                // Upload blob

                $blobClient->createBlockBlob($containerName, $filename,  $content);

                // List blobs

                $listBlobsOptions = new ListBlobsOptions();
                $listBlobsOptions->setPrefix("jamborow");

                do{
                    $result = $blobClient->listBlobs($containerName, $listBlobsOptions);
                    return $result;
                    foreach ($result->getBlob() as $blob)
                    {
                        $blob->getUrl();

                    }

                $listBlobsOptions->setContinuationToken($result->getContinuationToken());
                } while($result->getContinuationToken());

                $blob = $blobClient->getBlob($containerName, $filename);
                dd($blob);

                fpassthru($blob->getContentStream());
            }
            catch(ServiceException $e){
                $code = $e->getCode();
                $error_message = $e->getMessage();
                return $code.": ".$error_message;
            }
            catch(InvalidArgumentTypeException $e){

                $code = $e->getCode();
                $error_message = $e->getMessage();
                return $code.": ".$error_message;
            }
        }
        else{

            try{

                $blobClient->deleteContainer($_GET["containerName"]);
            }
            catch(ServiceException $e){
                $code = $e->getCode();
                $error_message = $e->getMessage();
                return $code.": ".$error_message;
            }
        }
    }
}
