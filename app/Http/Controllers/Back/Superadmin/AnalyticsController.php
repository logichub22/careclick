<?php

namespace App\Http\Controllers\Back\Superadmin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Services\Report\ReportService;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Auth;
use App\User;
use App\Models\Organization\Organization;
use Carbon\Carbon;
use PdfReport;
use ExcelReport;
use Illuminate\Support\Facades\DB;

class AnalyticsController extends Controller
{
    public function getGraphical()
    {
		// data goes here 
    	return view('back/superadmin/analytics/graphs');
    }
    
    public function getMonths()
    {
        $month_array = [];
        $user_dates = User::orderBy('created_at', 'ASC')->pluck('created_at');
        $user_dates = json_decode($user_dates);

        if (! empty($user_dates)) {
            foreach($user_dates as $unformatted_date) {
                $date = new \DateTime($unformatted_date->date);
                $month_name = $date->format('M');
                $month_number = $date->format('m');
                $month_array[ $month_number ] = $month_name;
            }
        }

        return $month_array;
    }

    public function getMonthlyUserCount($month)
    {
        $monthly_user_count = User::whereMonth('created_at', $month)->get()->count(); 
        return $monthly_user_count;
    }
	
	public function getMonthlyUsers()
	{
        //$monthly_user_data_array = [];
        $monthly_user_count_array = [];
        $month_array = $this->getMonths();
        $month_name_array = [];
        if (! empty($month_array)) {
            foreach($month_array as $month_number => $month_name) {
                $monthly_user_count = $this->getMonthlyUserCount($month_number);
                array_push($monthly_user_count_array, $monthly_user_count);
                array_push($month_name_array, $month_name);
            }
        }

        $max_no = max( $monthly_user_count_array );
        $max = round(($max_no + 10/2) / 10) * 10;
        $month_array = $this->getMonths();
        $monthly_user_data_array = [
            'months' => $month_name_array,
            'user_count_data' => $monthly_user_count_array,
            'max' => $max
        ];

        return $monthly_user_data_array;
	}

    public function reports()
    {
    	return view('back/superadmin/analytics/reports');
    }

