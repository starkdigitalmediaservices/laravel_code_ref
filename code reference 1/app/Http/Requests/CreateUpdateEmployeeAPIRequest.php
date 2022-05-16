<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;


class CreateUpdateEmployeeAPIRequest extends FormRequest
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
            'gender'         => 'required|in:m,f', 
            'departments_id' => 'required',
            "hobby"          => 'required|array|min:1|max:3',
            "hobby.*"        => "required|string|distinct|min:1|max:1",
        ];
    }


    public function messages()
    {
        return [
            'hobby.required' => 'Hobbies are required!',
            'departments_id.required' => 'Department is required!'
        ];

    }


    protected function failedValidation(Validator $validator) {
        throw new HttpResponseException(response()->json([
            "success" => false,
            "error"    => $validator->errors(),
            "data"     => array(),
        ], 422));
    }

}
