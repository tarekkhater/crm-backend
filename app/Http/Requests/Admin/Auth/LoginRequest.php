<?php

namespace App\Http\Requests\Admin\Auth;


use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Contracts\Validation\Validator;
use App\Rules\NoHtmlInjection;

class LoginRequest extends FormRequest
{

    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return True;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            "email" => ["required", "email", "exists:admins,email,deleted_at,NULL", new NoHtmlInjection],
            "password" => "required|string|min:8",

        ];
    }

    public function messages()
    {
        return [
            'email.exists' => 'Your email or password not right'
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        $response = [
            "message"   => $validator->errors()->first(),
            "status"    => 422,
        ];
        throw new HttpResponseException(response()->json($response, 422));
    }
}
