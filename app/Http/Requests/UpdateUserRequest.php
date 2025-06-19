<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateUserRequest extends FormRequest
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
            'first_name' => 'sometimes|string',
            'last_name' => 'sometimes|string',
            'email' => 'sometimes|email',
            'password' => 'sometimes|string|min:6',
            'status' => 'integer|in:0,1',
            'dob' => 'date|nullable',
            'phone'          => 'nullable|string|max:20',
            'profile_image'  => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'gender'         => 'nullable|in:male,female'
        ];
    }
}
