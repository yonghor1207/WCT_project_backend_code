<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StorePaymentRequest extends FormRequest
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
            'amount' => 'required|numeric|min:0',
            'payment_type' => 'required|string|in:semester1,semester2,yearly',
            'payment_method' => 'required|string|in:credit_card,paypal,aba,acleda,cash',
            'transaction_id' => 'nullable|string|max:255',
            'user_id' => 'required|exists:users,id',
            'status' => 'string|in:pending,paid,failed',
        ];
    }
}
