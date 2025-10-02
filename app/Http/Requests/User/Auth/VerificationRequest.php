<?php

namespace App\Http\Requests\User\Auth;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Contracts\Validation\Validator;
use App\Rules\NoHtmlInjection;

class VerificationRequest extends FormRequest
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
            "email"=>["required","email","exists:users,email,deleted_at,NULL",new NoHtmlInjection],
            "code"=>["required","string","min:6",new NoHtmlInjection],
        ];
    }

    public function messages(){
        return [
            'email.required'=>__("mobile.phone.required"),
            'email.string'=>__("mobile.phone.string"),
            'email.min'=>__("mobile.phone.min"),
            'email.exists'=>__("mobile.phone.exists"),
            'code.min'=>__("mobile.code.min"),
        ];
    }

      protected function failedValidation(Validator $validator) {
        $response = [
            "message"   =>$validator->errors()->first(),
            "status"    =>422,
        ];
        throw new HttpResponseException(response()->json($response, 422));
    }
}
