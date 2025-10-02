<?php

namespace App\Http\Requests\User\Auth;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Contracts\Validation\Validator;
use App\Rules\NoHtmlInjection;
use App\Rules\verifiedUserAfterAnyThing;

class ForgetPasswordEmailRequest extends FormRequest
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
            "email"=>["required","email","exists:users,email,deleted_at,NULL",new NoHtmlInjection,new verifiedUserAfterAnyThing],
        ];
    }

    public function messages(){
        return [
            'email.exists'=>"check your mail",
        ];
    }

      protected function failedValidation(Validator $validator) {
          if($validator->errors()->first() == "check your mail"){
              $response = [
                    "message"   =>$validator->errors()->first(),
                    "status"    =>200,
                ];
                 throw new HttpResponseException(response()->json($response, 200));
                
          }else{
             $response = [
            "message"   =>$validator->errors()->first(),
            "status"    =>422,
        ]; 
         throw new HttpResponseException(response()->json($response, 422));
          }
        
       
    }
}
