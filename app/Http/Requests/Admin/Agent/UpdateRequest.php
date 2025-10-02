<?php

namespace App\Http\Requests\Admin\Agent;

use App\Rules\NoHtmlInjection;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Contracts\Validation\Validator;

class UpdateRequest extends FormRequest
{
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
           'name' => ['required', 'string', 'max:255',new NoHtmlInjection],
            'surname' => ['required', 'string', 'max:255',new NoHtmlInjection],
            'email' => ['required', 'string', 'email', 'max:255',new NoHtmlInjection],
            'country' => ['required','numeric','exists:countries,id'],
            'phone' => ['required','regex:/[0-9]/',new NoHtmlInjection],
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
