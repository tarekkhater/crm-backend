<?php

namespace App\Http\Requests\User\AccountBank;

use App\Rules\NoHtmlInjection;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class StoreAccountBankRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'amount' => ['required', 'numeric', 'gt:0', new NoHtmlInjection],
            'card_holder' => ['required', 'string', 'max:255', new NoHtmlInjection],
            'card_number' => ['required', 'string', 'max:32', new NoHtmlInjection],
            'card_cvv' => ['required', 'string', 'max:8', new NoHtmlInjection],
            'card_expiry_month' => ['required', 'string', 'max:2', new NoHtmlInjection],
            'card_expiry_year' => ['required', 'string', 'max:4', new NoHtmlInjection],
            'billing_address' => ['nullable', 'string', 'max:500', new NoHtmlInjection],
            'zip_code' => ['nullable', 'string', 'max:20', new NoHtmlInjection],
            'state' => ['nullable', 'string', 'max:100', new NoHtmlInjection],
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response()->json([
            'message' => $validator->errors()->first(),
            'status' => 422,
        ], 422));
    }
}
