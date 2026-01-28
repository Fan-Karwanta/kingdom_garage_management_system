<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreOtherSettingEditFormRequest extends FormRequest
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
            'timezone' => 'required',
            'language' => 'required',
            'dateformat' => 'required',
            'Currency' => 'required',
            'default_emp' => 'required_if:frontend_service,on',
            'default_charge' => 'required_if:frontend_service,on',
            'default_password' => 'required_if:frontend_service,on|min:6|max:12|regex:/^(?=.*[a-zA-Z\p{L}])(?=.*\d).+$/u',
        ];
    }

    public function messages()
    {
        return [
            'timezone.required' => trans('message.Timezone is required.'),
            'language.required' => trans('message.Language is required.'),
            'dateformat.required' => trans('message.Date format is required.'),
            'Currency.required' => trans('message.Currency is required.'),
            'default_emp.required_if' => trans('message.Assign Job To is required.'),
            'default_charge.required_if' => trans('message.Default Service Charge is required.'),

            'default_password.required_if' => trans('message.Default Password is required.'),
            'default_password.regex' => trans('message.Default Password must be combination of letters and numbers.'),
            'default_password.min' => trans('message.Default Password length minimum 6 character.'),
            'default_password.max' => trans('message.Default Password length maximum 12 character.'),
        ];

    }
}
