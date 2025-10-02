<?php

namespace App\Http\Requests\User\Withdrawal;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Contracts\Validation\Validator;
use App\Rules\NoHtmlInjection;

class BankRequest extends FormRequest
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
            'amount' => ['required','string',new NoHtmlInjection],
            'firstName'=>['required','string',new NoHtmlInjection],
            // 'bank_address'=>['required','string',new NoHtmlInjection],
            'country'=>['required','string',new NoHtmlInjection],
            'currency'=>['required','string',new NoHtmlInjection],
            'iban'=>['required','string',new NoHtmlInjection],
            // 'amount' => ['required','numeric','gt:0',new NoHtmlInjection],
            // 'account_label'=>['required','string',new NoHtmlInjection],
            // 'bank_name'=>['required','string',new NoHtmlInjection],
            // 'bank_branch'=>['required','string',new NoHtmlInjection],
            // 'account_name'=>['required','string',new NoHtmlInjection],
            // 'bank_address'=>['required','string',new NoHtmlInjection],
            // 'bank_country'=>['required','string',new NoHtmlInjection],
            // 'bank_currency'=>['required','string',new NoHtmlInjection],
            // 'account_number'=>['required','string',new NoHtmlInjection],
            // 'iban_number'=>['required','string',new NoHtmlInjection],
            // 'sort_code'=>['required','numeric','gt:0',new NoHtmlInjection],
            // 'swift_code'=>['required','string',new NoHtmlInjection],
        ];
    }

    public function messages(){
        return [

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
