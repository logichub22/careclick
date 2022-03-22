<?php

namespace App\Http\Controllers\Back\Individual;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\General\LoanPackage;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB;
use App\Models\General\Currency;
use App\Models\General\Loan;
use App\Http\Requests\LoanPackageRequest;
use App\Models\General\Wallet;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Client;
use App\Jobs\PushPackageToRiby;
use App\Models\General\InterestModel;

class UserPackageController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user = Auth::user();

        $packages = LoanPackage::where('user_id', $user->id)->get();
        return view('back/individual/package/index', compact('packages', 'user'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $user = Auth::user();
        $currencies = DB::table('currencies')->where('country_id', '!=', null)->get();
        $walletBalance = Wallet::where('user_id', $user->id)->first();
        $models = InterestModel::where('status', true)->get();

        $x = LoanPackage::all()->sortBy('created_at');
        // dd($x);

        $months = $x->groupBy(function ($result, $key){
        return $result->created_at->format('M');
      })->map(function ($result) {
        $user = Auth::user();
        return ($result->where('user_id', $user->id)->count());
      });

      // dd($months);
      //Here I aam trying to select the interest rates from loan_packages table where at least one loan has been taken from the package then group them by months and display on the chart 
        $interest_rates = Loan::all()->sortBy('created_at')->groupBy(function($result, $key) {
        return $result->created_at->format('M');
      })->map(function($result) {
        return ($result->where('loan_package_id', '!=', null)->count());
      });
      // dd($interest_rates);


      //Here I aam trying to select the credit scores from loan_packages table where at least one loan has been taken from the loan package then group them by months and display on the chart 
      $credit_scores = DB::table('loans')
                      ->join('loan_packages', 'loan_packages.id', '=', 'loans.loan_package_id')
                      ->select('loan_packages.min_credit_score')
                      ->get();

        // dd($credit_scores);

        return view('back/individual/package/create', compact('currencies', 'walletBalance', 'models', 'user', 'interest_rates', 'credit_scores', 'months'));
      }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(LoanPackageRequest $request)
    {
        $user = Auth::user();

        $walletBalance = Wallet::where('user_id', $user->id)->first();

        $package = new LoanPackage();
        $package->user_id = $user->id;
        $package->name = $request->name;
        $package->repayment_plan = $request->repayment_plan;
        $package->min_credit_score = $request->min_score;
        // $package->max_credit_score = $request->max_score;
        $package->insured = $request->insured;
        $package->min_amount = $request->min_amount;
        $package->max_amount = $request->max_amount;
        $package->currency = $request->currency;
        $package->interest_rate = $request->interest;
        $package->description = $request->description;

        // Check if users wallet has minimum amount
        if($request->min_amount > $walletBalance->balance || $request->max_amount > $walletBalance->balance) {
            Session::flash('error', 'Please ensure the minimum and maximum amount fall between your wallet balance (' . $walletBalance->balance . ')');
            return redirect()->back();
        } else {
            $package->save();
            $hasInsurance = $package->insured;

            $data = [
                'package_name' => $package->name,
                'interest_rate' => $package->interest_rate,
                'min_amount' => $package->min_amount,
                'max_amount' => $package->max_amount,
                'repayment_frequency' => $package->repayment_plan,
                'description' => $package->description,
                'currency' => $package->currency,
                'administrative_fee_rate' => 1000,
                'owner_id' => $user->id,
                'owner_type' => 'COOPERATIVE',
                "start_date" => '30-06-2018',
		        'end_date' => '30-12-2018',
                'owner_name' => $user->name . ' ' . $user->other_names,
                'description' => $package->description,
                'min_approval' => $package->min_credit_score,
            ];

           //Push Loan Offer to Riby
           PushPackageToRiby::dispatch($data);

            //Proceed with insurance if the loan is insured
            switch ($hasInsurance) {
                case true:
                    Session::flash('success', $package->name . ' has been created successfully. Kindly proceed with insuring it below');
                    // return redirect()->route('user-packages.getInsurance', $package->id); Please provide logic to get only insured packages
                    return redirect()->route('user-packages.show', $package->id);
                    break;
                case false:
                    Session::flash('success', $package->name . ' has been created successfully for your organization');
                    return redirect()->route('user-packages.show', $package->id);
                    break;
            }
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
        $user = Auth::user();
        $package = LoanPackage::findOrFail($id);
        $walletBalance = Wallet::where('user_id', $user->id)->first();

        $datas = DB::table('users')
                ->join('loans', 'loans.user_id', '=', 'users.id')
                ->select('users.name', 'users.other_names', 'loans.amount', 'loans.status', 'loans.id')
                ->where(['loans.loan_package_id' => $package->id])
                ->get();

        return view('back/individual/package/show', compact('package', 'datas', 'user','walletBalance'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
      $user = Auth::user();
      $package = LoanPackage::findOrFail($id);
      $walletBalance = Wallet::where('user_id', $user->id)->first();
      $currencies = Currency::all();

      $datas = DB::table('users')
              ->join('loans', 'loans.user_id', '=', 'users.id')
              ->select('users.name', 'users.other_names', 'loans.amount', 'loans.status', 'loans.id')
              ->where(['loans.loan_package_id' => $package->id])
              ->get();

      return view('back/individual/package/edit', compact('package', 'datas', 'user','walletBalance', 'currencies'));
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
      $user = Auth::user();

      $walletBalance = Wallet::where('user_id', $user->id)->first();

      $package = new LoanPackage();
      $package->user_id = $user->id;
      $package->name = $request->name;
      $package->repayment_plan = $request->repayment_plan;
      $package->min_credit_score = $request->min_score;
      $package->max_credit_score = $request->max_score;
      $package->insured = $request->insured;
      $package->min_amount = $request->min_amount;
      $package->max_amount = $request->max_amount;
      $package->currency = $request->currency;
      $package->interest_rate = $request->interest;
      $package->description = $request->description;

      // Check if users wallet has minimum amount
      if($request->min_amount > $walletBalance->balance || $request->max_amount > $walletBalance->balance) {
          Session::flash('error', 'Please ensure the minimum and maximum amount fall between your wallet balance (' . $walletBalance->balance . ')');
          return redirect()->back();
      } else {
          $package->save();
          $hasInsurance = $package->insured;

          $data = [
              'package_name' => $package->name,
              'interest_rate' => $package->interest_rate,
              'min_amount' => $package->min_amount,
              'max_amount' => $package->max_amount,
              'repayment_frequency' => $package->repayment_plan,
              'description' => $package->description,
              'currency' => $package->currency,
              'administrative_fee_rate' => 1000,
              'owner_id' => $user->id,
              'owner_type' => 'COOPERATIVE',
              "start_date" => '30-06-2020',
          'end_date' => '30-12-2020',
              'owner_name' => $user->name . ' ' . $user->other_names,
              'description' => $package->description,
              'min_approval' => $package->min_credit_score,
          ];

         //Push Loan Offer to Riby (Revisit)
         PushPackageToRiby::dispatch($data);

          //Proceed with insurance if the loan is insured
          switch ($hasInsurance) {
              case true:
                  Session::flash('success', $package->name . ' has been created successfully. Kindly proceed with insuring it below');
                  // return redirect()->route('user-packages.getInsurance', $package->id); Please provide logic to get only insured packages
                  return redirect()->route('user-packages.show', $package->id);
                  break;
              case false:
                  Session::flash('success', $package->name . ' has been created successfully for your organization');
                  return redirect()->route('user-packages.show', $package->id);
                  break;
          }
      }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $user = Auth::user();

        if(Loan::where('loan_package_id', $id)->count() > 0) {
          Session::flash('success', 'Package cannot be deleted because it has active subscribers.');
          return redirect()->route('user-packages.index');
        }
        else {
          $packages = LoanPackage::destroy($id);
          Session::flash('success', 'Package has been deleted successfully');
          return redirect()->route('user-packages.index');
        }
    }

    public function getInsureLoan(Request $request, $id)
    {
        $user = Auth::user();
        $package = LoanPackage::findOrFail($id);

        // Get all insurance providers and their packages

        return view('back/individual/package/insure-package', compact('package', 'user'));
    }

    public function getBorrowerDetail($id)
    {
        $loan = Loan::findOrFail($id);

        $detail = DB::table('loan_details')->where('loan_id', $loan->id)->first();

        $user = DB::table('users')
              ->join('user_details', 'user_details.user_id', '=', 'users.id')
              ->where('users.id', $loan->user_id)
              ->first();
        return view('back/user/package/borrower', compact('loan', 'detail', 'user'));
    }

    public function loanRequests()
    {
        $user = Auth::user();
        $borrower_id = DB::table('loans')
                    ->join('users', 'users.id', '=', 'loans.user_id')
                    ->join('loan_packages', 'loan_packages.id', '=', 'loans.loan_package_id')
                    ->select('users.identification_document')
                    ->where('loan_packages.org_id', $user->id)
                    ->first();

        $loan_requests = DB::table('loans')
                ->join('users', 'users.id', '=', 'loans.user_id')
                ->join('loan_packages', 'loan_packages.id', '=', 'loans.loan_package_id')
                ->select('users.name', 'users.email', 'loans.amount', 'loans.borrower_credit_score', 'loans.status', 'loans.created_at', 'loan_packages.name as packageName', 'loan_packages.min_credit_score', 'loan_packages.interest_rate', 'loan_packages.insured', 'loan_packages.repayment_plan', 'loans.id', 'loans.loan_package_id')
                ->where('loan_packages.user_id', $user->id)
                ->where('loans.status', '=', '0')
                ->get();

        $url = '/public/documents/ids/';

        if($borrower_id != null){
          $path = $url . $borrower_id->identification_document;
        }
        else{
          $path = "ID not found.";
        }

        // dd($borrower_id);
        // dd($path);

        return view('back/individual/package/requests', compact('user', 'loan_requests', 'path'));
    }

    public function pushRiby(Request $request)
    {
        $client = new \GuzzleHttp\Client;

        $access_token = 'fe7f0f4f1a0843ea56a8f7a90d8a482ef6c26dee';

        $response = $client->requestAsync();
    }
}
