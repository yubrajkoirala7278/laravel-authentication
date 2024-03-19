<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class AuthRequest extends FormRequest
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
    public function rules()
    {
        if ($this->is('register')) {
            // Validation rules for registration
            return [
                'name' => ['required', 'string', 'min:2'],
                'email' => ['required', 'string', 'email', 'max:100', Rule::unique('users')],
                'password' => ['required', 'string', 'confirmed', 'min:6'],
                'password_confirmation' => ['required', 'min:6', 'same:password'],
            ];
        } elseif ($this->is('login')) {
            // Validation rules for login
            return [
                'email' => ['required', 'string', 'email', 'max:100'],
                'password' => ['required', 'string', 'min:6'],
            ];
        }elseif($this->is('reset-password')){
             // Validation rules for password reset
            return[
                'password' => ['required', 'string', 'confirmed', 'min:6'],
                'password_confirmation' => ['required', 'min:6', 'same:password'],
            ];
        } else {
            return [];
        }
    }
}
