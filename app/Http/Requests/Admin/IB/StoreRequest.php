<?php

namespace App\Http\Requests\Admin\IB;

use App\Rules\NoHtmlInjection;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Contracts\Validation\Validator;

class StoreRequest extends FormRequest
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
            'email' => ['required', 'string', 'email', 'max:255', 'unique:admins,email,NULL,id,deleted_at,NULL',new NoHtmlInjection],
            'country_id' => ['required','numeric','exists:countries,id'],
            'phone' => ['required','regex:/[0-9]/',new NoHtmlInjection],
            'password'=>[
                'required',
                'string',
                'min:8', // Minimum length
                'regex:/[A-Z]/', // At least one uppercase letter
                'regex:/[a-z]/', // At least one lowercase letter
                'regex:/[0-9]/', // At least one number
                'regex:/[@$!%*?&]/', // At least one special character
             ], // At least one special character,
            'confirm_password'=>'required|same:password',
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
