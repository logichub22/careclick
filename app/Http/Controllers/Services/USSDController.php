<?php

namespace App\Http\Controllers\Services;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

use App\Helpers\CommonFunctions;

use App\User;

class USSDController extends Controller
{
    public function __construct(Request $request){
        $phoneNumber = ltrim($request->phoneNumber, "+");

        if($userID = User::where(["msisdn" => $phoneNumber])->first()){
            Auth::login($userID);
            return Auth::user();
        }
        else{
            // Auth::logout();
        }
    }

    public function testUSSD(Request $request)
    {
        $sessionId = $request->sessionId;
        $serviceCode = $request->serviceCode;
        $phoneNumber = ltrim($request->phoneNumber, "+");
        $text = $request->text;

        $response = "
            END ID: {$sessionId}\n
            SCode: {$serviceCode}\n
            Phone: {$phoneNumber}\n
            Text: {$text}
        ";

        header('Content-type: text/plain');
        echo $response;
    }

    public function handleUSSD(Request $request)
    {
        if(Auth::check()){
            $sessionId = $request->sessionId;
            $serviceCode = $request->serviceCode;
            $phoneNumber = ltrim($request->phoneNumber, "+");
            $text = $request->text;
            $bankArr = [];

            $user = Auth::user();
            $userPIN = $user->pin;
            $userID = $user->id;

            if(Hash::check('0000', $userPIN)){
                if(strlen($text) == 4){
                    $response = "CON Enter new 4-digit PIN again to confirm";
                }
                elseif(strlen($text) == 9 && count(explode("*", $text)) == 2){
                    //Change PIN
                    $textArr = explode("*", $text);
                    if($textArr[0] == $textArr[1]){
                        DB::table('users')->where('id', Auth::user()->id)->update([
                            'pin' => Hash::make($textArr[0])
                        ]);
                        $response = "END PIN changed successfully. Please restart your session.";
                    }
                    else{
                        $response = "END Entered PIN did not match initial entry. Please restart your session.";
                    }
                    
                }
                else{
                    $response = "CON Welcome! You need to set up your PIN to continue. Enter a new 4-digit PIN to continue";
                }
            }
            else{
                
                $user_currency = CommonFunctions::userCurrency($user);
                $banks = CommonFunctions::getAllBanks($user_currency);
                $allBankCodes = array();
                $sn = 1;
                foreach($banks as $bank){
                    $allBankCodes[$sn] = $bank->code;
                    $sn ++;
                }

                if($text == ""){
                    $response = "CON Welcome to Jamborow USSD Channel. Please pick an option \n";
                    $response .= "1 Apply for a Loan\n";
                    $response .= "2 My Loans\n";
                    $response .= "3 Make Contributions\n";
                    $response .= "4 My Wallets\n";
                    $response .= "5 Savings\n";
                    $response .= "6 Withdrawal\n";
                    $response .= "7 Exit";
                }
                elseif(starts_with($text, "1")){
                    if($text == "1"){
                        $limit_user_if_has_active_loan = false;

                        if(CommonFunctions::userHasActiveLoan($user) && $limit_user_if_has_active_loan){
                            $response = "END You cannot make a new application while you have an outstanding loan.";
                        }
                        else{
                            $packages = $this->getLoanPackages($user);
                
                            $response = "";
                            if(count($packages) > 0){
                                $response = "CON Choose a loan package \n";
                                $sn = 1;

                                foreach($packages as $package){
                                    $response .= "{$sn} {$package->name} {$package->currency} {$package->min_amount}-{$package->max_amount} at {$package->interest_rate}% {$package->repayment_plan} \n";

                                    $sn ++;
                                }
                            }
                            else{
                                $response = "END There are no Loan Packages available for you at this time.\n";
                            }
                        }
                    }
                    
                    elseif(starts_with($text, "1*")){
                        $components = explode("*", $text);

                        if(count($components) == 2){
                            $packageIndex = intval($components[1]) - 1;

                            $package = $this->getLoan($user, $packageIndex);
                            if($package){
                                $response = "CON Apply for loan: \n\"{$package->name} at {$package->interest_rate}% {$package->repayment_plan}\" \n";
                                $response .= "Enter loan amount between {$package->currency} {$package->min_amount} and {$package->max_amount} to continue";
                            }
                            else{
                                $response = "END You have selected an invalid option. Please try again.";
                            }
                        }
                        elseif(count($components) == 3){
                            $packageIndex = intval($components[1]) - 1;
                            $package = $this->getLoan($user, $packageIndex);

                            $amount = $components[2];
                            if($amount >= $package->min_amount && $amount <= $package->max_amount){
                                switch($package->repayment_plan){
                                    case "weekly":
                                        $frequency = "weeks";
                                    break;
                                    
                                    case "monthly":
                                        $frequency = "months";
                                    break;

                                    default:
                                        $frequency = "2-week periods";
                                }

                                $response = "CON Enter loan duration for your loan in {$frequency}\n";
                                $response .= "{$package->name} - {$package->currency} {$amount}";
                            }
                            else{
                                $response = "END Invalid loan amount";
                            }
                        }
                        elseif(count($components) == 4){
                            $packageIndex = intval($components[1]) - 1;
                            $package = $this->getLoan($user, $packageIndex);
                            $amount = $components[2];
                            $response = "CON You are about to apply for a loan of {$package->currency} {$amount}\n";
                            $response .= "Please enter your 4-digit PIN to confirm:";
                        }
                        elseif(count($components) == 5){
                            $pin = $components[4];
                            if(Hash::check($pin, $userPIN)){
                                $packageIndex = intval($components[1]) - 1;
                                $package = $this->getLoan($user, $packageIndex);

                                $amount = $components[2];
                                $duration = $components[3];
                                
                                $loan_title = 'USSD-'.date('dmY').'-'.Str::random(3);
                                $loan_request = CommonFunctions::requestLoan($user, $package, $amount, $duration, $loan_title);

                                $response = $loan_request[0] == true ? "END You loan application has been received. You will be notified once it is approved." : "END We were unable to process to submit your request. Please try again soon.";
                            }
                            else{
                                $response = "END You have entered an invalid PIN. Please try again";
                            }
                        }
                    }
                }
                elseif(starts_with($text, "2")){
                    if($text == "2"){
                        $loans = $this->getActiveLoans($user);
                        // return $loans;
                        $response = "";
                        if(count($loans) > 0){
                            $response = "CON Your loans \n";
                            $sn = 1;

                            foreach($loans as $loan){
                                $loan_status = $loan->status == 0 ? "Pending" : "-{$loan->balance}";
                                $response .= "{$sn} {$loan->currency}{$loan->amount} ({$loan_status}) \n";

                                $sn ++;
                            }
                        }
                        else{
                            $response = "END You do not have any active or pending loan at the moment.\n";
                        }
                    }
                    
                    elseif(starts_with($text, "2*")){
                        $components = explode("*", $text);

                        if(count($components) == 2){
                            $loanIndex = intval($components[1]) - 1;

                            $loan = $this->getLoanDetails($user, $loanIndex);
                            if($loan){
                                $response_text = "{$loan->loan_title} | {$loan->amount}, {$loan->interest_rate}% {$loan->repayment_plan}\n";

                                if($loan->status == 1){
                                    $outstanding_installments = CommonFunctions::getOutstandingInstallments($loan->id);

                                    $response_text .= "{$outstanding_installments} outstanding payment(s) remaining. ";
                                    $response_text .= "How many installments would you like to pay?";

                                    $response = "CON {$response_text}";
                                }
                                else{
                                    $response_text .= "\n[Awaiting approval]\n";

                                    $response = "END {$response_text}";
                                }
                            }
                            else{
                                $response = "END You have selected an invalid option. Please try again.";
                            }
                        }
                        elseif(count($components) == 3){
                            $loanIndex = intval($components[1]) - 1;
                            $loan = $this->getLoanDetails($user, $loanIndex);

                            $installments = $components[2];

                            $outstanding_installments = CommonFunctions::getOutstandingInstallments($loan->id);

                            if($installments > $outstanding_installments) {
                                $response = "END You have provided an invalid value. Please try again.";
                            }
                            else{
                                $amount_payable = CommonFunctions::calculateAmountPayable($loan->id, $installments);
                                $wallet_balance = CommonFunctions::getUserWalletBalance($user->id);

                                if($amount_payable > $wallet_balance){
                                    $response = "END Insufficient balance. Please top up your wallet and try again.";
                                }
                                else{
                                    $response = "CON You are about to pay a sum of {$loan->currency} {$amount_payable}. Please enter your 4-digit PIN to confirm:";
                                }
                            }
                        }
                        elseif(count($components) == 4){
                            $pin = $components[3];
                            if(Hash::check($pin, $userPIN)){
                                //
                                $loanIndex = intval($components[1]) - 1;
                                $loan = $this->getLoanDetails($user, $loanIndex);

                                $installments = $components[2];
                                // $amount_payable = CommonFunctions::calculateAmountPayable($loan->id, $installments);

                                $repayment_response = CommonFunctions::repayLoan($user, $loan->id, $installments);

                                $repayment_response_msg = $repayment_response[1];
                                $response = "END {$repayment_response_msg}";
                            }
                            else{
                                $response = "END You have entered an invalid PIN. Please try again";
                            }
                        }
                    }
                }
                elseif(starts_with($text, "3")){
                    if($text == "3"){
                        $groups = $this->getGroups($user);
                        $response = "";
                        if(count($groups) > 0){
                            $response = "CON Select a group";
                            $sn = 1;

                            foreach($groups as $group){
                                $response .= "\n{$sn} {$group->name} ({$group->currency} {$group->amount} {$group->frequency})";

                                $sn ++;
                            }
                        }
                        else{
                            $response = "END You do not belong to any group. Please contact your organization to add you to a group.\n";
                        }
                    }
                    
                    elseif(starts_with($text, "3*")){
                        $components = explode("*", $text);

                        if(count($components) == 2){
                            $groupIndex = intval($components[1]) - 1;

                            $group = $this->getGroup($user, $groupIndex);
                            if($group){
                                $response = "CON You are about to make a contribution of {$group->currency} {$group->amount} to {$group->name}. Please enter your 4-digit PIN to confirm transaction.";
                            }
                            else{
                                $response = "END You have selected an invalid option. Please try again.";
                            }
                        }
                        elseif(count($components) == 3){
                            $pin = $components[2];
                            if(Hash::check($pin, $userPIN)){
                                $groupIndex = intval($components[1]) - 1;

                                $group = $this->getGroup($user, $groupIndex);

                                $contribution = CommonFunctions::makeContribution($user, $group->id);

                                $api_response = $contribution[0] = true ? $contribution[1] . ". Thank you for using our service." : $contribution[1];

                                $response = "END {$api_response}";
                            }
                            else{
                                $response = "END You have entered an invalid PIN. Please try again";
                            }
                        }
                    }
                }
                elseif($text == "4"){
                    $user_balance = number_format(CommonFunctions::getUserWalletBalance($user->id));
                    $savings_balance = number_format(CommonFunctions::getUserSavingsBalance($user->id));

                    $response = "END Your wallet balances:\n";
                    $response .= "Main wallet: {$user_currency} {$user_balance}\n";
                    $response .= "Savings Wallet: {$user_currency} {$savings_balance}";
                }
                elseif(starts_with($text, "5")){
                    $savings_wallet_balance = CommonFunctions::getUserSavingsBalance($user->id);

                    if($text == "5"){
                        $savings_bal = number_format($savings_wallet_balance, 2);
                        $response = "CON You savings wallet balance is {$user_currency} {$savings_bal}. Please select an option\n";
                        $response .= "1. Deposit\n2. Withdraw\n3. Exit";
                    }
                    
                    elseif(starts_with($text, "5*")){
                        $components = explode("*", $text);

                        if(count($components) == 2){
                            $savings_option = $components[1];
                            if($savings_option == "1"){
                                // Deposit
                                $response = "CON How much do you want to deposit?";
                            }
                            elseif($savings_option == "2"){
                                // Withdrawal
                                $total_withdrawable_balance = CommonFunctions::withdrawableSavingsBalance($user);
                                $response = "CON Total available balance: {$user_currency} {$total_withdrawable_balance}\n";
                                $response .= "How much do you want to withdraw?";
                            }
                            elseif($savings_option == "3"){
                                // Exit
                                $response = "END Thank you for using our service.";
                            }
                            else{
                                $response = "END You have selected an invalid option. Please try again.";
                            }
                        }
                        elseif(count($components) == 3){
                            $savings_option = $components[1];

                            if($savings_option == "1"){
                                $amount = $components[2];
                                $wallet_balance = CommonFunctions::getUserWalletBalance($user->id);

                                if($amount <= $wallet_balance){
                                    $response = "CON Enter the duration in months, between 1 and 12\n";
                                }
                                else{
                                    $response = "END You can not save more than the amount in your wallet balance.";
                                }
                            }
                            elseif($savings_option == "2"){
                                // Withdrawal
                                $total_withdrawable_balance = CommonFunctions::withdrawableSavingsBalance($user);
                                $amount = floatval($components[2]);

                                if($amount <= $total_withdrawable_balance){
                                    $amount = number_format($components[2]);

                                    $response = "END You are about to withdraw  {$user_currency} {$amount} from your savings wallet. Please enter your PIN to confirm this transaction.";
                                }
                                else{
                                    $total_withdrawable_balance = number_format($total_withdrawable_balance);
                                    $response = "END You cannot withdraw more than {$user_currency} {$total_withdrawable_balance} at this time.";
                                }
                            }
                        }
                        elseif(count($components) == 4){
                            $savings_option = $components[1];
                            
                            if($savings_option == "1"){
                                $amount = number_format($components[2]);
                                $duration = intval($components[3]);
                                $duration_in_days = $duration * 30;


                                if($duration < 1 || $duration > 12){
                                    $response = "END You have entered an invalid value. Please try again and specify a value between 1 & 12.";
                                }
                                else{
                                    $response = "CON You are about to lock {$user_currency} {$amount} into your savings wallet for {$duration} months. Please enter your PIN to confirm this.";
                                }
                            }
                            elseif($savings_option == "2"){
                                $amount = floatval($components[2]);
                                $pin = $components[3];
                                if(Hash::check($pin, $userPIN)){
                                    $savings_transaction = CommonFunctions::debitSavings($user, $amount);

                                    $response = $savings_transaction[0] == true ? "END ". $savings_transaction[1] : "END Transaction failed:\n" . $savings_transaction[1];
                                }
                                else{
                                    $response = "END You have entered an invalid PIN. Please try again";
                                }
                            }
                        }
                        elseif(count($components) == 5){
                            $savings_option = $components[1];
                            
                            if($savings_option == "1"){
                                $amount = floatval($components[2]);
                                $duration = intval($components[3]);
                                $duration_in_days = $duration * 30;
                                
                                $pin = $components[4];
                                if(Hash::check($pin, $userPIN)){

                                    $savings_transaction = CommonFunctions::creditSavings($user, $amount, $duration_in_days);

                                    $response = $savings_transaction[0] == true ? "END Transaction successful. You can withdraw this amount anytime from " . $savings_transaction[2] . "." : "END Transaction failed:\n" . $savings_transaction[1];
                                }
                                else{
                                    $response = "END You have entered an invalid PIN. Please try again";
                                }
                            }
                        }
                    }
                }
                
                elseif(starts_with($text, "6")){
                    $response =  "1 Bank Account Transfer \n";
                    $response .= "2 Mobile Money Transfer \n";
                    if(starts_with($text, "6*")){
                        if($text == "6*1"){
                            $components = explode("*", $text);
                            if(count($components) == 2){
                                $user_currency = CommonFunctions::userCurrency($user);
                                $banks = CommonFunctions::getAllBanks($user_currency);
                                $response = "CON Choose your bank \n";
                                $sn = 1;
                                foreach($banks as $bank){
                                    $response .= "{$sn} {$bank->name}  \n";
                                    $sn ++;
                                }
                                                                
                            }
                        }
                        elseif(starts_with($text, "6*1")){
                            $components = explode("*", $text);
                            $selectedBankCode = $allBankCodes[$components[2]];
                            if(count($components) == 3){
                                $response = "Please enter amount";
                            }
                            if(count($components) == 4){
                                $response = "Enter your ACCOUNT NUMBER";                             
                            }
                            if(count($components) == 5){
                                $response = "Please enter your 4-digit PIN to continue";  
                            }
                            
                            if(count($components) == 6){
                                $pin = $components[5];
                                if(Hash::check($pin, $userPIN)){
                                    $amount = floatval($components[3]);
                                    $account_no = $components[4];
                                    $bank = $selectedBankCode;
                                    $narration = "Withdrawal";
                                    $user_currency = CommonFunctions::userCurrency($user);
                            
                                    $payload = [
                                        "amount" => $amount,
                                        "account_no" => $account_no,
                                        "bank_name" => $bank,
                                        "currency" =>  $user_currency,
                                        "narration" => $narration,
                                        "name" =>  $user->name
                                        
                                    ];
                                   $result =  $this->proccessWithdrawal($payload);
                                   if($result->status == "error"){
                                         $response = "END ".$result->message;
                                    }
                                    elseif ($result->status == "success") {
                                        
                                        $response = "END ".$result->message;
                                    }else{
                                         $response = "END An error occured. Please Try again ";
                                    }
                                   
                                }else{
                                    $response = "END You have entered an invalid PIN. Please try again";
                                }
                            }
                          
                        
                        }
                        
                        elseif(starts_with($text, "6*2")){
                            $components = explode("*", $text);
                            if(count($components) == 2){
                                $response = "CON Select Network \n";
                                $response .= "1 MTN \n";
                                $response .= "2 Airtel \n";
                                $response .= "3 GLO \n";
                                $response .= "4 9Mobile \n";

                            }
                            if(count($components) == 3){
                                $response = "Please enter amount";
                            }
                            if(count($components) == 4){
                                $response = "Enter your phone number";                             
                            }
                            if(count($components) == 5){
                                $response = "Please enter your 4-digit PIN to continue";  
                            }
                            
                            if(count($components) == 6){
                                $pin = $components[5];
                                if(Hash::check($pin, $userPIN)){
                                    $network = "";
                                    if(starts_with($text, "6*2*1")){
                                        $network = "MTN";
                                    }elseif(starts_with($text, "6*2*2")){
                                        $network = "AIRTEL";
                                    }
                                    elseif(starts_with($text, "6*2*3")){
                                        $network = "GLO";
                                    } elseif(starts_with($text, "6*2*4")){
                                        $network = "9MOBILE";
                                    }
                                    else {
                                        $response = "END You have entered an invalid option. Please try again";  
                                    }
                                    
                                    $amount = floatval($components[3]);
                                    $account_no = $components[4];
                                    $narration = "Withdrawal";
                                    $user_currency = CommonFunctions::userCurrency($user);
                            
                                    $payload = [
                                        "amount" => $amount,
                                        "account_no" => $account_no,
                                        "bank_name" => $network,
                                        "currency" =>  $user_currency,
                                        "narration" => $narration,
                                        "name" =>  $user->name
                                        
                                    ];
                                                                        
                                   $result =  $this->proccessWithdrawal($payload);                                   
                                   
                                   if($result->status == "error"){
                                       $response = "END ".$result->message;
                                   }
                                   elseif ($result->status == "success") {
                                       
                                     $response = "END ".$result->message;
                                   }else{
                                      $response = "END An error occured. Please Try again ";
                                   }
                                                                      
                                }else{
                                    $response = "END You have entered an invalid PIN. Please try again";
                                }
                            }
                          
                        
                        }
                        
                    }
                    

                }
            
                elseif($text == "7"){
                    // Exit
                    $response = "END Thank you for using our service.";
                }
                else{
                    $response = "END You have selected an invalid option. Please try again";
                }
            }
        }
        else{
            $response = "END Not onboarded. Contact your cooperative to get you onboard on Jamborow and get access to our services.";
        }

        header('Content-type: text/plain');
        echo $response;
    }

