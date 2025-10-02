<?php

namespace App\Http\Requests\User\Auth;


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
            "email" => ["required", "email", "exists:users,email,deleted_at,NULL", new NoHtmlInjection],
            "password" => "required|string|min:8",
        ];
    }

    public function messages()
    {
        return [
            'email.required' => __("app.email.required"),
            'email.email' => __("app.email.string"),
            'email.exists' => __("auth.exists"),
            'password.required' => __("app.password.required"),
            'password.string' => __("app.password.string"),
            'password.min' => __("app.password.min"),
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
