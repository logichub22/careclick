<?php

namespace App\Http\Controllers\Front;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\General\Document;
use App\Mail\ContactEmail;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Response;
use App\User;
use App\Models\General\Group;
use App;
use Config;
use Auth;
use App\Mail\SendMemberEmail;
class PageController extends Controller
{
    // public function __construct()
    // {
    //     $this->middleware('auth')->only('accountCreated', 'organizationSignUp');
    // }
	/**
     * Show the landing page.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
    	return view('front/pages/index');
    }

    /**
     * Show the team page.
     *
     * @return \Illuminate\Http\Response
     */
    public function about()
    {
    	return view('front/pages/about');
    }

    public function article1()
    {
    	return view('front/article-nov');
    }
    public function article2()
    {
    	return view('front/article-dec');
    }
    public function article3(){
        return view('front/article-jan');
    }
    public function article4(){
        return view('front/article-jan2');
    }

    /**
     * Show the faq page.
     *
     * @return \Illuminate\Http\Response
     */
    public function faq()
    {
    	return view('front/pages/faq');
    }

    /**
     * Show the team page.
     *
     * @return \Illuminate\Http\Response
     */
    public function team()
    {
        return view('front/pages/team');
    }

    /**
     * Show the mission page.
     *
     * @return \Illuminate\Http\Response
     */
    public function mission()
    {
    	return view('front/pages/mission');
    }

    /**
     * Show the privacy page.
     *
     * @return \Illuminate\Http\Response
     */
    public function privacy()
    {
    	return view('front/pages/privacy');
    }

    /**
     * Show the terms page.
     *
     * @return \Illuminate\Http\Response
     */
    public function terms()
    {
    	return view('front/pages/terms');
    }

    /**
     * Show the blog page.
     *
     * @return \Illuminate\Http\Response
     */
    public function news()
    {
        return view('front/pages/news');
    }

    /**
     * Show the contact page.
     *
     * @return \Illuminate\Http\Response
     */
    public function contact()
    {
        return view('front/pages/contact');
    }

    /**
     * Show the investing page.
     *
     * @return \Illuminate\Http\Response
     */
    public function investing()
    {
        return view('front/pages/investing');
    }

    /**
     * Show the funding page.
     *
     * @return \Illuminate\Http\Response
     */
    public function funding()
    {
        return view('front/pages/funding');
    }

    /**
     * Show the individual sign up page.
     *
     * @return \Illuminate\Http\Response
     */
    public function individualSignUp()
    {
        $documents = Document::all();
        $countries = DB::table('countries')->whereIn('id', [25, 93, 101, 131, 159, 177, 186,])->get();
        $incomes = DB::table('income_classes')->get();
        $maritals = DB::table('maritals')->whereIn('id', [1, 2,])->get();
        $genders = DB::table('genders')->get();
        $residents = DB::table('resident_types')->get();

        return view('auth/individual-signup', compact('documents', 'countries', 'incomes', 'maritals', 'genders', 'residents'));
    }

    /**
     * Show the successful individual sign up page.
     *
     * @return \Illuminate\Http\Response
     */
    public function accountCreated()
    {
        return view('auth/individual-success');
    }

    /**
     * Show the organization sign up page.
     *
     * @return \Illuminate\Http\Response
     */
    public function organizationSignUp()
    {
        $countries = DB::table('countries')->whereIn('id', [25, 93, 101, 131, 159, 177, 186,])->get();
        return view('auth/organization-signup', compact('countries'));
    }

    /**
     * Show the successful organization sign up page.
     *
     * @return \Illuminate\Http\Response
     */
    public function regSuccess()
    {
        return view('auth/organization-success');
    }

    /**
     * Contact Form
     *
     * @return \Illuminate\Http\Response
     */
    public function postContact(Request $request)
    {
        $request->validate([
            'company' => 'required',
            'name' => 'required|string',
            'email' => 'required|email',
            'message' => 'required',
            'telephone' => 'required|numeric',
        ]);

        $name = $request->name;
        $email = $request->email;
        $message = $request->message;
        $company = $request->company;
        $telephone = $request->telephone;
        $mail = 'info@jamborow.co.uk';

        $maildata = [
            'name' => $name,
            'email' => $email,
            'message' => $message,
            'company' => $company,
            'telephone' => $telephone,
        ];

        Mail::to($mail)->send(new ContactEmail($maildata));
        Session::flash('success', 'Your mail has been sent successfully');
        return redirect()->back();
    }

