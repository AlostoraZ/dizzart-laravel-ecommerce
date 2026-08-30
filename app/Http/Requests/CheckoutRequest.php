<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CheckoutRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'customer_name' => ['required', 'string', 'max:255'],
            'customer_email' => ['required', 'email', 'max:255'],
            'phone' => ['required', 'string', 'max:30', 'regex:/^[0-9+\-\s]+$/'],
            'address' => ['required', 'string', 'max:1000'],
            'card_number' => ['required', 'digits_between:12,19'],
            'card_expiry' => ['required', 'string', 'max:7'],
            'card_cvv' => ['required', 'digits_between:3,4'],
            'promo_code' => ['nullable', 'string', 'max:50'],
        ];
    }

    public function messages(): array
    {
        return [
            'phone.regex' => 'Please enter a valid phone number.',
            'card_number.digits_between' => 'Please enter a valid card number.',
            'card_cvv.digits_between' => 'Please enter a valid CVV.',
        ];
    }
}
