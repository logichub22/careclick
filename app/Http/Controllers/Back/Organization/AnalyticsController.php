<?php

namespace App\Http\Controllers\Back\Organization;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Services\Report\ReportService;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\Organization\Organization;
use App\Models\General\Loan;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\LoanDetailExport;
use Carbon\Carbon;
use App\User;
use PdfReport;
use ExcelReport;
use JavaScript;

class AnalyticsController extends Controller
{
  public function collectionsChart()
  {
    $user = Auth::user();

    $x = Loan::all()->sortBy('created_at');

    $loans = DB::table('loans')
            ->where('org_id', $user->id)
            ->select('*')
            ->orderBy('created_at', 'desc')
            ->get();

    $months = $x->groupBy(function ($result, $key){
      return $result->created_at->format('M');
    })->map(function ($result) {
      $user = Auth::user();
      return ($result->where('org_id', $user->id)->count());
    });

    $loan_pending   = Loan::all()->sortBy('created_at')->groupBy(function($result, $key) {
      return $result->created_at->format('M');
    })->map(function($result) {
      $user = Auth::user();
      return ($result->where('org_id', $user->id)->where('status', '0')->count());
    });

    $loan_paid   = Loan::all()->sortBy('created_at')->groupBy(function($result, $key) {
      return $result->created_at->format('M');
    })->map(function($result) {
      $user = Auth::user();
      return ($result->where('org_id', $user->id)->where('status', '3')->count());
    });

    $loan_defaulted   = Loan::all()->sortBy('created_at')->groupBy(function($result, $key) {
      return $result->created_at->format('M');
    })->map(function($result) {
      $user = Auth::user();
      return ($result->where('org_id', $user->id)->where('status', '4')->count());
    });

     // dd($loans);

   return view('back/organization/analytics/loancollections', compact('loan_pending','loan_paid','loan_defaulted', 'user', 'months', 'loans'));
  }

  public function maturityChart() {
    $user = Auth::user();
    //Loan Maturity refers to the date on which a borrower's final loan payment is due. How do we represent that on the chart.

    $loan_pending   = Loan::orderBy('created_at', 'desc')->where('user_id', $user->id)->where('status', '1')->take(5)->get();
    $loan_paid      = Loan::orderBy('created_at', 'desc')->where('user_id', $user->id)->where('status', '1')->take(5)->get();
    $loan_defaulted = Loan::orderBy('created_at', 'desc')->where('user_id', $user->id)->Where('status', '1')->take(5)->get();

    return view('back/organization/analytics/loanmaturity', compact('loan_pending','loan_paid','loan_defaulted', 'user'));
  }

  public function releasedChart() {
    $user = Auth::user();

    $x = Loan::all()->sortBy('created_at');

    $months = $x->groupBy(function ($result, $key){
      return $result->created_at->format('M');
    })->map(function ($result) {
      $user = Auth::user();
      return ($result->where('org_id', $user->id)->count());
    });

    $loan_released   = Loan::all()->sortBy('created_at')->groupBy(function($result, $key) {
      return $result->created_at->format('M');
    })->map(function($result) {
      $user = Auth::user();
      return ($result->where('org_id', $user->id)->where('status', '1')->count());
    });

    $loan_unreleased  = Loan::all()->sortBy('created_at')->groupBy(function($result, $key) {
      return $result->created_at->format('M');
    })->map(function($result) {
      $user = Auth::user();
      return ($result->where('org_id', $user->id)->where('status', '2')->count());
    });

    $loans = DB::table('loans')
            ->join('loan_packages', 'loan_packages.id', '=', 'loans.loan_package_id')
            ->select('loans.loan_title', 'loans.amount', 'loans.status', 'loans.created_at', 'loans.currency')
            ->where('loan_packages.org_id', $user->id)
            ->get();
            // dd($loans);

    return view('back/organization/analytics/loanreleased', compact('loan_released','loan_unreleased', 'user', 'months', 'loans' ));
  }

  public function genderChart() {
    $user = Auth::user();

    $total = DB::table('user_details')->where('org_id', $user->id)->count();

    $male = DB::table('user_details')->where('org_id', $user->id)
            ->where('gender', '1')
            ->count();

    $female = DB::table('user_details')->where('org_id', $user->id)
            ->where('gender', '2')
            ->count();

    $not_specified = DB::table('user_details')->where('org_id', $user->id)
            ->where('gender', 'null')
            ->count();
    if( $total > 0) {
      $male_percentage = ($male/$total) * 100;
      $female_percentage = ($female/$total) * 100;
      $not_specified_percentage = ($not_specified/$total) * 100;
    }
    else{
      $male_percentage = 0;
      $female_percentage = 0;
      $not_specified_percentage = 0;
    }

    // dd($female_percentage);

    return view('back/organization/analytics/gender', compact('user', 'female_percentage', 'male_percentage', 'not_specified_percentage'));
  }

