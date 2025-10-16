<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StorePaymentRequest extends FormRequest
{
    public function authorize(): bool
    {
        // Allow all authenticated users
        return auth()->check();
    }

    public function rules(): array
    {
        return [
            'method' => 'required|string|in:credit_card,paypal',
        ];
    }

    public function messages(): array
    {
        return [
            'method.required' => 'Payment method is required.',
            'method.in' => 'Payment method must be either credit_card or paypal.',
        ];
    }
}
