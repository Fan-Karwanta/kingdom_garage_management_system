<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class vehicleModelAddEditFormRequest extends FormRequest
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
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'brand_id' => 'required|regex:/^[(a-zA-Z0-9\s)\p{L}]+$/u|max:50',
            'model_name' => 'required',
        ];
    }

    public function messages()
    {
        return [
            'model_name.required' => trans('message.Vehicle model is required.'),
            'brand_id.required' => trans('message.Vehicle brand is required.'),
        ];

    }
}
