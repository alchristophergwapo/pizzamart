<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UserRegisterRequest extends FormRequest
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
            //
            "firstname" => [
                'regex:/^[a-zA-Z]+$/u', //must be letters
                'required',
                'min:2', // must be at least 2 characters in length
            ],
            "lastname" => [
                'regex:/^[a-zA-Z]+$/u', //must be letters
                'required',
                'min:3', // must be at least 3 characters in length
            ],
            "address" => [
                'required', //must be letters
                'string',
                'min:4', // must be at least 4 characters in length
            ],
            "email" => [
                'required',
                'email',
            ],
            'contactNumber' => [
                'required',
                'min:10',
                'min:11',
                'string',
            ],
            "user_typeId" => [
                'required',
                'numeric',
                'max:2',
            ],
            'password' => [
                'required',
                'string',
                'regex:/[a-z]/', // must contain at least one lowercase letter
                'regex:/[A-Z]/', // must contain at least one uppercase letter
                'regex:/[0-9]/', // must contain at least one digit
                'min:8', // must be at least 8 characters in length
            ],
        ];
    }
}
