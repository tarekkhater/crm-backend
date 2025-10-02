<?php

namespace App\Http\Requests\User\Plan;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Contracts\Validation\Validator;
use App\Rules\NoHtmlInjection;
class PlanRequest extends FormRequest
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
        // NoHtmlInjection
        return [
            // 'plan_id' => 'required|numeric|exists:plans,id',
            // 'unit' => ['required'],
            // 'amount' => ['required','numeric','bg:1','min:1'],
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
