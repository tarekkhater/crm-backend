<?php

namespace App\Http\Requests\User\Account;


use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Contracts\Validation\Validator;
use App\Rules\ChecckImage;

class UploadImageRequest extends FormRequest
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
            // 'file' => ['required','image','mimes:jpeg,png,jpg','max:1024'],
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