  public function balanceChart() {
    $user = Auth::user();

    $loan_pending   = Loan::orderBy('created_at', 'desc')->where('user_id', $user->id)->where('status', '1')->take(5)->get();
    $loan_paid      = Loan::orderBy('created_at', 'desc')->where('user_id', $user->id)->where('status', '1')->take(5)->get();
    $loan_defaulted = Loan::orderBy('created_at', 'desc')->where('user_id', $user->id)->Where('status', '1')->take(5)->get();

    return view('back/organization/analytics/balance', compact('loan_pending','loan_paid','loan_defaulted', 'user'));
  }

  public function averageLoanTenureChart() {
    $user = Auth::user();

    $loan_pending   = Loan::orderBy('created_at', 'desc')->where('user_id', $user->id)->where('status', '1')->take(5)->get();
    $loan_paid      = Loan::orderBy('created_at', 'desc')->where('user_id', $user->id)->where('status', '1')->take(5)->get();
    $loan_defaulted = Loan::orderBy('created_at', 'desc')->where('user_id', $user->id)->Where('status', '1')->take(5)->get();

   return view('back/organization/analytics/averageloantenure', compact('loan_pending','loan_paid','loan_defaulted', 'user'));
  }

  public function savingsChart() {
    $user = Auth::user();

    $total_savings = DB::table('transactions')
            ->where('org_id', $user->id)
            ->where('transaction_type_id', '=', '9')
            ->count();

    $credit = DB::table('transactions')
            ->where('org_id', $user->id)
            ->where('transaction_type_id', '=', '9')
            ->where('txn_type', '=', '1')
            ->count();

    $debit = DB::table('transactions')
            ->where('org_id', $user->id)
            ->where('transaction_type_id', '=', '9')
            ->where('txn_type', '=', '2')
            ->count();

            // dd($debit);

    if( $total_savings > 0) {
      $savings_percentage = ($credit/$total_savings) * 100;
      $withdrawal_percentage = ($debit/$total_savings) * 100;
    }
    else{
      $savings_percentage = 0;
      $withdrawal_percentage = 0;
    }

    return view('back/organization/analytics/savings', compact('savings_percentage','withdrawal_percentage', 'user'));
  }


 /**REPORTING FUCTIONS**/


  public function reportWallet()
  {
    $user = Auth::user();
   return view('back/organization/analytics/report_creditdebit', compact('user'));
  }

