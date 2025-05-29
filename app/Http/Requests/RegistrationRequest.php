<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RegistrationRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        if($this->isMethod("post") === false)
        {
            return [
            //
            ];
        }

        return [
            'full_name' => 'required',
            'email' => 'required|email|unique:users,email',
            'password' => ['required','confirmed'],
            'password_confirmation' => 'required',
        ];

    }

    //This will customise any error message
    public function messages(){
        if($this->isMethod('post') === false)
        {
            return [];
        }
        return [
            'full_name.required' => 'Full name is required.',
            'email.unique' => 'Email already taken'
        ];
    }
}