    public function userData(Request $request)
    {
            $first_name = $request->name;
            $other_names = $request->other_names;
            $email = $request->email;
            $msisdn = $request->msisdn;
            $gender = $request->gender;
            $marital_status = $request->marital_status;
            $doc_type = $request->doc_type;
            $doc_no = $request->doc_no;
            $dob = $request->dob;
            $country = $request->country;
            $residence = $request->residence;
            $city = $request->city;
            $state = $request->state;
            $postal_code = $request->postal_code;
            $address = $request->address;
            $income = $request->income;
            $occupation = $request->occupation;

            $client = new \GuzzleHttp\Client;

            // Create Customer
            $response = $client->request('POST', 'http://104.131.174.54:7171/api/v1.0/customers', [
                'headers' => [
                    'api-key' => 'oksSJXwSB6dz9j3qYo6qiNv2iQbubyok',
                    'Content-Type' => 'application/json'
                ],
                'auth' => [
                    null,
                    'oksSJXwSB6dz9j3qYo6qiNv2iQbubyok'
                ],
                'body' => json_encode([
                    'email' => $email,
                    'phone' => $msisdn,
                    'first_name' => $first_name,
                    'other_name' => $other_names,
                    //'dob' => $newdob,
                    'gender' => strtoupper($gender),
                    'address' => $address,
                    'hometown' => $city,
                    'occupation' => $occupation,
                    'nationality_id' => $country,
                ])
            ]);

            $customer = json_decode($response->getBody(), true);

            $myres = $customer['your_response'];

            $uid = $myres['username'];

            //Create new account for customer
            $res = $client->request('POST', 'http://104.131.174.54:7171/api/v1.0/accounts', [
                'headers' => [
                    'api-key' => 'oksSJXwSB6dz9j3qYo6qiNv2iQbubyok',
                    'Content-Type' => 'application/json'
                ],
                'auth' => [
                    null,
                    'oksSJXwSB6dz9j3qYo6qiNv2iQbubyok'
                ],
                'body' => json_encode([
                    'account_category_id' => 2,
                    'account_type_id' => 1,
                    'customer_uid' => $uid,
                    'name' => 'Test User',
                ])
            ]);

            $newcus = json_decode($res->getBody(), true);
            $newres = $newcus['your_response'];
            $account = $newres['account_number'];

            return response()->json([
                'first_name' => $first_name,
                'other_names' => $other_names,
                'email' => $email,
                'phone_number' => $msisdn,
                'gender' => $gender,
                'date_of_birth' => $dob,
                'marital_status' => $marital_status,
                'document_of_identification' => $doc_type,
                'document_number' => $doc_no,
                'nationality_id' => $country,
                'state' => $state,
                'city' => $city,
                'type_of_residence' => $residence,
                'postal_code' => $postal_code,
                'address' => $address,
                'occupation' => $occupation,
                'annual_income' => $income,
                'advance_username' => $uid,
                'advance_account_number' => $account,
            ]);
    }
    public function getData()
    {
        $data = array(
            "code" => null
        );
        $file = base_path() . '/public/countries.txt';
        $input = json_decode(file_get_contents($file));

        $newdata = json_encode($input->your_response);

        foreach($input->your_response as $key => $value) {
            $content = [];
            $newarr = array_push($content, $data);
        }
        return response()->json($c);
    }

    public function ipTest(Request $request)
    {
        $ip = $request->getClientIp();
        $data = \Location::get('66.102.0.0');
        dd($data->countryCode);
    }

    public function testCsv()
    {
        $headers = [
            'Cache-Control' => 'must-revalidate, post-check=0, pre-check=0',
            'Content-type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename=template.csv',
            'Expires' => '0',
            'Pragma' => 'public'
        ];

        $mycolumns = [];

        $user_columns = User::all(['name', 'other_names', 'email', 'msisdn', 'account_no'])->toArray();
        $group_columns = Group::all(['group_data'])->toArray();
        //dd($group_columns);
        $newd = $group_columns[0];
        $data = $newd['group_data'];
        $newd = array_keys($data);
        //dd(array_values($newd));
        array_unshift($user_columns, array_keys($user_columns[0]));
        $columns = array_values($user_columns[0]);

        array_push($mycolumns, array_merge($columns, array_values($newd)));
        //dd($mycolumns[0]);

        $callback = function() use ($mycolumns)
        {
            $file = fopen('php://output', 'w');
            fputcsv($file, $mycolumns[0]);
            fclose($file);
        };

        //dd($callback);

        // return response()->streamDownload(function () {
        //     echo GitHub::api('repo')
        //                 ->contents()
        //                 ->readme('laravel', 'laravel')['contents'];
        // }, 'laravel-readme.md');
        return Response::stream($callback, 200, $headers);
    }

