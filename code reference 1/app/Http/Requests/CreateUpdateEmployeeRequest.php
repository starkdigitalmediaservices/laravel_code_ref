<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreateUpdateEmployeeRequest extends FormRequest
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
            'name'           => 'required|string|min:3|max:100',
            'salary'         => 'required|digits_between:2,10',
            'gender'         => 'required',
            'departments_id' => 'required',
            "hobby"          => 'required|array|min:1|max:3',
        ];
    }


    public function messages()
    {
        return [
            'hobby.required' => 'Hobbies are required!',
            'departments_id.required' => 'Department is required!'
        ];

    }
}
