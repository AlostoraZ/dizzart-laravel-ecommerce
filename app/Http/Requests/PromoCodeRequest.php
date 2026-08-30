<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class PromoCodeRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $promoCodeId = $this->route('promo_code')?->id;

        return [
            'code' => [
                'required',
                'string',
                'max:50',
                'alpha_dash',
                Rule::unique('promo_codes', 'code')->ignore($promoCodeId),
            ],
            'discount_percentage' => ['required', 'integer', 'min:1', 'max:100'],
            'max_uses' => ['required', 'integer', 'min:1'],
            'expires_at' => ['nullable', 'date', 'after:now'],
        ];
    }
}
