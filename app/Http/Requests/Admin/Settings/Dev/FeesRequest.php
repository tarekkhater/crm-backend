<?php

namespace App\Http\Requests\Admin\Settings\Dev;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\RequiredIf;

class FeesRequest extends FormRequest
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
            'tax' => ['nullable', 'array'],
            'tax.id' => [new RequiredIf($this->tax), 'integer', 'exists:settings,id'],
            'tax.value' => [new RequiredIf($this->tax), 'numeric', 'min:0', 'max:100'],

            'commission' => ['nullable', 'array'],
            'commission.id' => [new RequiredIf($this->commission), 'integer', 'exists:settings,id'],
            'commission.value' => [new RequiredIf($this->commission), 'numeric', 'min:0', 'max:100'],

            'cot' => ['nullable', 'array'],
            'cot.id' => [new RequiredIf($this->cot), 'integer', 'exists:settings,id'],
            'cot.value' => [new RequiredIf($this->cot), 'numeric', 'min:0', 'max:100'],
        ];
    }
}
