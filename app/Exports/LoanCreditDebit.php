<?php

namespace App\Exports;

use App\Models\General\LoanDetail;
use Maatwebsite\Excel\Concerns\FromCollection;

class LoanCreditDebit implements FromCollection
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return LoanDetail::all();
    }
}
