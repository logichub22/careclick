<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UserRequest extends FormRequest
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
            'name' => 'required',
            'other_names' => 'required',
            'email' => 'required|unique:users,email',
            'msisdn' => 'required|unique:users,msisdn',
            'account' => 'nullable|numeric',
            // ['required','regex:/(234)[0-9]{10}/']
            'gender' => 'required',
            'marital_status' => 'required',
            'doc_type' => 'required',
            'doc_no' => 'required|unique:user_details,doc_no',
            'dob' => 'required|date',
            'country' => 'required',
            'residence' => 'required',
            'city' => 'required',
            //'state' => 'required',
            'postal_code' => 'required',
            'address' => 'required',
        ];
    }
}
