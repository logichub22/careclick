<?php

namespace App\Exports;

use App\Models\General\Transaction;
use Maatwebsite\Excel\Concerns\FromCollection;

class LoanTransaction implements FromCollection
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return Transaction::all();
    }
}
