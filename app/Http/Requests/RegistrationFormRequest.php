<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;


class RegistrationFormRequest extends FormRequest
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
            'username' => 'required|string|between:2,100',
            'email' => 'required|string|email:rfc,dns|max:100|unique:users',
            'password' => 'required|string|min:6',
            'password_confirmation' => 'required|same:password',
            'phone_number'=>'required',
            'first_name'=>'required',
            'last_name'=>'required',
            'address'=>'required',
        ];
    }
}
