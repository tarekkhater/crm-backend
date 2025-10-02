<?php

namespace App\Http\Requests\Admin\Asset;

use App\Rules\NoHtmlInjection;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Contracts\Validation\Validator;
class UpdateRequest extends FormRequest
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
            'name' => ['required','string',new NoHtmlInjection],
            'sym' => ['required','string',new NoHtmlInjection],
            'ex_sym' => ['required','string',new NoHtmlInjection],
            'type' => ['required','string'],
            'leverage' => ['required','numeric','gt:0',new NoHtmlInjection],
            'base' => ['required','string',new NoHtmlInjection],
            'com' => ['required','numeric',new NoHtmlInjection],
            'buy_spread' => ['required','numeric',new NoHtmlInjection],
            'sell_spread' => ['required','numeric',new NoHtmlInjection],
            'disabled' =>['required','in:1,0'],
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