    public function getJson($currency_unit)
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_URL, 'http://game.helioengine-qa-ts1.com:10101/SMSGameService/api/Game/GetGameGroupFinancialRules?request.gameFinancialRuleIDs=30&request.apiKey=USSDAPIK3Y&request.gameGroupCode=euromillions');

        $result = curl_exec($ch);
        curl_close($ch);

        $newdata = json_decode($result);

        $data = $newdata->gameFinancialRules;
        $prices = [];

        $formed_arr = [
            'formatted_data' => [
                'gameID' => 0,
                'ticketPrices' => [
                    [
                        'ticketPrice' => 0,
                        'currencyCode' => '',
                        'highestPrizeCategory' => [

                        ],
                    ]
                ]
            ]
        ];

        $usd_arr = [];

        foreach($data[0]->ticketPrices as $price) {
            foreach($price->amounts as $amount) {
                if ($amount->currencyCode === $currency_unit) {
                    $formed_arr['formatted_data']['gameID'] = $data[0]->gameID;
                    $formed_arr['formatted_data']['ticketPrices'][0]['currencyCode'] = $amount->currencyCode;
                    $formed_arr['formatted_data']['ticketPrices'][0]['ticketPrice'] = $amount->amount;
                }
            }
        }

        foreach($data[0]->prizeCategories as $category) {
           foreach($category->amounts as $amount) {
               if ($amount->currencyCode === $currency_unit) {
                   $category->amounts = $amount;
                   array_push($usd_arr, $category);
               }
           }
        }

        $highest = max(array_column($usd_arr, 'amounts'));

        $formed_arr['formatted_data']['ticketPrices'][0]['highestPrizeCategory'] = $highest;

        $gameID = $formed_arr['formatted_data']['gameID'];
        $ticketPrice = $formed_arr['formatted_data']['ticketPrices'][0]['ticketPrice'];
        $currency = $formed_arr['formatted_data']['ticketPrices'][0]['currencyCode'];
        $high = $formed_arr['formatted_data']['ticketPrices'][0]['highestPrizeCategory'];

        $string = 'Game ID is ' . $gameID . ' with the ticket price being ' . $currency . ' ' . number_format($ticketPrice, 2) . ' and the highest amount ' . $currency . ' ' . number_format($high->prizeAmount);

        return $string;

    }

    public function changeLanguage(Request $request)
    {
        $user = Auth::user();
        $userlang = $request->input('lang');

        $setting = DB::table('lang_settings')->where('user_id', $user->id)->first();

        if(!is_null($setting)) {

            DB::table('lang_settings')->where('user_id',$user->id)->update(array(
                'lang' => $userlang
            ));
        } else {

            DB::table('lang_settings')->insert(
                ['user_id' => $user->id, 'lang' => $userlang, 'created_at' => date('Y-m-d H:m:s'), 'updated_at' => date('Y-m-d H:m:s')]
            );
        }

        App::setLocale($userlang);

        // $currlocale = Config::get('app.locale');
        // dd($currlocale);

        Session::put('userlang', $userlang);

        $message = "success";

        if($userlang == 'en'){

            $message = "You have set your language as English. your change will be remembered on your next login";
        }

        else{

            $message = "Umeweka lugha yako kama Kiswahili. mabadiliko yako yatakumbukwa kwenye kuingia kwako kwa pili";
        }

        return redirect()->back()->with('success',$message);
    }

    public function userNotifications(Request $request)
    {
        $user = $request->user();

        $notifications = DB::table('notifications')->where('notifiable_id', $user->id)->whereNull('read_at')->orderBy('created_at', 'desc')->take(5)->get();

        dd($notifications);
    }

    public function loadLevelTwo($id)
    {
        //dd($id);
        $level_twos = DB::table('level_two')->where('level_one_id', $id)->pluck('name', 'id');
        //dd($subcounties);
        return $level_twos;
    }

    public function loadLevelThree($id)
    {
        //dd($id);
        $level_threes = DB::table('level_three')->where('level_two_id', $id)->pluck('name', 'id');
        //dd($subcounties);
        return $level_threes;
    }

    public function sendEmailGroups(Request $request)
    {
        $coordinatorname = $request->coordinatorname;
        $subject = $request->subject;
        $message = $request->message;
        $coordinatoremail = $request->coordinatoremail;
        $trainername = $request->trainername;
        $traineremail = $request->traineremail;

        if($request->recipient == 1) {
            // Data to send to mail
            $maildata = array(
                'name' => $coordinatorname,
                'subject' => $subject,
                'message' => $message,
                'email' => $coordinatoremail,
                'sender' => 'Association Administrator',
            );

            Mail::to($coordinatoremail)->send(new SendMemberEmail($maildata));
        } else if($request->recipient == 0) {
            $members = DB::table('users')
                     ->join('group_members', 'group_members.user_id', '=', 'users.id')
                     ->where('group_members.group_id', '=', $request->group)
                     ->select('users.name', 'users.email')
                     ->get();

            if(count($members) > 0) {
                foreach($members as $member) {
                    $maildata = array(
                        'name' => $member->name,
                        'subject' => $subject,
                        'message' => $message,
                        'email' => $member->email,
                        'sender' => 'Association Administrator',
                    );

                    Mail::to($member->email)->send(new SendMemberEmail($maildata));
                }
            }
        } else if($request->recipient == 2) {
            $maildata = array(
                'name' => $trainername,
                'subject' => $subject,
                'message' => $message,
                'email' => $traineremail,
                'sender' => 'Association Administrator',
            );

            Mail::to($traineremail)->send(new SendMemberEmail($maildata));
        }

        // Flash and redirect
        Session::flash('success', 'Group email has been successfully sent');
        return redirect()->back();
    }
}