  public function export(Request $request)
  {
    $admin = Auth::user()->id;
    // dd($admin);

    $resource_type = $request->resource_type;
    $title = $request->title;
    $from_date = $request->from_date;
    $to_date = $request->to_date;
    $sort_by = $request->sort_by;
    $filename = $request->file_name;
    $type_criteria =  $request->type_criteria;
    $type = $request->type;

    $currency =  DB::table('organization_details')
                    ->join('currencies', 'country_id', '=', 'organization_details.country')
                    ->where('organization_details.org_id', '=', $admin)
                    ->select('currencies.prefix')
                    ->first();

    // dd($currency);

    //Generate User's Reports
    if ($resource_type == 'user' && $type_criteria == 'all') {
        $users =DB::table('users')
                ->join('user_details', 'user_details.user_id', '=', 'users.id')
                ->join('organizations', 'organizations.id', '=', 'user_details.org_id')
                ->select('users.name', 'users.other_names', 'users.email', 'users.msisdn', 'user_details.dob')
                ->where(array("organizations.admin_id" => $admin))
                ->where('user_details.created_at', '>=', $from_date)
                ->where('user_details.created_at', '<=', $to_date)
                ->where('deleted_at', NULL)
                ->get();
                
        $count = count($users);
        if ($count > 0) {
          $users = json_decode(json_encode($users), true);

          // dd($users);

          $columns = array("First Name", "Last Name", "Email", "Phone Number", "DOB");
          
          $callback = function() use ($users, $columns)
          {
              $file = fopen('php://output', 'w');
              fputcsv($file, $columns);

              foreach($users as $user) {
                  fputcsv($file, array($user["name"], $user["other_names"], $user["email"], $user["msisdn"], $user["dob"]));
              }
              fclose($file);
          };
          
          $headers = array(
              "Content-type" => "text/csv",
              "Content-Disposition" => "attachment; filename=$filename.csv",
              "Pragma" => "no-cache",
              "Cache-Control" => "must-revalidate, post-check=0, pre-check=0",
              "Expires" => "0"
          );

          return \Response::stream($callback, 200, $headers); 
        }
        else {
          Session::flash('error', 'You do not have any  users added within the selected period.');
          return redirect()->back();
        }
    }
    elseif ($resource_type == 'user' && $type_criteria ==  'verified') {
       $users = DB::table('users')
                ->join('user_details', 'user_details.user_id', '=', 'users.id')
                ->join('organizations', 'organizations.id', '=', 'user_details.org_id')
                ->select('users.name', 'users.other_names', 'users.email', 'users.msisdn', 'user_details.dob', 'users.verified')
                ->where(array("organizations.admin_id" => $admin))
                ->where('user_details.created_at', '>=', $from_date)
                ->where('user_details.created_at', '<=', $to_date)
                ->where('users.verified', '=', 1)
                ->where('deleted_at', NULL)
                ->get();
        $count = count($users);
        if ($count > 0) {
          $users = json_decode(json_encode($users), true);

          // dd($users);

          $columns = array("First Name", "Last Name", "Email", "Phone Number", "DOB", "Verified");
          
          $callback = function() use ($users, $columns)
          {
              $file = fopen('php://output', 'w');
              fputcsv($file, $columns);

              foreach($users as $user) {
                  fputcsv($file, array($user["name"], $user["other_names"], $user["email"], $user["msisdn"], $user["dob"], $user["verified"]));
              }
              fclose($file);
          };
          
          $headers = array(
              "Content-type" => "text/csv",
              "Content-Disposition" => "attachment; filename=$filename.csv",
              "Pragma" => "no-cache",
              "Cache-Control" => "must-revalidate, post-check=0, pre-check=0",
              "Expires" => "0"
          );

          return \Response::stream($callback, 200, $headers); 
        }
        else {
          Session::flash('error', 'You do not have any  users added within the selected period.');
          return redirect()->back();
        }
    }
    elseif ($resource_type == 'user' && $type_criteria ==  'unverified') {
       $users = DB::table('users')
                ->join('user_details', 'user_details.user_id', '=', 'users.id')
                ->join('organizations', 'organizations.id', '=', 'user_details.org_id')
                ->select('users.name', 'users.other_names', 'users.email', 'users.msisdn', 'user_details.dob', 'users.verified')
                ->where(array("organizations.admin_id" => $admin))
                ->where('user_details.created_at', '>=', $from_date)
                ->where('user_details.created_at', '<=', $to_date)
                ->where('users.verified', '=', 0)
                ->where('deleted_at', NULL)
                ->get();
        $count = count($users);
        if ($count > 0) {
          $users = json_decode(json_encode($users), true);

          // dd($users);

          $columns = array("First Name", "Last Name", "Email", "Phone Number", "DOB", "Verified");
          
          $callback = function() use ($users, $columns)
          {
              $file = fopen('php://output', 'w');
              fputcsv($file, $columns);

              foreach($users as $user) {
                  fputcsv($file, array($user["name"], $user["other_names"], $user["email"], $user["msisdn"], $user["dob"], $user["verified"]));
              }
              fclose($file);
          };
          
          $headers = array(
              "Content-type" => "text/csv",
              "Content-Disposition" => "attachment; filename=$filename.csv",
              "Pragma" => "no-cache",
              "Cache-Control" => "must-revalidate, post-check=0, pre-check=0",
              "Expires" => "0"
          );

          return \Response::stream($callback, 200, $headers); 
        }
        else {
          Session::flash('error', 'You do not have any  users added within the selected period.');
          return redirect()->back();
        }
    }
    elseif ($resource_type == 'user' && $type_criteria ==  'active') {
       $users = DB::table('users')
                ->join('user_details', 'user_details.user_id', '=', 'users.id')
                ->join('organizations', 'organizations.id', '=', 'user_details.org_id')
                ->select('users.name', 'users.other_names', 'users.email', 'users.msisdn', 'user_details.dob', 'users.status')
                ->where(array("organizations.admin_id" => $admin))
                ->where('user_details.created_at', '>=', $from_date)
                ->where('user_details.created_at', '<=', $to_date)
                ->where('users.status', '=', 1)
                ->where('deleted_at', NULL)
                ->get();
        $count = count($users);
        if ($count > 0) {
          $users = json_decode(json_encode($users), true);

          // dd($users);

          $columns = array("First Name", "Last Name", "Email", "Phone Number", "DOB", "Active Status");
          
          $callback = function() use ($users, $columns)
          {
              $file = fopen('php://output', 'w');
              fputcsv($file, $columns);

              foreach($users as $user) {
                  fputcsv($file, array($user["name"], $user["other_names"], $user["email"], $user["msisdn"], $user["dob"], $user["status"]));
              }
              fclose($file);
          };
          
          $headers = array(
              "Content-type" => "text/csv",
              "Content-Disposition" => "attachment; filename=$filename.csv",
              "Pragma" => "no-cache",
              "Cache-Control" => "must-revalidate, post-check=0, pre-check=0",
              "Expires" => "0"
          );

          return \Response::stream($callback, 200, $headers); 
        }
        else {
          Session::flash('error', 'You do not have any  users added within the selected period.');
          return redirect()->back();
        }
    }
    elseif ($resource_type == 'user' && $type_criteria ==  'inactive') {
       $users = DB::table('users')
                ->join('user_details', 'user_details.user_id', '=', 'users.id')
                ->join('organizations', 'organizations.id', '=', 'user_details.org_id')
                ->select('users.name', 'users.other_names', 'users.email', 'users.msisdn', 'user_details.dob', 'users.status')
                ->where(array("organizations.admin_id" => $admin))
                ->where('user_details.created_at', '>=', $from_date)
                ->where('user_details.created_at', '<=', $to_date)
                ->where('users.status', '=', 0)
                ->where('deleted_at', NULL)
                ->get();

        $count = count($users);
        if ($count > 0) {
          $users = json_decode(json_encode($users), true);

          // dd($users);

          $columns = array("First Name", "Last Name", "Email", "Phone Number", "DOB", "Active Status");
          
          $callback = function() use ($users, $columns)
          {
              $file = fopen('php://output', 'w');
              fputcsv($file, $columns);

              foreach($users as $user) {
                  fputcsv($file, array($user["name"], $user["other_names"], $user["email"], $user["msisdn"], $user["dob"], $user["status"]));
              }
              fclose($file);
          };
          
          $headers = array(
              "Content-type" => "text/csv",
              "Content-Disposition" => "attachment; filename=$filename.csv",
              "Pragma" => "no-cache",
              "Cache-Control" => "must-revalidate, post-check=0, pre-check=0",
              "Expires" => "0"
          );

          return \Response::stream($callback, 200, $headers); 
        }
        else {
          Session::flash('error', 'You do not have any  users added within the selected period.');
          return redirect()->back();
        }
    }

    //Generate Loan Reports
    if ($resource_type == 'loan' && $type_criteria == 'all') {
        $loans = DB::table('loans')
                ->join('loan_details', 'loan_details.loan_id', '=', 'loans.id')
                ->join('loan_packages', 'loan_packages.id', '=', 'loans.loan_package_id')
                ->select('loans.loan_title', 'loans.amount', 'loans.currency', 'loans.created_at')
                ->where('loan_details.created_at', '>=', $from_date)
                 ->where('loan_details.created_at', '<=', $to_date)
                ->get();

        $count = count($loans);

        if ($count > 0) {
          $loans = json_decode(json_encode($loans), true);

          // dd($loans);

          $columns = array("Loan Title", "Amount", "Currency", "Status", "Date");
          
          $callback = function() use ($loans, $columns)
          {
              $file = fopen('php://output', 'w');
              fputcsv($file, $columns);

              foreach($loans as $loan) {
                  fputcsv($file, array($loan["loan_title"], $loan["amount"], $loan["currency"], $loan["status"], $loan["created_at"]));
              }
              fclose($file);
          };
          
          $headers = array(
              "Content-type" => "text/csv",
              "Content-Disposition" => "attachment; filename=$filename.csv",
              "Pragma" => "no-cache",
              "Cache-Control" => "must-revalidate, post-check=0, pre-check=0",
              "Expires" => "0"
          );

          return \Response::stream($callback, 200, $headers); 
        }
        else {
          Session::flash('error', 'You have not made any transactions within the selected period.');
          return redirect()->back();
        }
    }
    elseif ($resource_type == 'loan' && $type_criteria == 'paid') {
        $loans = DB::table('loans')
                ->join('loan_details', 'loan_details.loan_id', '=', 'loans.id')
                ->join('loan_packages', 'loan_packages.id', '=', 'loans.loan_package_id')
                ->select('loans.loan_title', 'loans.amount', 'loans.currency', 'loans.status', 'loans.created_at')
                ->where('loan_packages.org_id', '=', $admin)
                ->where('loan_details.created_at', '>=', $from_date)
                ->where('loan_details.created_at', '<=', $to_date)
                ->where('loans.status', '=', 3)
                ->get();

        $count = count($loans);

        if ($count > 0) {
          $loans = json_decode(json_encode($loans), true);

          // dd($loans);

          $columns = array("Loan Title", "Amount", "Currency", "Status", "Date");
          
          $callback = function() use ($loans, $columns)
          {
              $file = fopen('php://output', 'w');
              fputcsv($file, $columns);

              foreach($loans as $loan) {
                  fputcsv($file, array($loan["loan_title"], $loan["amount"], $loan["currency"], $loan["status"], $loan["created_at"]));
              }
              fclose($file);
          };
          
          $headers = array(
              "Content-type" => "text/csv",
              "Content-Disposition" => "attachment; filename=$filename.csv",
              "Pragma" => "no-cache",
              "Cache-Control" => "must-revalidate, post-check=0, pre-check=0",
              "Expires" => "0"
          );

          return \Response::stream($callback, 200, $headers); 
        }
        else {
          Session::flash('error', 'You have not made any transactions within the selected period.');
          return redirect()->back();
        }
    }
    elseif ($resource_type == 'loan' && $type_criteria == 'defaulted') {
        $loans = DB::table('loans')
                ->join('loan_details', 'loan_details.loan_id', '=', 'loans.id')
                ->join('loan_packages', 'loan_packages.id', '=', 'loans.loan_package_id')
                ->select('loans.loan_title', 'loans.amount', 'loans.currency', 'loans.status', 'loans.created_at')
                ->where('loan_packages.org_id', '=', $admin)
                ->where('loan_details.created_at', '>=', $from_date)
                ->where('loan_details.created_at', '<=', $to_date)
                ->where('loans.status', '=', 4)
                ->get();

        $count = count($loans);

        if ($count > 0) {
          $loans = json_decode(json_encode($loans), true);

          // dd($loans);

          $columns = array("Loan Title", "Amount", "Currency", "Status", "Date");
          
          $callback = function() use ($loans, $columns)
          {
              $file = fopen('php://output', 'w');
              fputcsv($file, $columns);

              foreach($loans as $loan) {
                  fputcsv($file, array($loan["loan_title"], $loan["amount"], $loan["currency"], $loan["status"], $loan["created_at"]));
              }
              fclose($file);
          };
          
          $headers = array(
              "Content-type" => "text/csv",
              "Content-Disposition" => "attachment; filename=$filename.csv",
              "Pragma" => "no-cache",
              "Cache-Control" => "must-revalidate, post-check=0, pre-check=0",
              "Expires" => "0"
          );

          return \Response::stream($callback, 200, $headers); 
        }
        else {
          Session::flash('error', 'You have not made any transactions within the selected period.');
          return redirect()->back();
        }
    }
    elseif ($resource_type == 'loan' && $type_criteria == 'pending') {
        $loans = DB::table('loans')
                ->join('loan_details', 'loan_details.loan_id', '=', 'loans.id')
                ->join('loan_packages', 'loan_packages.id', '=', 'loans.loan_package_id')
                ->select('loans.loan_title', 'loans.amount', 'loans.currency', 'loans.status', 'loans.created_at')
                ->where('loan_packages.org_id', '=', $admin)
                ->where('loan_details.created_at', '>=', $from_date)
                ->where('loan_details.created_at', '<=', $to_date)
                ->where('loans.status', '=', 0)
                ->get();

        $count = count($loans);

        if ($count > 0) {
          $loans = json_decode(json_encode($loans), true);

          // dd($loans);

          $columns = array("Loan Title", "Amount", "Currency", "Status", "Date");
          
          $callback = function() use ($loans, $columns)
          {
              $file = fopen('php://output', 'w');
              fputcsv($file, $columns);

              foreach($loans as $loan) {
                  fputcsv($file, array($loan["loan_title"], $loan["amount"], $loan["currency"], $loan["status"], $loan["created_at"]));
              }
              fclose($file);
          };
          
          $headers = array(
              "Content-type" => "text/csv",
              "Content-Disposition" => "attachment; filename=$filename.csv",
              "Pragma" => "no-cache",
              "Cache-Control" => "must-revalidate, post-check=0, pre-check=0",
              "Expires" => "0"
          );

          return \Response::stream($callback, 200, $headers); 
        }
        else {
          Session::flash('error', 'You have not made any transactions within the selected period.');
          return redirect()->back();
        }
    }

    //Generate Transaction Reports
    if ($resource_type == 'transaction' && $type_criteria == 'all') {
        $transactions = DB::table('transactions')
                ->join('transaction_types', 'transaction_types.id', '=', 'transactions.transaction_type_id')
                ->select('transactions.txn_code', 'transactions.txn_type', 'transaction_types.name', 'transactions.amount', 'transactions.created_at')
                ->where('transactions.org_id', '=', $admin)
                ->where('transactions.created_at', '>=', $from_date)
                ->where('transactions.created_at', '<=', $to_date)
                ->get();

        $count  = count($transactions);

        if ($count > 0) {
            $transactions = json_decode(json_encode($transactions), true);

            // dd($transactions);

            $columns = array("Transaction Code", "Credit/Debit(1=Credit/2=Debit)", "Transaction Type", "Amount", "Date");
            
            $callback = function() use ($transactions, $columns)
            {
                $file = fopen('php://output', 'w');
                fputcsv($file, $columns);

                foreach($transactions as $transaction) {
                    fputcsv($file, array($transaction["txn_code"], $transaction["txn_type"], $transaction["name"], $transaction["amount"], $transaction["created_at"]));
                }
                fclose($file);
            };
            
            $headers = array(
                "Content-type" => "text/csv",
                "Content-Disposition" => "attachment; filename=$filename.csv",
                "Pragma" => "no-cache",
                "Cache-Control" => "must-revalidate, post-check=0, pre-check=0",
                "Expires" => "0"
            );

            return \Response::stream($callback, 200, $headers);
        }
        else {
            Session::flash('error', 'You have not made any transactions within the selected period.');
            return redirect()->back();
        }
    }
    elseif ($resource_type == 'transaction' && $type_criteria == 'debits') {
        $transactions = DB::table('transactions')
                ->join('transaction_types', 'transaction_types.id', '=', 'transactions.transaction_type_id')
                ->select('transactions.txn_code', 'transactions.txn_type', 'transaction_types.name', 'transactions.amount', 'transactions.created_at')
                ->where('transactions.org_id', '=', $admin)
                ->where('transactions.txn_type', '=', 2)
                ->where('transactions.created_at', '>=', $from_date)
                ->where('transactions.created_at', '<=', $to_date)
                ->get();

        $count = count($transactions);

        if($count > 0) {
            $transactions = json_decode(json_encode($transactions), true);

            // dd($transactions);

            $columns = array("Transaction Code", "Credit/Debit(1=Credit/2=Debit)", "Transaction Type", "Amount", "Date");
            
            $callback = function() use ($transactions, $columns)
            {
                $file = fopen('php://output', 'w');
                fputcsv($file, $columns);

                foreach($transactions as $transaction) {
                    fputcsv($file, array($transaction["txn_code"], $transaction["txn_type"], $transaction["name"], $transaction["amount"], $transaction["created_at"]));
                }
                fclose($file);
            };
            
            $headers = array(
                "Content-type" => "text/csv",
                "Content-Disposition" => "attachment; filename=$filename.csv",
                "Pragma" => "no-cache",
                "Cache-Control" => "must-revalidate, post-check=0, pre-check=0",
                "Expires" => "0"
            );

            return \Response::stream($callback, 200, $headers);
        }
        else {
          Session::flash('error', 'You have not made any transactions within the selected period.');
          return redirect()->back();
        }
    }
    elseif ($resource_type == 'transaction' && $type_criteria == 'credits') {
        $transactions = DB::table('transactions')
                ->join('transaction_types', 'transaction_types.id', '=', 'transactions.transaction_type_id')
                ->select('transactions.txn_code', 'transactions.txn_type', 'transaction_types.name', 'transactions.amount', 'transactions.created_at')
                ->where('transactions.org_id', '=', $admin)
                ->where('transactions.txn_type', '=', 1)
                ->where('transactions.created_at', '>=', $from_date)
                ->where('transactions.created_at', '<=', $to_date)
                ->get();

        $count = count($transactions);

        if($count  > 0) {
            $transactions = json_decode(json_encode($transactions), true);

            // dd($transactions);

            $columns = array("Transaction Code", "Credit/Debit(1=Credit/2=Debit)", "Transaction Type", "Amount", "Date");
            
            $callback = function() use ($transactions, $columns)
            {
                $file = fopen('php://output', 'w');
                fputcsv($file, $columns);

                foreach($transactions as $transaction) {
                    fputcsv($file, array($transaction["txn_code"], $transaction["txn_type"], $transaction["name"], $transaction["amount"], $transaction["created_at"]));
                }
                fclose($file);
            };
            
            $headers = array(
                "Content-type" => "text/csv",
                "Content-Disposition" => "attachment; filename=$filename.csv",
                "Pragma" => "no-cache",
                "Cache-Control" => "must-revalidate, post-check=0, pre-check=0",
                "Expires" => "0"
            );

            return \Response::stream($callback, 200, $headers);
        }
        else {
            Session::flash('error', 'You have not made any transactions within the selected period.');
            return redirect()->back();
        }
    }

    //Generate Revenue Reports Interest Rates and Total Money made
    if ($resource_type == 'revenue' && $type_criteria == 'all') {
        $transactions = DB::table('transactions')
                ->join('transaction_types', 'transaction_types.id', '=', 'transactions.transaction_type_id')
                ->select('transactions.txn_code', 'transaction_types.name', 'transactions.amount', 'transactions.created_at')
                ->where('transactions.org_id', '=', $admin)
                ->where('transactions.txn_type', '=', 1)
                ->where('transactions.transaction_type_id', '=', 6)
                ->where('transactions.created_at', '>=', $from_date)
                ->where('transactions.created_at', '<=', $to_date)
                ->get();

        $count = count($transactions);

        if ($count  > 0) {
            $transactions = json_decode(json_encode($transactions), true);

            // dd($transactions);

            $columns = array("Transaction Code", "Transaction Type", "Amount", "Date");
            
            $callback = function() use ($transactions, $columns)
            {
                $file = fopen('php://output', 'w');
                fputcsv($file, $columns);

                foreach($transactions as $transaction) {
                    fputcsv($file, array($transaction["txn_code"], $transaction["name"], $transaction["amount"], $transaction["created_at"]));
                }
                fclose($file);
            };
            
            $headers = array(
                "Content-type" => "text/csv",
                "Content-Disposition" => "attachment; filename=$filename.csv",
                "Pragma" => "no-cache",
                "Cache-Control" => "must-revalidate, post-check=0, pre-check=0",
                "Expires" => "0"
            );

            return \Response::stream($callback, 200, $headers);   
        }
        else {
              Session::flash('error', 'You have not made any transactions within the selected period.');
              return redirect()->back();
            }
    }
    elseif ($resource_type == 'revenue' && $type_criteria == 'profit') {
      $debits = DB::table('transactions')
                ->join('transaction_types', 'transaction_types.id', '=', 'transactions.transaction_type_id')
                ->select('transactions.amount')
                ->where('transactions.org_id', '=', $admin)
                ->where('transactions.txn_type', '=', 2)
                ->where('transactions.transaction_type_id', '=', 4)
                ->where('transactions.created_at', '>=', $from_date)
                ->where('transactions.created_at', '<=', $to_date)
                ->get();

        $credits = DB::table('transactions')
                ->join('transaction_types', 'transaction_types.id', '=', 'transactions.transaction_type_id')
                ->select('transactions.amount')
                ->where('transactions.org_id', '=', $admin)
                ->where('transactions.txn_type', '=', 1)
                ->where('transactions.transaction_type_id', '=', 6)
                ->where('transactions.created_at', '>=', $from_date)
                ->where('transactions.created_at', '<=', $to_date)
                ->get();
        $count = count($credits);

        if ($count > 0) {
            $debits = json_decode(json_encode($debits), true);
            $credits = json_decode(json_encode($credits), true);
            
            $total_debit = array();
            $total_credit = array();

            foreach($debits[0] as $k => $v){
              $total_debit[$k] = array_sum(array_column($debits, $k));
            }
            foreach($credits[0] as $k => $v){
              $total_credit[$k] = array_sum(array_column($credits, $k));
            }

            if($total_credit > $total_debit) {
                $transactions = DB::table('transactions')
                    ->join('transaction_types', 'transaction_types.id', '=', 'transactions.transaction_type_id')
                    ->select('transactions.txn_code', 'transaction_types.name', 'transactions.amount', 'transactions.created_at')
                    ->where('transactions.org_id', '=', $admin)
                    ->where('transactions.txn_type', '=', 1)
                    ->where('transactions.transaction_type_id', '=', 6)
                    ->where('transactions.created_at', '>=', $from_date)
                    ->where('transactions.created_at', '<=', $to_date)
                    ->get();

                $transactions = json_decode(json_encode($transactions), true);

                $columns = array("Transaction Code", "Transaction Type", "Amount", "Date");
                
                $callback = function() use ($transactions, $columns)
                {
                    $file = fopen('php://output', 'w');
                    fputcsv($file, $columns);

                    foreach($transactions as $transaction) {
                        fputcsv($file, array($transaction["txn_code"], $transaction["name"], $transaction["amount"], $transaction["created_at"]));
                    }
                    fclose($file);
                };
                
                $headers = array(
                    "Content-type" => "text/csv",
                    "Content-Disposition" => "attachment; filename=$filename.csv",
                    "Pragma" => "no-cache",
                    "Cache-Control" => "must-revalidate, post-check=0, pre-check=0",
                    "Expires" => "0"
                );

                return \Response::stream($callback, 200, $headers);
            }
            else {
              Session::flash('error', 'You have not made any profit within the selected period.');
              return redirect()->back();
            }
        }
        else {
              Session::flash('error', 'You have not made any transactions within the selected period.');
              return redirect()->back();
            }
    }
    elseif ($resource_type == 'revenue' && $type_criteria == 'loss') {
      $debits = DB::table('transactions')
                ->join('transaction_types', 'transaction_types.id', '=', 'transactions.transaction_type_id')
                ->select('transactions.amount')
                ->where('transactions.org_id', '=', $admin)
                ->where('transactions.txn_type', '=', 2)
                ->where('transactions.transaction_type_id', '=', 4)
                ->where('transactions.created_at', '>=', $from_date)
                ->where('transactions.created_at', '<=', $to_date)
                ->get();

        $credits = DB::table('transactions')
                ->join('transaction_types', 'transaction_types.id', '=', 'transactions.transaction_type_id')
                ->select('transactions.amount')
                ->where('transactions.org_id', '=', $admin)
                ->where('transactions.txn_type', '=', 1)
                ->where('transactions.transaction_type_id', '=', 6)
                ->where('transactions.created_at', '>=', $from_date)
                ->where('transactions.created_at', '<=', $to_date)
                ->get();

        $count = count($credits);

        if($count > 0) {
            $debits = json_decode(json_encode($debits), true);
            $credits = json_decode(json_encode($credits), true);
            
            $total_debit = array();
            $total_credit = array();

            foreach($debits[0] as $k => $v){
              $total_debit[$k] = array_sum(array_column($debits, $k));
            }
            foreach($credits[0] as $k => $v){
              $total_credit[$k] = array_sum(array_column($credits, $k));
            }

            if($total_credit < $total_debit) {
                $transactions = DB::table('transactions')
                    ->join('transaction_types', 'transaction_types.id', '=', 'transactions.transaction_type_id')
                    ->select('transactions.txn_code', 'transaction_types.name', 'transactions.amount', 'transactions.created_at')
                    ->where('transactions.org_id', '=', $admin)
                    ->where('transactions.txn_type', '=', 1)
                    ->where('transactions.transaction_type_id', '=', 6)
                    ->where('transactions.created_at', '>=', $from_date)
                    ->where('transactions.created_at', '<=', $to_date)
                    ->get();
                    
                $transactions = json_decode(json_encode($transactions), true);

                $columns = array("Transaction Code", "Transaction Type", "Amount", "Date");
                
                $callback = function() use ($transactions, $columns)
                {
                    $file = fopen('php://output', 'w');
                    fputcsv($file, $columns);

                    foreach($transactions as $transaction) {
                        fputcsv($file, array($transaction["txn_code"], $transaction["name"], $transaction["amount"], $transaction["created_at"]));
                    }
                    fclose($file);
                };
                
                $headers = array(
                    "Content-type" => "text/csv",
                    "Content-Disposition" => "attachment; filename=$filename.csv",
                    "Pragma" => "no-cache",
                    "Cache-Control" => "must-revalidate, post-check=0, pre-check=0",
                    "Expires" => "0"
                );

                return \Response::stream($callback, 200, $headers);
            }
            else {
              Session::flash('error', 'You have not made any losses within the selected period.');
              return redirect()->back();
            }
        }
        else {
              Session::flash('error', 'You have not made any transactions within the selected period.');
              return redirect()->back();
            }  
    }

    //Generate Principal report
    if ($resource_type == 'principal' && $type_criteria == 'all') {
      $principal = DB::table('transactions')
                ->join('transaction_types', 'transaction_types.id', '=', 'transactions.transaction_type_id')
                ->select('transactions.amount')
                ->where('transactions.org_id', '=', $admin)
                ->where('transactions.txn_type', '=', 2)
                ->where('transactions.transaction_type_id', '=', 4)
                ->where('transactions.created_at', '>=', $from_date)
                ->where('transactions.created_at', '<=', $to_date)
                ->get();

        $count = count($principal);

        if($count > 0) {
            $principal = json_decode(json_encode($principal), true);

            $total_principal = array();

            foreach($principal[0] as $k => $v){
              $total_principal[$k] = array_sum(array_column($principal, $k));
            }

            if($total_principal > 0) {
                $transactions = DB::table('transactions')
                    ->join('transaction_types', 'transaction_types.id', '=', 'transactions.transaction_type_id')
                    ->select('transactions.txn_code', 'transaction_types.name', 'transactions.amount', 'transactions.created_at')
                    ->where('transactions.org_id', '=', $admin)
                    ->where('transactions.txn_type', '=', 2)
                    ->where('transactions.transaction_type_id', '=', 4)
                    ->where('transactions.created_at', '>=', $from_date)
                    ->where('transactions.created_at', '<=', $to_date)
                    ->get();

                $transactions = json_decode(json_encode($transactions), true);

                $columns = array("Transaction Code", "Transaction Type", "Amount", "Date");
                
                $callback = function() use ($transactions, $columns)
                {
                    $file = fopen('php://output', 'w');
                    fputcsv($file, $columns);

                    foreach($transactions as $transaction) {
                        fputcsv($file, array($transaction["txn_code"], $transaction["name"], $transaction["amount"], $transaction["created_at"]));
                    }
                    fclose($file);
                };
                
                $headers = array(
                    "Content-type" => "text/csv",
                    "Content-Disposition" => "attachment; filename=$filename.csv",
                    "Pragma" => "no-cache",
                    "Cache-Control" => "must-revalidate, post-check=0, pre-check=0",
                    "Expires" => "0"
                );

                return \Response::stream($callback, 200, $headers);
            }
            else {
              Session::flash('error', 'You do not have Principal within the selected period.');
              return redirect()->back();
            }
        }
        else {
            Session::flash('error', 'You have not made transactions within the specified period.');
            return redirect()->back();
        }

        // dd($total_credit);
    }
    
  }

  public function reportBorrowing()
  {
    $user = Auth::user();
   return view('back/organization/analytics/report_borrowing', compact('user'));
  }

  public function reportLending()
  {
    $user = Auth::user();
   return view('back/organization/analytics/report_lending', compact('user'));
  }

  public function reportTransactions()
  {
    $user = Auth::user();
   return view('back/organization/analytics/report_transactions', compact('user'));
  }

  public function reportGroup()
  {
    $user = Auth::user();
   return view('back/organization/analytics/report_group', compact('user'));
  }

  public function reportCashFlow()
  {
    $user = Auth::user();
   return view('back/organization/analytics/report_cashflowprojections', compact('user'));
  }

  public function reportDisbursement()
  {
    $user = Auth::user();
   return view('back/organization/analytics/report_disbursement', compact('user'));
  }

  public function reportProfitLoss()
  {
    $user = Auth::user();
   return view('back/organization/analytics/report_profitloss', compact('user'));
  }

  public function reportPendingDues()
  {
    $user = Auth::user();
   return view('back/organization/analytics/report_pendingdues', compact('user'));
  }

}
