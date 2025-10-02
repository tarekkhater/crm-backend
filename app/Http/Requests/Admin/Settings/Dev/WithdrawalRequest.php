<?php

namespace App\Http\Requests\Admin\Settings\Dev;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\RequiredIf;

class WithdrawalRequest extends FormRequest
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
            'crypto_withdraw' => ['nullable', 'array'],
            'crypto_withdraw.id' => [new RequiredIf($this->crypto_withdraw), 'integer', 'exists:settings,id'],
            'crypto_withdraw.value' => [new RequiredIf($this->crypto_withdraw), 'boolean'],

            'wire_withdraw' => ['nullable', 'array'],
            'wire_withdraw.id' => [new RequiredIf($this->wire_withdraw), 'integer', 'exists:settings,id'],
            'wire_withdraw.value' => [new RequiredIf($this->wire_withdraw), 'boolean'],

            'paypal_withdraw' => ['nullable', 'array'],
            'paypal_withdraw.id' => [new RequiredIf($this->paypal_withdraw), 'integer', 'exists:settings,id'],
            'paypal_withdraw.value' => [new RequiredIf($this->paypal_withdraw), 'boolean'],

            'withdrawal_request' => ['nullable', 'array'],
            'withdrawal_request.id' => [new RequiredIf($this->withdrawal_request), 'integer', 'exists:settings,id'],
            'withdrawal_request.value' => [new RequiredIf($this->withdrawal_request), 'boolean'],
        ];
    }
}