    public function export(Request $request, ReportService $reportService)
    {
    	$request->validate([
    		'resource_type' => 'required',
    		'type' => 'required',
    		'type_criteria' => 'required',
    		'from_date' => 'required|date',
    		'to_date' => 'required|date',
    		'sort_by' => 'required',
    		'file-name' => 'required',
    		'title' => 'required',
    	]);

    	$admin = Auth::user()->id;
		$resource = $request->input('resource_type');
		$fromDate = $request->input('from_date');
        $toDate = $request->input('to_date');
        $sortBy = $request->input('sort_by');
        $title = $request->input('title');
        $doc = $request->input('file-name');
        $type = $request->input('type_criteria');

        switch ($resource) {
        	// If resource is User
        	case "user":
		        switch ($type) {
		        	// All Users
		        	case "all":
		        		$meta = [
				            'All Users from' => $fromDate . ' To ' . $toDate,
				            'Sort By' => $sortBy
				        ];
		        		$queryBuilder = DB::table('users')
			                ->join('org_users', 'org_users.user_id', '=', 'users.id')
			                ->join('organizations', 'organizations.id', '=', 'org_users.org_id')
			                ->select('users.*')
			                ->where(array("organizations.admin_id" => $admin))
	                        ->where('users.created_at','>=',Carbon::parse($fromDate))
	                        ->where('users.created_at','<=',Carbon::parse($toDate))
	                        ->orderBy('users.created_at', $sortBy);
		                $columns = [
				            'First Name' => 'name',
				            'Other Names' => 'other_names',
				            'Phone' => 'msisdn',
				            'Date of Joining' => 'created_at',
				            'Status' => function($result) { // You can do if statement or any action do you want inside this closure
				                return ($result->status == 1) ? 'Active' : 'Inactive';
				            },
				            'Email Verified' => function($result) { // You can do if statement or any action do you want inside this closure
				                return ($result->verified == 1) ? 'Yes' : 'No';
				            }
				        ];
		        		break;
		        	// Verified Users
		        	case "verified":
		        		$meta = [
				            'Verified Users from' => $fromDate . ' To ' . $toDate,
				            'Sort By' => $sortBy
				        ];
		        		$queryBuilder = DB::table('users')
			                ->join('org_users', 'org_users.user_id', '=', 'users.id')
			                ->join('organizations', 'organizations.id', '=', 'org_users.org_id')
			                ->select('users.*')
			                ->where(array("organizations.admin_id" => $admin, "users.verified" => true))
	                        ->where('users.created_at','>=',Carbon::parse($fromDate))
	                        ->where('users.created_at','<=',Carbon::parse($toDate))
	                        ->orderBy('users.created_at', $sortBy);
				        $columns = [
				            'First Name' => 'name',
				            'Other Names' => 'other_names',
				            'Phone' => 'msisdn',
				            'Date of Joining' => 'created_at',
				            'Status' => function($result) { // You can do if statement or any action do you want inside this closure
				                return ($result->status == 1) ? 'Active' : 'Inactive';
				            },
				            'Email Verified' => function($result) { // You can do if statement or any action do you want inside this closure
				                return ($result->verified == 1) ? 'Yes' : 'No';
				            }
				        ];
		        		break;
		        	// Unverified Users
		        	case "unverified":
		        		$meta = [
				            'Unverified Users from' => $fromDate . ' To ' . $toDate,
				            'Sort By' => $sortBy
				        ];
		        		$queryBuilder = DB::table('users')
			                ->join('org_users', 'org_users.user_id', '=', 'users.id')
			                ->join('organizations', 'organizations.id', '=', 'org_users.org_id')
			                ->select('users.*')
			                ->where(array("organizations.admin_id" => $admin, "users.verified" => false))
	                        ->where('users.created_at','>=',Carbon::parse($fromDate))
	                        ->where('users.created_at','<=',Carbon::parse($toDate))
	                        ->orderBy('users.created_at', $sortBy);
				        $columns = [
				            'First Name' => 'name',
				            'Other Names' => 'other_names',
				            'Phone' => 'msisdn',
				            'Date of Joining' => 'created_at',
				            'Status' => function($result) { // You can do if statement or any action do you want inside this closure
				                return ($result->status == 1) ? 'Active' : 'Inactive';
				            },
				            'Email Verified' => function($result) { // You can do if statement or any action do you want inside this closure
				                return ($result->verified == 1) ? 'Yes' : 'No';
				            }
				        ];
		        		break;
		        	// Active Users
		        	case "active":
		        		$meta = [
				            'Active Users from' => $fromDate . ' To ' . $toDate,
				            'Sort By' => $sortBy
				        ];
		        		$queryBuilder = DB::table('users')
			                ->join('org_users', 'org_users.user_id', '=', 'users.id')
			                ->join('organizations', 'organizations.id', '=', 'org_users.org_id')
			                ->select('users.*')
			                ->where(array("organizations.admin_id" => $admin, "users.status" => true))
	                        ->where('users.created_at','>=',Carbon::parse($fromDate))
	                        ->where('users.created_at','<=',Carbon::parse($toDate))
	                        ->orderBy('users.created_at', $sortBy);
				        $columns = [
				            'First Name' => 'name',
				            'Other Names' => 'other_names',
				            'Phone' => 'msisdn',
				            'Date of Joining' => 'created_at',
				            'Status' => function($result) { // You can do if statement or any action do you want inside this closure
				                return ($result->status == 1) ? 'Active' : 'Inactive';
				            },
				            'Email Verified' => function($result) { // You can do if statement or any action do you want inside this closure
				                return ($result->verified == 1) ? 'Yes' : 'No';
				            }
				        ];
		        		break;
		        	case "inactive":
		        		$meta = [
				            'Inactive Users from' => $fromDate . ' To ' . $toDate,
				            'Sort By' => $sortBy
				        ];
		        		$queryBuilder = DB::table('users')
			                ->join('org_users', 'org_users.user_id', '=', 'users.id')
			                ->join('organizations', 'organizations.id', '=', 'org_users.org_id')
			                ->select('users.*')
			                ->where(array("organizations.admin_id" => $admin, "users.status" => false))
	                        ->where('users.created_at','>=',Carbon::parse($fromDate))
	                        ->where('users.created_at','<=',Carbon::parse($toDate))
	                        ->orderBy('users.created_at', $sortBy);
				        $columns = [
				            'First Name' => 'name',
				            'Other Names' => 'other_names',
				            'Phone' => 'msisdn',
				            'Date of Joining' => 'created_at',
				            'Status' => function($result) { // You can do if statement or any action do you want inside this closure
				                return ($result->status == 1) ? 'Active' : 'Inactive';
				            },
				            'Email Verified' => function($result) { // You can do if statement or any action do you want inside this closure
				                return ($result->verified == 1) ? 'Yes' : 'No';
				            }
				        ];
		        		break;
		        }
        		break;
        	case "loan":
        		break;
        	case "transaction":
        		break;
        }

        if ($request->type === 'pdf') {
            return PdfReport::of($title, $meta, $queryBuilder, $columns)
                      ->setCss([
                          '.head-content' => 'border-width: 1px',
                       ])
                      ->download($doc); // or download('filename here..') to download pdf
        } else {
            return ExcelReport::of($title, $meta, $queryBuilder, $columns)
                      ->editColumn('Created On', [
                          'displayAs' => function($result) {
                              return $result->created_at->format('d M Y');
                          }
                      ])
                     // ->limit(20)
                      ->download($doc); // or download('filename here..') to download pdf
        }

    	Session::flash('success', 'Report generated successfully');
    	return redirect()->back();
    }
}
