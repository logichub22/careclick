<?php

namespace App\Http\Controllers\Back\Federation;

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
    	return view('back/federation/analytics/graphs');
	}
	
	public function getMonthlyUsers()
	{
		
	}

    public function reports()
    {
    	return view('back/federation/analytics/reports');
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