    public function getLoanPackages($user){
        return CommonFunctions::showLoanPackages($user);
    }

    public function getLoan($user, $packageIndex){
        // Prevent users from accessing new loans while one is active (Disable for now)
        // if(CommonFunctions::userHasActiveLoan($user)) return false;

        $packages = $this->getLoanPackages($user);

        if(isset($packages[$packageIndex])){
            return $packages[$packageIndex]; 
        }
        else{
            return false;
        }
    }

    public function calcRepaymentDate($packageIndex, $duration){
        //
    }

    public function getActiveLoans($user){
        $loans = DB::table('loans')
                    ->join('loan_details', 'loan_details.loan_id', 'loans.id')
                    ->join('loan_packages', 'loan_packages.id',  'loans.loan_package_id')
                    ->where('loans.user_id', $user->id)
                    ->whereIn('loans.status', [0, 1])
                    ->select('loans.id', 'loans.loan_title', 'loans.amount', 'loans.status', 'loan_packages.currency', 'loan_details.balance', 'loan_packages.interest_rate', 'loan_packages.repayment_plan', 'loan_details.loan_schedule')
                    ->take(5)
                    ->get();

        return $loans;
    }
    
    public function getLoanDetails($user, $loanIndex){
        //Should ask user to select length...
        $loans = $this->getActiveLoans($user);

        if(isset($loans[$loanIndex])){
            return $loans[$loanIndex]; 
        }
        else{
            return false;
        }
    }

    public function getGroups($user){
        return CommonFunctions::getUserGroups($user);
    }
    

    public function getGroup($user, $groupIndex){
        //Should ask user to select length...
        $groups = $this->getGroups($user);

        if(isset($groups[$groupIndex])){
            return $groups[$groupIndex]; 
        }
        else{
            return false;
        }
    }
    
    public function proccessWithdrawal($payload){
      
        $res = CommonFunctions::TransferViaFlutterwave($payload);
        return $res;
       
    }
}
