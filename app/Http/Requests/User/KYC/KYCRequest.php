<?php

namespace App\Http\Requests\User\KYC;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Contracts\Validation\Validator;

class KYCRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        // 8MB = 8192 KB
        $rule = 'required|image|mimes:jpeg,jpg,png,gif,svg,webp|max:8192';

        return [
            // 'front_id'          => $rule,
            // 'back_id'           => $rule,
            // 'selfie'            => $rule, // انتبه: الاسم في الطلب selfie
            // 'front_credit_card' => $rule,
            // 'back_credit_card'  => $rule,
        ];
    }

    public function messages()
    {
        return [];
    }

    protected function failedValidation(Validator $validator)
    {
        $response = [
            "message" => $validator->errors()->first(),
            "status"  => false,
        ];
        throw new HttpResponseException(response()->json($response, 422));
    }
}
