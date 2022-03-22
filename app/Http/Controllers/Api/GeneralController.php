<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\User;
use Auth;
use Carbon\Carbon;
use DB;
use App\Models\General\Role;
use Illuminate\Support\Str;
use App\Models\General\Wallet;
use App\Events\UserCreatedEvent;
use App\Models\General\UserDetail;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Log;
use App\Models\General\AdministrativeRegion;
use App\Models\General\Country;
use App\Models\General\LevelOne;
use App\Models\General\LevelTwo;
use App\Models\General\LevelThree;
use App\Models\General\LevelFour;

class GeneralController extends Controller
{
    public function getRegisterData()
    {
        $docs = DB::table('documents')->get();
        $countries = DB::table('countries')->whereIn('id', [25, 93, 101, 131, 159, 177, 186,])->get();
        $classes = DB::table('income_classes')->get();
        $genders = DB::table('genders')->get();
        $marital_statuses = DB::table('maritals')->get();
        $resident_types = DB::table('resident_types')->get();

        return response()->json([
            'success' => true,
            'message' => [
                'docs' => $docs,
                'countries' => $countries,
                'classes' => $classes,
                'genders' => $genders,
                'marital_statuses' => $marital_statuses,
                'resident_types' => $resident_types,
            ]
        ]);
    }

    public function getRoles()
    {
        $roles = Role::all();

        return response()->json([
            'success' => true,
            'message' => $roles
        ]);
    }

    public function getAdministrativeRegions()
    {
        $regions = AdministrativeRegion::with('country')->get();

        return response()->json([
            'success' => true,
            'message' => $regions
        ]);
    }

    public function getTransactionTypes(Request $request)
    {
        // $user = $request->user();

        // if(!$user) {
        //     return response()->json([
        //         'success' => false,
        //         'message' => 'User not found'
        //     ]);
        // }

        $types = DB::table('transaction_types')->get();

        return response()->json([
            'success' => true,
            'message' => 'Statuses for all transactions',
            'data' => $types
        ]);
    }


    //get repayment plans and currencies
    public function getLendingData()
    {
   
        $currencies = DB::table('currencies')->get();
        $repaymentplans = DB::table('repayment_plans')->get();

        return response()->json([
            'success' => true,
            'message' => [

                'currencies' => $currencies,
                'repaymentplans' => $repaymentplans,
            
            ]
        ]);
    }
}
