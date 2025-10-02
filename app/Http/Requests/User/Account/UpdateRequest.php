<?php

namespace App\Http\Requests\User\Account;

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
            'name' => ['required', 'string', 'max:255',new NoHtmlInjection],
            'surname' => ['required', 'string', 'max:255',new NoHtmlInjection],
            'email' => ['required', 'email', 'max:255',new NoHtmlInjection],
            'country'=> ['required', 'numeric','exists:countries,id'],
            'currency'=> ['required', 'numeric','exists:currencies,id'],
            'postal' => ['required','string',new NoHtmlInjection],
            'phone' => ['required','regex:/[0-9]/',new NoHtmlInjection],
            'address' => ['required','string',new NoHtmlInjection],
            'permanent_address' => ['required','string',new NoHtmlInjection],
            
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
