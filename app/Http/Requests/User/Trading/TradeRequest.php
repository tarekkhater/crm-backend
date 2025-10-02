<?php

namespace App\Http\Requests\User\Trading;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Contracts\Validation\Validator;
class TradeRequest extends FormRequest
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
            'amount' => 'required|numeric|gt:-1',
            'coinId' => 'required|numeric|exists:currency_pairs,id',
            'type' => 'required|in:Buy,Sell',
            'is_take_profit' => 'required|numeric|in:0,1',
            'is_stop_loss' => 'required|numeric|in:0,1',
            'is_pending_order' => 'required|numeric|in:0,1',
            'auto_close' => 'required|numeric|in:0,1',
            'pending_order' => 'required|numeric|gt:-1',
            'stop_loss' => 'required|numeric',
            'take_profit' => 'required|gt:-1',
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
