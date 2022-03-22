<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class LoanPackageRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name' => 'required|unique:loan_packages,name',
            'repayment_plan' => 'required',
            'min_score' => 'required|numeric|min:1|max:10',
            // 'max_score' => 'required|numeric|min:1|max:10',
            'min_amount' => 'required|numeric',
            'max_amount' => 'required|numeric',
            'interest' => 'required|numeric|min:1|max:100',
            'description' => 'required',
        ];
    }
}
