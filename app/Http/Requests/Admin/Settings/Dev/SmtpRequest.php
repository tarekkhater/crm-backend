<?php

namespace App\Http\Requests\Admin\Settings\Dev;

use App\Rules\NoHtmlInjection;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\RequiredIf;

class SmtpRequest extends FormRequest
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
            'host' => ['nullable', 'array'],
            'host.id' => [new RequiredIf($this->host), 'integer', 'exists:settings,id'],
            'host.value' => [new RequiredIf($this->host), 'string', 'max:128', 'active_url', new NoHtmlInjection],

            'port' => ['nullable', 'array'],
            'port.id' => [new RequiredIf($this->port), 'integer', 'exists:settings,id'],
            'port.value' => [new RequiredIf($this->port), 'numeric', 'min:0', 'max:65535', new NoHtmlInjection],

            'encryption' => ['nullable', 'array'],
            'encryption.id' => [new RequiredIf($this->encryption), 'integer', 'exists:settings,id'],
            'encryption.value' => [new RequiredIf($this->encryption), 'string', 'max:32', new NoHtmlInjection],

            'username' => ['nullable', 'array'],
            'username.id' => [new RequiredIf($this->username), 'integer', 'exists:settings,id'],
            'username.value' => [new RequiredIf($this->username), 'string', 'max:64', new NoHtmlInjection],

            'password' => ['nullable', 'array'],
            'password.id' => [new RequiredIf($this->password), 'integer', 'exists:settings,id'],
            'password.value' => [new RequiredIf($this->password), 'string', new NoHtmlInjection],

            'from_address' => ['nullable', 'array'],
            'from_address.id' => [new RequiredIf($this->from_address), 'integer', 'exists:settings,id'],
            'from_address.value' => [new RequiredIf($this->from_address), 'string', 'email', new NoHtmlInjection],
        ];
    }
}
