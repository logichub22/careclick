<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class OrganizationRequest extends FormRequest
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
            'name' =>'required|unique:organization_details,name',
            'org_msisdn' => 'nullable|unique:organization_details,org_msisdn',
            'msisdn' => 'required|unique:users,msisdn',
            'address' => 'required',
            'country' =>'required',
            'domain' => 'nullable|active_url',
            'first_name' => 'required',
            'other_names' => 'required',
            'is_financial' => 'required',
            'org_email' => 'nullable|unique:organization_details,org_email',
            'password' => 'nullable|confirmed',
            'permit' => 'nullable|file|max:2000',
            'tax' => 'nullable|file|max:2000',
        ];
    }
}
