<?php

namespace App\Http\Requests\User\Auth;

use App\Rules\NoHtmlInjection;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Contracts\Validation\Validator;

class RegisterGoogelRequest extends FormRequest
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
            'given_name' => ['required', 'string', 'max:255',new NoHtmlInjection],
            'family_name' => ['required', 'string', 'max:255',new NoHtmlInjection],
            'email' => ['required', 'email', 'max:255',new NoHtmlInjection],
            'sub' => ['required','numeric','gt:-1',],
            'desk_ref' => ['nullable', 'string', 'max:64', Rule::exists('desks', 'registration_token')],
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
