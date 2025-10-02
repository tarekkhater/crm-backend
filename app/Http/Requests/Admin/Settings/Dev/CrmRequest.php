<?php

namespace App\Http\Requests\Admin\Settings\Dev;

use App\Rules\NoHtmlInjection;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\RequiredIf;

class CrmRequest extends FormRequest
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
            'enable' => ['nullable', 'array'],
            'enable.id' => [new RequiredIf($this->enable), 'integer', 'exists:settings,id'],
            'enable.value' => [new RequiredIf($this->enable), 'boolean'],

            'url' => ['nullable', 'array'],
            'url.id' => [new RequiredIf($this->url), 'integer', 'exists:settings,id'],
            'url.value' => [new RequiredIf($this->url), 'string', 'url', new NoHtmlInjection],

            'access_token' => ['nullable', 'array'],
            'access_token.id' => [new RequiredIf($this->access_token), 'integer', 'exists:settings,id'],
            'access_token.value' => [new RequiredIf($this->access_token), 'string', 'max:64', new NoHtmlInjection],

            'lead_source' => ['nullable', 'array'],
            'lead_source.id' => [new RequiredIf($this->lead_source), 'integer', 'exists:settings,id'],
            'lead_source.value' => [new RequiredIf($this->lead_source), 'string', 'max:64', new NoHtmlInjection],

            'assigned' => ['nullable', 'array'],
            'assigned.id' => [new RequiredIf($this->assigned), 'integer', 'exists:settings,id'],
            'assigned.value' => [new RequiredIf($this->assigned), 'string', new NoHtmlInjection],

            'status' => ['nullable', 'array'],
            'status.id' => [new RequiredIf($this->status), 'integer', 'exists:settings,id'],
            'status.value' => [new RequiredIf($this->status), 'boolean'],
        ];
    }
}
