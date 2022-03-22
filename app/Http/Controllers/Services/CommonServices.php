<?php

namespace App\Http\Controllers\Services;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\General\FlutterwaveWithdrawal;
use App\Models\General\Transaction;
use App\Models\Organization\OrganizationWallet;

use App\Helpers\CommonFunctions;

class CommonServices extends Controller
{
    public function verifyBankAccount(Request $request)
    {
        $details = CommonFunctions::verifyAccountNumber($request->all());
        return json_encode($details);
    }

    public function verifyFwWithdrawal(Request $request)
    {
        if(isset($request->transfer)){
            $transfer = $request->transfer;
            $transfer = json_decode(json_encode($transfer)); //ensure the payload is an object, not an array
            $trf = FlutterwaveWithdrawal::where('reference', $transfer->reference)
                        ->where('fw_id', $transfer->id)
                        ->first();

            if($trf){

                // Update record
                $trf->status = $transfer->status;
                $trf->message = $transfer->complete_message;
                $trf->save();
                $transaction = Transaction::where('txn_code', $transfer->reference)->first();

                if($transfer->status == "SUCCESSFUL"){
                    // Update debit transaction
                    $transaction->txn_type = 2; // Proper Debit
                    $transaction->save();
                }
                elseif($transfer->status == "FAILED"){

                    // Update debit transaction
                    $transaction->txn_type = 2; // Failed Debit
                    $transaction->save();

                    $org_wallet = OrganizationWallet::where('org_id', $transaction->org_id)->first();
                    $wallet_balance = $org_wallet->balance;


                    // actual amount initially debited
                    $total_amount = $transaction->amount;

                    // Create credit/refund transaction
                    $transaction = new Transaction();
                    $transaction->org_id = $transaction->org_id;
                    $transaction->txn_code = $trf->reference . '_refund';
                    $transaction->txn_type = 1; // Credit (Refund)
                    $transaction->transaction_type_id = 2; // Withdrawal
                    $transaction->amount = $total_amount;
                    $transaction->save();

                    // Update wallet balance (refund)
                    $org_wallet->balance = $wallet_balance + $total_amount;
                    $org_wallet->save();
                    logger('..............................');
                    logger('..............................');
                    logger('***Withdrawal UNSUCCESSFUL***');
                    logger('..............................');
                    logger('..............................');
                }
            }

        }
    }
}
