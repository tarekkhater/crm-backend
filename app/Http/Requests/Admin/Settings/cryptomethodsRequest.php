<?php

namespace App\Http\Requests\Admin\Settings;

use App\Rules\NoHtmlInjection;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Contracts\Validation\Validator;

class cryptomethodsRequest extends FormRequest
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
           'name'=>['required','unique:crypto_payments,name',new NoHtmlInjection],
            'wallet'=>['required','unique:crypto_payments,wallet',new NoHtmlInjection],
            'symbol'=>['required','unique:crypto_payments,symbol',new NoHtmlInjection],
            'barcode'=>['required','file','mimes:jpg,png','max:10240'],
        ];
    }

    public function messages()
    {
        return [

        ];
    }

    protected function failedValidation(Validator $validator) {
        $response = [
            "message"   =>$validator->errors()->first(),
            "status"    =>false,
        ];
        throw new HttpResponseException(response()->json($response, 422));
    }
}
