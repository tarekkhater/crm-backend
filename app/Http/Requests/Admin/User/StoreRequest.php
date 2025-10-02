<?php

namespace App\Http\Requests\Admin\User;

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
            'name' => ['required', 'string',  new NoHtmlInjection],
            'surname' => ['required', 'string',  new NoHtmlInjection],
            'email' => ['required', 'string', 'email', 'unique:users,email,NULL,id,deleted_at,NULL', new NoHtmlInjection],
            'country' => ['required', 'numeric',  new NoHtmlInjection],
            'phone' => ['required', 'regex:/[0-9]/',  new NoHtmlInjection],
            'address' => ['required', 'string',  new NoHtmlInjection],
            'type' => ['required', 'numeric', 'in:1,2,9,10'],
            'permanent_address' => ['required', 'string',  new NoHtmlInjection],
            'currency' => ['required', 'string',  new NoHtmlInjection],
            'postal' => ['required', 'numeric',  new NoHtmlInjection],
            'source' => ['nullable', 'integer',  new NoHtmlInjection],
            'status' => ['nullable', 'integer',  new NoHtmlInjection],
            'branch' => ['nullable', 'integer',  new NoHtmlInjection],
            'plan' => ['nullable', 'integer',  new NoHtmlInjection],
            'profit' => ['nullable', 'integer',  new NoHtmlInjection],
            'fee' => ['nullable', 'integer',  new NoHtmlInjection],
        ];
    }


    public function messages()
    {
        return [];
    }

    protected function failedValidation(Validator $validator)
    {
        $response = [
            "message"   => $validator->errors()->first(),
            "status"    => false,
        ];
        throw new HttpResponseException(response()->json($response, 422));
    }
}
