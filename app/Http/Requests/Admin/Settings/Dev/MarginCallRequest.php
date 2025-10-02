<?php

namespace App\Http\Requests\Admin\Settings\Dev;

use App\Rules\NoHtmlInjection;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\RequiredIf;

class MarginCallRequest extends FormRequest
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
            'enabled' => ['nullable', 'array'],
            'enabled.id' => [new RequiredIf($this->enabled), 'integer', 'exists:settings,id'],
            'enabled.value' => [new RequiredIf($this->enabled), 'boolean'],

            'percent' => ['nullable', 'array'],
            'percent.id' => [new RequiredIf($this->percent), 'integer', 'exists:settings,id'],
            'percent.value' => [new RequiredIf($this->percent), 'numeric', 'min:0', 'max:100'],

            'liquidation' => ['nullable', 'array'],
            'liquidation.id' => [new RequiredIf($this->liquidation), 'integer', 'exists:settings,id'],
            'liquidation.value' => [new RequiredIf($this->liquidation), 'numeric', 'min:0', 'max:100'],

            'mail_subject' => ['nullable', 'array'],
            'mail_subject.id' => [new RequiredIf($this->mail_subject), 'integer', 'exists:settings,id'],
            'mail_subject.value' => [new RequiredIf($this->mail_subject), 'string', 'max:100', new NoHtmlInjection],

            'margin_mail' => ['nullable', 'array'],
            'margin_mail.id' => [new RequiredIf($this->margin_mail), 'integer', 'exists:settings,id'],
            'margin_mail.value' => [new RequiredIf($this->margin_mail), 'string', 'max:100', new NoHtmlInjection],
        ];
    }
}
