<?php

namespace App\Http\Controllers\Back\Individual;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Http\Controllers\Controller;
use App\Models\General\Loan;
use App\Models\General\LoanDetail;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Models\General\LoanPackage;
use App\Models\General\LoanDeduction;
use Illuminate\Support\Facades\Validator;
use App\Services\Loan\OrgLoanService;
use Illuminate\Support\Facades\Log;
use App\Models\General\Wallet;
use App\Models\Organization\Organization;
use App\Models\Organization\OrganizationWallet;
use App\Mail\LoanBorrowedEmail;
use App\Mail\NotifyLenderEmail;
use Mail;
use Carbon\Carbon;
use Carbon\CarbonInterval;
use App\Models\General\Transaction;
use App\User;
use App\Notifications\LoanAppliedNotification;
use App\Services\Loan\CreditScoreService;

use App\Helpers\CommonFunctions;

class UserLoanController extends Controller
{
    public function __construct(CreditScoreService $creditScoreService)
    {
        $this->creditScoreService = $creditScoreService;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user = Auth::user();

        $loans = Loan::orderBy('created_at', 'desc')->where('user_id', $user->id)->get();

        return view('back/individual/loan/index', compact('loans', 'loans', 'user'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        $user = Auth::user();
        $serviceFee = 1000;

        $mypackage = LoanPackage::where('id', $request->package_id)->first();

        $loan_application = CommonFunctions::requestLoan($user, $mypackage, $request->amount, $request->length_of_loan, $request->loan_title);

        if($loan_application[0] == true){
            Session::flash('success', $loan_application[1]);
            $loan = $loan_application[2];
            return redirect()->route('user-loans.show', $loan->id);
        }
        else{
            Session::flash('error', $loan_application[1]);
            return redirect()->back();
        }

        /*
        $score = $this->creditScoreService->calculateCreditScore($user);

        if(is_null($mypackage->org_id)) {
            $wallet = Wallet::where('user_id', $mypackage->user_id)->first();
            $lender = User::where('id', $mypackage->user_id)->first();
        } else {
            $wallet = OrganizationWallet::where('org_id', $mypackage->org_id)->first();
            $lender = DB::table('users')
                    ->join('organizations', 'organizations.admin_id','=','users.id')
                    ->where('organizations.id','=',$mypackage->org_id)
                    ->select('users.*')
                    ->first();
        }

        if($request->amount > $wallet->balance) {
            Session::flash('error', 'Loan Denied! This loan cannot be lend out at the moment');
            return redirect()->back();
        } else {
            // Store into loans table
            $loan = new Loan();
            $loan->loan_title = $request->loan_title;
            $loan->user_id = $request->user;
            $loan->loan_package_id = $request->package_id;
            $loan->amount = $request->amount;
            $loan->borrower_credit_score = $score;
            $loan->status = 0;
            $loan->save();

            // Interest Calculations
            $lengthOfLoan = $request->length_of_loan;
            $paymentFrequency = $request->payment_frequency;
            $annualInterestRate = $request->interest_rate / 100;

            $rate = 0;
            $time = 0;
            $currency = 'Naira';

            $principal = $request->amount;

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

            $date = $this->calcPaymentDate($request);

            $today = Carbon::now();

            if ($request->payment_frequency === "WEEKLY") {
                $nextPayment = $today->addWeek(1);
                $payDate = $nextPayment->toDateString();
            } elseif ($request->payment_frequency === "BI-WEEKLY") {
                $nextPayment = $today->addWeeks(2);
                $payDate = $nextPayment->toDateString();
            } else{
                $nextPayment = $today->addMonth(1);
                $payDate = $nextPayment->toDateString();
            }

            // Store into Loan Details Table
            $detail = new LoanDetail();
            $detail->loan_id = $loan->id;
            $detail->package_name = $request->package_name;
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

            // $package = LoanPackage::where('id', $request->package_id)->first();

            $maildata = [
              'principal' => $request->amount,
              'interest' => $request->interest_rate,
              'interest_due' => $interest,
              'package' => $mypackage->name,
              'date' => $date,
              'loanName' => $request->loan_title,
              'name' => $user->name,
              'installments' => $lengthOfLoan,
              'interest_charge_frequency' => $paymentFrequency,
              'total' => $initialPayable,
              'currency' => $mypackage->currency,
            ];

            // Send mail to lender
            Mail::to($lender->email)->send(new NotifyLenderEmail($maildata));

            Session::flash('success', 'Your loan application has been submitted successfully and will be reviewed by the lender for approval.');
            
            return redirect()->route('user-loans.show', $loan->id);
        }
        */
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $loan = Loan::findOrFail($id);

        $user = Auth::user();

        $detail = DB::table('loans')
                  ->join('loan_details', 'loan_details.loan_id', '=', 'loans.id')
                  ->select('loan_details.*', 'currency')
                  ->where(['loan_details.loan_id' => $loan->id])
                  ->first();
        
        if(is_null($detail->loan_schedule)){
            $this->scheduler($id)['schedule'];
        }

        return view('back/individual/loan/loan-details', compact('loan', 'detail', 'user'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
      //
    }

    public function browseLoans(Request $request)
    {
        $user = Auth::user();
        $packages = CommonFunctions::showLoanPackages($user);

        return view('back/individual/loan/browse-loans', compact('packages', 'user'));
    }

    public function searchLoans(Request $request)
    {
        //needs to be modified to work like browseLoans() method
        $user = Auth::user();
        // $organization = Organization::where('admin_id', $user->id)->first();
        $packages = DB::table('loan_packages')
            ->where('status', true)
            ->where('user_id', $user->id)
            ->orWhere('repayment_plan', request('repayment_plan'))
            ->orWhere('interest_rate', '>', request('min_interest'))
            ->orWhere('interest_rate', '<', request('max_interest'))
            ->orWhere('insured', 1)
            ->orWhere('insured', 0)
            ->orWhere('min_amount', '<', request('min_amount'))
            ->orWhere('max_amount', '>',request('max_amount'))
            ->get();

            // dd($packages);

        return view('back/individual/loan/browse-loans', compact('packages', 'user'));
    }


    public function getBorrowLoan(Request $request, $id)
    {
        $user = Auth::user();

        $package = LoanPackage::findOrFail($id);
        

        $org = DB::table('group_members')
            ->join('groups', 'group_id', 'group_members.group_id')
            ->where('group_members.user_id', $user->id)
            ->select('groups.org_id as id')
            ->first();

        if($package->user_id != $user->id || $package->org_id == $org->id){
            $balance = Wallet::where('user_id', $user->id)->pluck('balance')->toArray();
            $bal = implode($balance);
            return view('back/individual/loan/borrow', compact('package', 'bal', 'user'));
        }
        else{
            return abort(404);
        }
    }

    public function loanCalculations(Request $request)
    {
        $user = Auth::user();
        // Calculations
        $principal = $request->amount;
        $lengthOfLoan = $request->length_of_loan;
        $paymentFrequency = $request->payment_frequency;
        $annualInterestRate = $request->interest_rate / 100; // Convert to decimal for simplicity
        $titleOfLoan = $request->loan_title;
        $creditScore = $request->credit_score;
        $walletBalance = $request->wallet_balance;
        // $processingFee = 0.02*principal;

        //Log::debug($request);
        $rate = 0;
        $time = 0;
        $currency = 'Naira';
        $serviceFee = 1000;

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

        // CREDIT SCORE API
        $score = $this->creditScoreService->calculateCreditScore($user);

        return response()->json([
            'principal' => number_format($principal),
            'subTotal' => number_format($initialPayable),
            'totalAmount' => number_format(round($amountPayable, 2)),
            'totalInterest' => number_format(round($interest, 2)),
            'installmentAmount' => number_format(round($installmentAmount, 2)),
            'titleOfLoan' => $titleOfLoan,
            'lengthOfLoan' => $lengthOfLoan,
            'expectedScore' => $creditScore,
            'score' => $score,
            'walletBalance' => $walletBalance,
            'serviceFee' => $serviceFee,
            'paymentFrequency' => $paymentFrequency,
            'annualInterest' => $request->interest_rate,
            'currencyUnit' => $currency,
        ]);
    }

    public function creditScore()
    {
        $user = Auth::user();

        // Check email verification (score between 0 and 1)
        if ($user->verified) {
            $score_status = 0.5;
        } else {
            $score_status = 0;
        }

        // Check if he has account number
        if ( is_null($user->account_no) ) {
            $score_account = 0;
        } else {
            $score_account = 1;
        }

        // Check if wallet has half or more amount being requested
        $balance = Wallet::where('user_id', $user->id)->pluck('balance')->toArray();
        $bal = implode($balance);

        $principal = request('amount');

        $half_amount = 0.5 * $principal;

        if ($bal >= $half_amount) {
            $score_wallet = 1.5;
        } else {
            $score_wallet = 0.5;
        }

        // Check if user has profile picture set for identity purposes
        if ($user->avatar === "default.png") {
            $score_photo = 0.5;
        } else {
            $score_photo = 1;
        }

        // Check if user has had any transactions before
        $trans = DB::table('transactions')->where('user_id', $user->id)->get();

        if (count($trans) > 0) {
            $score_trans = 1;
        } else {
            $score_trans = 0;
        }

        $debits = DB::table('transactions')
            ->where('user_id', $user->id)
            ->where(function ($query) {
                $query->where('txn_type', '=', 2);
            })->get();

        if (count($debits) > 0) {
            $score_debit = 2;
        } else {
            $score_debit = 0;
        }

        $score = $score_status + $score_account + $score_wallet + $score_photo + $score_trans + $score_debit;

        return $score;
    }

    public function calcPaymentDate(Request $request)
    {

    	$installments = $request->length_of_loan;
    	$payment_frequency = $request->payment_frequency;

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

    public function scheduler($id){
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

    public function repaymentList($id){
        $scheduler = $this->scheduler($id);
        $schedules = $scheduler['schedule'];
        $options = "";

        $amounts = [];
        $currency = $scheduler['currency'];
        $total = 0.00;
        $count = 1;

        switch ($scheduler['payment_frequency']) {
            case 'WEEKLY':
                $word = "week";
                break;
            
            case 'MONTHLY':
                $word = "month";
                break;
                
            case 'BI-WEEKLY':
                $word = "2-week period";
                break;
        }

        foreach($schedules as $key=>$schedule){
            if($schedule['status'] == 0){
                $amount = $schedule['amount'];
                $total += $amount;

                $options .= "<option value='$count'>$count $word: $currency $total</option>";

                if($count == 1) $word .= 's';
    
                $count ++;
            }
        }        

        return $options;
    }

    public function generateSchedule(Request $request, $id)
    {
        $loan = Loan::findOrFail($id);

        $details = DB::table('loans')
                ->join('loan_details', 'loan_details.loan_id', '=', 'loans.id')
                ->where('loans.id', $loan->id)
                ->select('loan_details.*')
                ->first();

        $dates = [];
        $amounts = [];
        $statuses = [];
        $balances = [];
        $balance = $details->balance;

        $end = $details->payback_date;
        $bdate = Carbon::parse($details->created_at);

        $interval = 0;

        if($details->interest_charge_frequency === "MONTHLY") {
            $interval = CarbonInterval::months(1);
        } elseif($details->interest_charge_frequency === "WEEKLY") {
            $interval = CarbonInterval::weeks(1);
        } else {
            $interval = CarbonInterval::weeks(2);
        }

        $period = new \DatePeriod($bdate, $interval, new Carbon($end));

        foreach($period as $date) {
            $date = $date->format("Y-m-d");
            array_push($dates, $date);
            array_push($amounts, number_format($details->charge_per_installment, 2));
        }

        array_shift($dates);
        array_shift($amounts);

        array_push($dates, $details->payback_date);
        array_push($amounts, number_format($details->charge_per_installment, 2));

        $installments = count($dates);

        for ($x = 0; $x <= $installments - 1; $x++) {
            $newBalance = $balance - $details->charge_per_installment;
            $balance = $newBalance;
            array_push($balances, number_format($newBalance, 2));
        }

        $data = [
            'dates' => $dates,
            'amounts' => $amounts,
            'principal'=> number_format($details->principal_due),
            'amount_to_pay' => number_format($details->amount_payable),
            'payment_frequency' => $details->interest_charge_frequency,
            'installments' => $installments,
            'balances' => $balances,
            // 'outstanding' => $outstanding
        ];

        return $data;

    }

    public function approveLoan(Request $request)
    {
        $user = Auth::user();

        $loan = Loan::findOrFail($request->id);
        $principal = $loan->amount;

        $loan->status = 1;
        $loan->save();

        $rate = 0;
        $time = 0;
        $currency = 'Naira';

        $principal = $loan->amount;


        $package = LoanPackage::where('id', $loan->loan_package_id)->first();
        $date = $this->calcPaymentDate($request);
        $detail = LoanDetail::where('loan_id', $loan->id)->first();
        $lender = User::where('id', $package->user_id)->first();

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

                // dd($new);

                DB::table('wallets')->where('user_id', $package->user_id)->update([
                    'balance' => $nb,
                ]);
            } else {
                $balance = DB::table('org_wallets')->where('org_id', $package->org_id)->pluck('balance')->toArray();
                $mybal = implode($balance);
                $new = floatval($mybal);
                $nb = $new - $principal;

                DB::table('org_wallets')->where('org_id', $package->org_id)->update([
                    'balance' => $nb,
                ]);
            }

            // Credit Borrower (User) Account ==> The debt has increased
            $transaction = new Transaction();
            $transaction->user_id = $loan->user_id;
            $transaction->txn_code = strtoupper(Str::random(10));
            $transaction->txn_type = 1;
            $transaction->transaction_type_id = DB::table('transaction_types')->where('name', 'loan_request')->value('id');
            $transaction->amount = $detail->principal_due;
            $transaction->save();

            $bl = DB::table('wallets')->where('user_id', $loan->user_id)->pluck('balance')->toArray();
            $bal = implode($bl);
            $balance = floatval($bal);
            $newBalance = $balance + $principal;

            // Deposit into wallet and make necessary deductions
            $bl = DB::table('wallets')->where('user_id', $loan->user_id)->pluck('balance')->toArray();
            $bal = implode($bl);
            $balance = floatval($bal);
            $newBalance = $balance + $principal;
            DB::table('wallets')->where('user_id', $loan->user_id)->update(['balance' => $newBalance]);

            $maildata = [
                'principal' => $loan->amount,
                'interest' => $package->interest_rate,
                'interest_due' => $detail->interest_due,
                'package' => $package->name,
                'date' => $date,
                'loanName' => $loan->loan_title,
                'name' => $request->name,
                'installments' => $detail->no_of_installments,
                'interest_charge_frequency' => $detail->interest_charge_frequency,
                'total' => $detail->balance,
            ];

            // $loan = [
            //     'amount' => $request->amount,
            //     'borrower' => $user->name,
            //     'package_name' => $detail->package_name,
            // ];

            // Send mail to borrower
            Mail::to($user->email)->send(new LoanBorrowedEmail($maildata));

            // Send mail to lender
            $lender->notify(new LoanAppliedNotification($detail));
            Mail::to($lender->email)->send(new NotifyLenderEmail($maildata));

            Session::flash('success', ' ' . number_format($loan->amount) . ' has been deducted from your wallet');

           return redirect()->back();
    }

    public function declineLoan($id)
    {
        $user = Auth::user();
        $loan = Loan::findOrFail($id);

        $loan->status = 2;
        $loan->save();

        Session::flash('success', 'Loan Request declined');
        return redirect()->back();
        //Add code to send mail to borrower and lender
    }

    public function repayLoan(Request $request){
        $user = Auth::user();
        $loan_id = $request->loan_id;
        $noOfPayments = $request->repayment_count;

        $loan_repayment = CommonFunctions::repayLoan($user, $loan_id, $noOfPayments);

        $response_code = $loan_repayment[0] == true ? 'success' : 'error';
        $response_message = $loan_repayment[1];

        Session::flash($response_code, $response_message);
        return redirect()->back();
    }

    public function repayLoanOld(Request $request)
    {
        $user = Auth::user();
        $loan_id = $request->loan_id;
        $total_amount = $request->total_amount;
        $amount = $request->amount;
        $loan_details = DB::table('loan_details')->where('loan_id', $loan_id)->first();
        $today = Carbon::now();
        
        // dd($today->toDateString());

        $pending_balance = $total_amount - $amount;

        //get wallet balance and debit wallet
        $bl = DB::table('wallets')->where('user_id', $user->id)->pluck('balance')->toArray();
        $bal = implode($bl);
        $balance = floatval($bal);
        $newBalance = $balance - $amount;

        $loan = DB::table('loans')
                ->join('loan_packages', 'loan_packages.id', '=', 'loans.loan_package_id')
                ->where('loans.id', $loan_id)
                ->select('loan_packages.*', 'loans.*')
                ->first();

        $loan_deduction = DB::table('loan_deductions')->where('loan_id', $loan_id)->first();

        if($amount <= $balance){
            if($loan->org_id == null){
                //Initiate Debit Transaction
                $transaction = new Transaction();
                $transaction->user_id = $user->id;
                $transaction->txn_code = strtoupper(Str::random(10));
                $transaction->txn_type = 2;
                 $transaction->transaction_type_id = DB::table('transaction_types')->where('name', 'loan_repayment')->value('id');
                $transaction->amount = $amount;
                $transaction->save();

                //Debit Borrower Wallet
                DB::table('wallets')->where('user_id', $user->id)->update(['balance' => $newBalance]);

                //Get Lender Wallet
                $lender_wallet_bl = DB::table('wallets')->where('user_id', $loan->user_id)->pluck('balance')->toArray();
                $lender_wallet_bal = implode($lender_wallet_bl);
                $lender_wallet_balance = floatval($lender_wallet_bal);
                $newBal = $lender_wallet_balance + $amount;
                // dd($newBal);

                //Initiate Credit Transaction.
                $transaction = new Transaction();
                $transaction->user_id = $loan->user_id;
                $transaction->txn_code = strtoupper(Str::random(10));
                $transaction->txn_type = 1;
                 $transaction->transaction_type_id = DB::table('transaction_types')->where('name', 'loan_repayment')->value('id');
                $transaction->amount = $amount;
                $transaction->save();

                //Credit Lender
                DB::table('wallets')->where('user_id', $loan->user_id)->update(['balance' => $newBal]);

                //Update Loan Deductions table
                $deduction = new LoanDeduction();
                $deduction->loan_id = $loan_id;
                $deduction->user_id = $user->id;
                $deduction->amount = $amount;
                $deduction->pending_balance = $pending_balance;
                if($deduction->pending_balance <= 0){
                    $deduction->status = 1;
                }
                elseif ($today->toDateString() > $loan_details->payback_date) {
                    $deduction->status = 2;
                }
                else {
                    $deduction->status = 0;
                }
                $deduction->save();

                //Update Loan Details
                DB::table('loan_details')->where('loan_id', $loan_id)->update([
                    'balance' => $total_amount - $amount
                ]);

                Session::flash('success', 'Loan repayment was successful!');
                return redirect()->back();
            }
            else {
                Session::flash('error', 'Loan repayment was unsuccessful! Try Again!');
                return redirect()->back();
            }

            // dd($newBal); 
        }
        else {
            Session::flash('error', 'Top Up your wallet and try again!');
            return redirect()->back();
        }
    }

    public function repayLoan2(Request $request){
        $user = Auth::user();
        $loan_id = $request->loan_id;
        $amount = $request->amount;
        $today = Carbon::now()->toDateString();

        $loan = DB::table('loans')
                ->join('loan_details', 'loan_details.loan_id', '=', 'loans.id')
                ->where('loans.id', $loan_id)
                ->select('loan_details.*', 'loans.*')
                ->first();

        $loanAmount = $loan->amount_payable;
        $pendingBalance = $loan->balance;
        $monthlyCharge = $loan->charge_per_installment;

        $noOfPayments = $amount/$monthlyCharge;

        $walletBalance = floatval(DB::table('wallets')->where('user_id', $user->id)->first()->balance);

        if($walletBalance < $amount){
            Session::flash('error', 'Top Up your wallet and try again!');
            return redirect()->back();
        }
        elseif($amount > $pendingBalance){
            Session::flash('error', "Repayment amount is too large for this loan!");
            return redirect()->back();
        }
        elseif($amount % $monthlyCharge == 0 || $amount == $pendingBalance){            
            $loanSchedule = unserialize($loan->loan_schedule);
            $deductions = 0.0;
            $payment_frequency = $loan->interest_charge_frequency;

            if($loan->org_id == null){
                if($noOfPayments < 1) $noOfPayments = ceil($noOfPayments);

                $totalPayments = 0;
                for($i=1; $i<=$noOfPayments; $i++){
                    foreach($loanSchedule as $index=>$schedule){
                        if($schedule['status'] == 0){
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
                
                //Update Loan Details
                $loanStatus = end($loanSchedule)['status'] == 1 ? 3 : 1;

                if($loanStatus == 3){
                    DB::table('loans')->where('id', $loan_id)->update([
                        'status' => $loanStatus
                    ]);
                }
                
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

                $lender_wallet_balance = floatval(DB::table('wallets')->where('user_id', $loan->user_id)->first()->balance);
                $newBal = $lender_wallet_balance + $deductions;
                

                //Initiate Credit Transaction.
                $transaction = new Transaction();
                $transaction->user_id = $loan->user_id;
                $transaction->txn_code = strtoupper(Str::random(10));
                $transaction->txn_type = 1;
                $transaction->transaction_type_id = DB::table('transaction_types')->where('name', 'loan_repayment')->value('id');
                $transaction->amount = $deductions;
                $transaction->save();
                
                //Credit Lender
                DB::table('wallets')->where('user_id', $loan->user_id)->update(['balance' => $newBal]);

                // Serialize and update schedule
                DB::table('loan_details')->where('loan_id', $loan_id)->update([
                    'balance' => $pendingBalance,
                    'loan_schedule' => serialize($loanSchedule)
                ]);
                
                Session::flash('success', 'Loan repayment was successful!');
                return redirect()->back();
            }
            else{
                Session::flash('error', 'Loan repayment was unsuccessful! Try Again!');
                return redirect()->back();
            }
        }
        else{
            Session::flash('error', "Value of amount must be a multiple of $monthlyCharge!");
            return redirect()->back();
        }

    }

    public function repayLoan3(Request $request){
        $user = Auth::user();
        $loan_id = $request->loan_id;
        $noOfPayments = $request->repayment_count;
        $amount = $this->getRepaymentAmount($noOfPayments, $loan_id);
        $today = Carbon::now()->toDateString();

        $loan = DB::table('loans')
                ->join('loan_details', 'loan_details.loan_id', '=', 'loans.id')
                ->where('loans.id', $loan_id)
                ->select('loan_details.*', 'loans.*')
                ->first();

        $loanAmount = $loan->amount_payable;
        $pendingBalance = $loan->balance;
        // $monthlyCharge = $loan->charge_per_installment;

        $walletBalance = floatval(DB::table('wallets')->where('user_id', $user->id)->first()->balance);

        if($walletBalance < $amount){
            Session::flash('error', 'Your wallet balance is too low. Top Up your wallet and try again!');
            return redirect()->back();
        }
        //this second condition may not be necessary
        elseif($amount > $pendingBalance){
            Session::flash('error', "Repayment amount is too large for this loan!");
            return redirect()->back();
        }
        else{            
            $loanSchedule = unserialize($loan->loan_schedule);
            $deductions = 0.0;
            $payment_frequency = $loan->interest_charge_frequency;

            if($loan->org_id == null){
                if($noOfPayments < 1) $noOfPayments = ceil($noOfPayments);

                $totalPayments = 0;
                for($i=1; $i<=$noOfPayments; $i++){
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

                $lender_wallet_balance = floatval(DB::table('wallets')->where('user_id', $loan->user_id)->first()->balance);
                $newBal = $lender_wallet_balance + $deductions;
                

                //Initiate Credit Transaction.
                $transaction = new Transaction();
                $transaction->user_id = $loan->user_id;
                $transaction->txn_code = strtoupper(Str::random(10));
                $transaction->txn_type = 1;
                $transaction->transaction_type_id = DB::table('transaction_types')->where('name', 'loan_repayment')->value('id');
                $transaction->amount = $deductions;
                $transaction->save();
                
                //Credit Lender
                DB::table('wallets')->where('user_id', $loan->user_id)->update(['balance' => $newBal]);

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
                
                Session::flash('success', $message);
                return redirect()->back();
            }
            else{
                Session::flash('error', 'Loan repayment was unsuccessful! Try Again!');
                return redirect()->back();
            }
        }

    }

    public function getRepaymentAmount($count, $loan_id){
        $schedules = $this->scheduler($loan_id)['schedule'];
        
        $total = 0;
        for($i=1; $i<=$count; $i++){
            foreach($schedules as $schedule){
                if($schedule['status'] == 0) {
                    $total += $schedule['amount'];
                    break;
                }
            }
        }

        return $total;
    }
}
