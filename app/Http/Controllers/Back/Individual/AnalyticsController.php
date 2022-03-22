<?php

namespace App\Http\Controllers\Back\Individual;

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
//use Excel;

class AnalyticsController extends Controller
{
    public function collectionsChart()
    {
      $user = Auth::user();

      $x = Loan::all()->sortBy('created_at');

      $loans = DB::table('loans')
              ->where('user_id', $user->id)
              ->select('*')
              ->orderBy('created_at', 'desc')
              ->get();

      $months = $x->groupBy(function ($result, $key){
        return $result->created_at->format('M');
      })->map(function ($result) {
        $user = Auth::user();
        return ($result->where('user_id', $user->id)->count());
      });

      // dd($months);

      $loan_pending   = Loan::all()->sortBy('created_at')->groupBy(function($result, $key) {
        return $result->created_at->format('M');
      })->map(function($result) {
        $user = Auth::user();
        return ($result->where('user_id', $user->id)->where('status', '0')->count());
      });

      // dd($loan_pending);

      $loan_paid   = Loan::all()->sortBy('created_at')->groupBy(function($result, $key) {
        return $result->created_at->format('M');
      })->map(function($result) {
        $user = Auth::user();
        return ($result->where('user_id', $user->id)->where('status', '3')->count());
      });

      $loan_defaulted   = Loan::all()->sortBy('created_at')->groupBy(function($result, $key) {
        return $result->created_at->format('M');
      })->map(function($result) {
        $user = Auth::user();
        return ($result->where('user_id', $user->id)->where('status', '4')->count());
      });

    	return view('back/individual/analytics/loancollections', compact('loan_pending','loan_paid','loan_defaulted', 'user', 'months', 'loans'));
    }

    public function maturityChart() {
      $user = Auth::user();

      $loan_pending   = Loan::orderBy('created_at', 'desc')->where('user_id', $user->id)->where('status', '1')->take(5)->get();
      $loan_paid      = Loan::orderBy('created_at', 'desc')->where('user_id', $user->id)->where('status', '1')->take(5)->get();
      $loan_defaulted = Loan::orderBy('created_at', 'desc')->where('user_id', $user->id)->Where('status', '1')->take(5)->get();

      return view('back/individual/analytics/loanmaturity', compact('loan_pending','loan_paid','loan_defaulted', 'user'));
    }

    public function releasedChart() {
        $user = Auth::user();

        $x = Loan::all()->sortBy('created_at');

        $months = $x->groupBy(function ($result, $key){
          return $result->created_at->format('M');
        })->map(function ($result) {
          $user = Auth::user();
          return ($result->where('user_id', $user->id)->count());
        });

        $loan_released   = Loan::all()->sortBy('created_at')->groupBy(function($result, $key) {
          return $result->created_at->format('M');
        })->map(function($result) {
          $user = Auth::user();
          return ($result->where('user_id', $user->id)->where('status', '1')->count());
        });

        $loan_unreleased  = Loan::all()->sortBy('created_at')->groupBy(function($result, $key) {
          return $result->created_at->format('M');
        })->map(function($result) {
          $user = Auth::user();
          return ($result->where('user_id', $user->id)->where('status', '2')->count());
        });

        $loans = DB::table('loans')
                ->join('loan_packages', 'loan_packages.id', '=', 'loans.loan_package_id')
                ->select('loans.loan_title', 'loans.amount', 'loans.status', 'loans.created_at', 'loans.currency')
                ->where('loan_packages.user_id', $user->id)
                ->get();

      return view('back/individual/analytics/loanreleased', compact('loan_released','loan_unreleased', 'user', 'months', 'loans' ));
    }

    public function genderChart() {
      $user = Auth::user();

      $loan_pending   = Loan::orderBy('created_at', 'desc')->where('user_id', $user->id)->where('status', '1')->take(5)->get();
      $loan_paid      = Loan::orderBy('created_at', 'desc')->where('user_id', $user->id)->where('status', '1')->take(5)->get();
      $loan_defaulted = Loan::orderBy('created_at', 'desc')->where('user_id', $user->id)->Where('status', '1')->take(5)->get();

      return view('back/individual/analytics/gender', compact('loan_pending','loan_paid','loan_defaulted', 'user'));
    }

    public function balanceChart() {
      $user = Auth::user();

      $loan_pending   = Loan::orderBy('created_at', 'desc')->where('user_id', $user->id)->where('status', '1')->take(5)->get();
      $loan_paid      = Loan::orderBy('created_at', 'desc')->where('user_id', $user->id)->where('status', '1')->take(5)->get();
      $loan_defaulted = Loan::orderBy('created_at', 'desc')->where('user_id', $user->id)->Where('status', '1')->take(5)->get();

      return view('back/individual/analytics/balance', compact('loan_pending','loan_paid','loan_defaulted', 'user'));
    }

    public function averageLoanTenureChart() {
      $user = Auth::user();

      $loan_pending   = Loan::orderBy('created_at', 'desc')->where('user_id', $user->id)->where('status', '1')->take(5)->get();
      $loan_paid      = Loan::orderBy('created_at', 'desc')->where('user_id', $user->id)->where('status', '1')->take(5)->get();
      $loan_defaulted = Loan::orderBy('created_at', 'desc')->where('user_id', $user->id)->Where('status', '1')->take(5)->get();

    	return view('back/individual/analytics/averageloantenure', compact('loan_pending','loan_paid','loan_defaulted', 'user'));
    }

    public function savingsChart() {
      $user = Auth::user();

      $loan_pending   = Loan::orderBy('created_at', 'desc')->where('user_id', $user->id)->where('status', '1')->take(5)->get();
      $loan_paid      = Loan::orderBy('created_at', 'desc')->where('user_id', $user->id)->where('status', '1')->take(5)->get();
      $loan_defaulted = Loan::orderBy('created_at', 'desc')->where('user_id', $user->id)->Where('status', '1')->take(5)->get();

      return view('back/individual/analytics/savings', compact('loan_pending','loan_paid','loan_defaulted', 'user'));
    }


   /**REPORTING FUCTIONS**/


    public function reportWallet()
    {
       $user = Auth::user();
    	return view('back/individual/analytics/report_creditdebit', compact('user'));
    }

    public function reportWallet_get(Request $request)
    {
     
      return Excel::download(new LoanDetailExport, 'Jamborow.xlsx');
    }

    public function reportBorrowing()
    {
      $user = Auth::user();
    	return view('back/individual/analytics/report_borrowing', compact('user'));
    }

    public function reportLending()
    {
      $user = Auth::user();
    	return view('back/individual/analytics/report_lending', compact('user'));
    }

    public function reportTransactions()
    {
      $user = Auth::user();
    	return view('back/individual/analytics/report_transactions', compact('user'));
    }

    public function reportGroup()
    {
      $user = Auth::user();
    	return view('back/individual/analytics/report_group', compact('user'));
    }

    public function reportCashFlow()
    {
      $user = Auth::user();
    	return view('back/individual/analytics/report_cashflowprojections', compact('user'));
    }

    public function reportDisbursement()
    {
      $user = Auth::user();
    	return view('back/individual/analytics/report_disbursement', compact('user'));
    }

    public function reportProfitLoss()
    {
      $user = Auth::user();
    	return view('back/individual/analytics/report_profitloss', compact('user'));
    }

    public function reportPendingDues()
    {
      $user = Auth::user();
    	return view('back/individual/analytics/report_pendingdues', compact('user'));
    }
}
