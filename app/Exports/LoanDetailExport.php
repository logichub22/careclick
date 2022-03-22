<?php

namespace App\Exports;

use App\Models\General\LoanDetail;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Auth;
use DB;

class LoanDetailExport implements FromCollection, WithHeadings
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    
    {
        $user = Auth::user();
        $loans = DB::table('loans')
        ->where('user_id', $user->id)
        ->join('loan_details', 'loan_details.loan_id', '=', 'loans.id')         
        ->select('loans.loan_title', 'loans.amount',
                'loan_details.package_name', 'loan_details.principal_due',
                'loan_details.interest_charge_frequency', 'loan_details.interest_due',
                'loan_details.amount_payable', 'loan_details.balance', 'loan_details.penalty_due', 'loan_details.charge_per_installment', 'loan_details.next_payment_date', 'loan_details.payback_date',  'loans.currency', 'loans.created_at')
        ->get();
        return $loans;
    }
    
    public function headings(): array
    {
        return [
            'Loan Title',
            'Amount',
            'Package Name',
            'Interest Charge Frequency',
            'Interest Due',
            'Amount Payable',
            'Balance',
            'Penalty Due',
            'Charge Per Installment',
            'Next Payment Date',
            'Payback Date',
            'Currency',
            'Date'
        ];
    }
}
