<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreUserRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        // This is set to true, but you can change it to false if you want to restrict access to the request
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:6',
            'password_confirmation' => 'nullable|string|min:6', // For frontend validation
            'role'=> 'required|in:admin,student,teacher',
            'gender' => 'nullable|in:male,female',
            'dob' => 'nullable|date',
            'phone' => 'nullable|string|max:15',
            'status' => 'nullable|integer',
        ];
    }
}
