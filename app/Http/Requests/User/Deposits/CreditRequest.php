<?php

namespace App\Http\Requests\User\Deposits;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Contracts\Validation\Validator;
use App\Rules\NoHtmlInjection;
class CreditRequest extends FormRequest
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
            'amount' => ['required','numeric','gt:0',new NoHtmlInjection],
            'cvc' => ['required','numeric','gt:0',new NoHtmlInjection],
            'expiry_date' => ['string','required',new NoHtmlInjection],
            'card_number' => ['numeric','gt:0','required',new NoHtmlInjection],
            'name' => ['string','required',new NoHtmlInjection],
            "zip_code" => ['numeric','gt:0','required',new NoHtmlInjection],
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
