<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreRegisterRequest extends FormRequest
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
            "name" => [
                'required',
                'string',
                'min:3',
            ],
            "address" => [
                'required', //must be letters
                'string',
                'min:4', // must be at least 4 characters in length
            ],
            'contactNumber' => [
                'required',
                'min:10',
                'min:11',
                'string',
            ],
            "email" => [
                'required',
                'email',
            ],
            "user_typeId" => [
                'required',
                'numeric',
                'max:2',
            ],
            'password' => [
                'required',
                'string',
                'min:8', // must be at least 8 characters in length
                'regex:/[a-z]/', // must contain at least one lowercase letter
                'regex:/[A-Z]/', // must contain at least one uppercase letter
                'regex:/[0-9]/', // must contain at least one digit
            ],
        ];
    }
}
