<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdatePaymentRequest extends FormRequest
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
        return [
            'amount' => 'sometimes|numeric|min:0',
            'payment_type' => 'sometimes|string|in:semester1,semester2,yearly',
            'payment_method' => 'sometimes|string|in:credit_card,paypal,aba,acleda,cash',
            'user_id' => 'sometimes|exists:users,id',
            'status' => 'sometimes|in:pending,paid,failed',
        ];
    }
}
