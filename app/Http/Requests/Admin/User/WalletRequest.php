<?php

namespace App\Http\Requests\Admin\User;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Contracts\Validation\Validator;
use App\Rules\ReverseValue;
class WalletRequest extends FormRequest
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
           'id'=>['required','numeric','exists:users,id'],
           'from'=>['required','in:1,2', new ReverseValue('to')],
            'to'=>['required','in:1,2', new ReverseValue('from')],
            'amount'=>"required|numeric|gt:0|min:1"
        ];
    }

    public function messages(){
        return [

        ];
    }

      protected function failedValidation(Validator $validator) {
        $response = [
            "message"   =>$validator->errors()->first(),
            "status"    =>422,
        ];
        throw new HttpResponseException(response()->json($response, 422));
    }
}
