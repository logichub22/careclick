<?php

namespace App\Http\Controllers\Back\Trainer;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\General\Loan;
use App\Models\General\LoanDetail;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Models\General\LoanPackage;
use Illuminate\Support\Facades\Validator;
use App\Services\Loan\OrgLoanService;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use App\Models\General\Wallet;
use App\Models\Organization\Organization;
use App\Models\Organization\OrganizationWallet;
use App\Mail\LoanBorrowedEmail;
use App\Mail\NotifyLenderEmail;
use Illuminate\Support\Facades\Mail;
use Carbon\Carbon;
use Carbon\CarbonInterval;
use App\Models\General\Transaction;
use App\User;

class OrgLoanController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user = Auth::user();

        $organization = Organization::where('admin_id', $user->id)->first();

        $loans = Loan::where('org_id', $organization->id)->get();

        return view('back/federation/loan/organization/index', compact('loans'));
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
        $organization = Organization::where('admin_id', $user->id)->first();
        $serviceFee = 1000;

        $score = $this->creditScore();
        $mypackage = LoanPackage::where('id', $request->package_id)->first();

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
            $loan->org_id = $request->organization;
            $loan->loan_package_id = $request->package_id;
            $loan->amount = $request->amount;
            $loan->borrower_credit_score = $score;
            $loan->status = 1;
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
            } else {
                $nextPayment = $today->addMonth(1);
                $payDate = $nextPayment->toDateString();
            }


        // Store into Loan Details Table
            $detail = new LoanDetail();
            $detail->loan_id = $loan->id;
            $detail->package_name = $request->package_name;
            $detail->principal_due = $loan->amount;
        // $detail->principal_balance = $loan->amount;
            $detail->interest_due = $interest;
            $detail->balance = $initialPayable;
            $detail->amount_payable = $initialPayable;
            $detail->charge_per_installment = $installmentAmount;
            $detail->no_of_installments = $lengthOfLoan;
            $detail->interest_charge_frequency = $paymentFrequency;
            $detail->payback_date = $date;
            $detail->next_payment_date = $payDate;
            $detail->save();

            $bl = DB::table('org_wallets')->where('org_id', $organization->id)->pluck('balance')->toArray();
            $bal = implode($bl);
            $balance = floatval($bal);
            $newBalance = $balance + $principal;

            $package = LoanPackage::where('id', $request->package_id)->first();

            // Debit Lender (Owner of package) => Cash has reduced but he expects more money
            $transaction = new Transaction();
            if(is_null($package->org_id)) { // if a user owns the package
                $transaction->user_id = $package->user_id;
            } else {
                $transaction->org_id = $package->org_id;
            }
            $transaction->txn_code = strtoupper(Str::random(10));
            $transaction->txn_type = 2;
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
        } else {
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
        $transaction->org_id = $organization->id;
        $transaction->txn_code = strtoupper(Str::random(10));
        $transaction->txn_type = 1;
        $transaction->amount = $detail->principal_due;
        $transaction->save();

            // Deposit into wallet and make necessary deductions
            DB::table('org_wallets')->where('org_id', $organization->id)->update(['balance' => $newBalance]);

            // $date = $this->calcPaymentDate($request);

            $maildata = [
                'principal' => $request->amount,
                'interest' => $request->interest_rate,
                'interest_due' => $interest,
                'package' => $package->name,
                'date' => $date,
                'loanName' => $request->loan_title,
                'name' => $request->name,
                'installments' => $request->length_of_loan,
                'interest_charge_frequency' => $request->payment_frequency,
                'total' => $initialPayable,
            ];
        
            // Send mail to borrower
            Mail::to($user->email)->send(new LoanBorrowedEmail($maildata));

            // Send mail to lender
            Mail::to($lender->email)->send(new NotifyLenderEmail($maildata));

            Session::flash('success', 'Your wallet has been deposited with' . ' ' . number_format($loan->amount));
            return redirect()->route('org-loans.show', $loan->id);
        }

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

        $organization = Organization::where('admin_id', $user->id)->first();

        $detail = DB::table('loans')
                  ->join('loan_details', 'loan_details.loan_id', '=', 'loans.id')
                  ->select('loan_details.*')
                  ->where(['loan_details.loan_id' => $loan->id])
                  ->first();

        //dd($detail);

        return view('back/federation/loan/organization/loan-details', compact('loan', 'detail'));
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
        $organization = Organization::where('admin_id', $user->id)->first();
        $packages = DB::table('loan_packages')
            ->where('status', true)
            ->where(function ($query) {
                $user = Auth::user();
                $organization = Organization::where('admin_id', $user->id)->first();
                $query->where('org_id', '!=', $organization->id);
            })->orWhere('user_id', '!=', $user->id)->orderBy('loan_packages.created_at')->get();

        // if ($request->has('repayment_plan') && $request->has('min_interest') && $request->has('max_interest') && $request->has('insured') && $request->has('max_amount') && $request->has('min_amount')) {
        //     $packages = DB::table('loan_packages')->where([
        //         ['interest_rate', '>=', $request->min_interest],
        //         ['interest_rate', '<=', $request->max_interest],
        //         ['min_amount', '>=', $request->min_amount],
        //         ['max_amount', '<=', $request->max_interest],
        //         ['insured', $request->insured],
        //         ['repayment_plan', $request->repayment_plan],
        //         ['status', true]
        //     ])->get();
        //     //dd($packages);
        // }


        return view('back/federation/loan/organization/browse-loans', compact('packages'));
    }

    public function getBorrowLoan(Request $request, $id)
    {
        $user = Auth::user(); 

        $organization = Organization::where('admin_id', $user->id)->first();

        $package = LoanPackage::findOrFail($id);
        $walletBalance = OrganizationWallet::where('org_id', $organization->id)->first();
        return view('back/federation/loan/organization/borrow', compact('package', 'walletBalance', 'organization'));
    }

    public function filterLoans(Request $request)
    {
        if ($request->ajax()) {
            $packages = DB::table('loan_packages')->get();
        }

        if($packages) {
            foreach ($packages as $key => $package) {
                $data = '<tr>' .
                '<td>' .$package->name.'</td>'.
                '<td>' .$package->min_amount.'</td>'.
                '<td>' .$package->max_amount.'</td>'.
                '<td>' .$package->repayment_plan.'</td>'.
                '<td>' .$package->interest_rate.'</td>'.
                '<td>' .'<a href="" class="btn btn-sm btn-primary">Borrow</a>'.'</td>'.
              '</tr>' ;
            }
        }

        return response()->json($data);
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
        $score = $this->creditScore();

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

        $organization = Organization::where('admin_id', $user->id)->first();

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
        $balance = OrganizationWallet::where('org_id', $organization->id)->pluck('balance')->toArray();
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
        ];

        return $data;

    }
}
