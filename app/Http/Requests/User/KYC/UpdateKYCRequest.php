<?php

namespace App\Http\Requests\User\KYC;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Contracts\Validation\Validator;

class UpdateKYCRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'name' => ['required','in:front_id,back_id,selfy,por,front_credit_card,back_credit_card'],
            // 8MB
            'file' => 'required|mimetypes:image/jpeg,image/png,image/gif,image/svg+xml,image/webp|max:8192',
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
