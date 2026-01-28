<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class FrontendServiceFormRequest extends FormRequest
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
            'firstname' => 'required|regex:/^[\p{L}\p{M}\s\-\'\.]+$/u|max:50',
            'lastname' => 'required|regex:/^[\p{L}\p{M}\s\-\'\.]+$/u|max:50',
            'email' => 'required|email|custom_email|unique:users,email',
            'mobile' => 'required|min:6|max:16|regex:/^[- +()]*[0-9][- +()0-9]*$/',
            'country_id' => 'required',
            'address' => 'required',
            'vehical_id' => 'required',
            'vehicabrand' => 'required',
            'fueltype' => 'required',
            'modelname' => 'required',
            'number_plate' => 'required',
            'jobno' => 'required',
            's_date' => 'required',
            'repair_cat' => 'required',
        ];
    }

    public function messages()
    {
        return [
            'firstname.required' => trans('message.First name is required.'),
            'firstname.regex' => trans('message.First name is only alphabets and space.'),
            'firstname.max' => trans('message.First name should not more than 50 character.'),

            'lastname.required' => trans('message.Last name is required.'),
            'lastname.regex' => trans('message.Last name is only alphabets and space.'),
            'lastname.max' => trans('message.Last name should not more than 50 character.'),

            'email.required' => trans('message.Email is required.'),
            'email.email' => trans('message.Please enter a valid email address. Like : sales@mojoomla.com'),
            'email.unique' => trans('message.Email you entered is already registered.'),
            'email.custom_email' => trans('message.Please enter a valid email address. Like : sales@mojoomla.com'),

            'mobile.required' => trans('message.Contact number is required.'),
            // 'mobile.numeric'  => trans('message.Contact number only numbers are allowed.',
            'mobile.min' => trans('message.Contact number minimum 6 digits.'),
            'mobile.max' => trans('message.Contact number maximum 16 digits.'),
            'mobile.regex' => trans('message.Contact number must be number, plus, minus and space only.'),

            'country_id.required' => trans('message.Country field is required.'),
            'address.required' => trans('message.Address field is required.'),
        ];

    }
}
