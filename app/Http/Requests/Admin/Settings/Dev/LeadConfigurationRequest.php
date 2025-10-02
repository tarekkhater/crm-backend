<?php

namespace App\Http\Requests\Admin\Settings\Dev;

use App\Rules\NoHtmlInjection;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\RequiredIf;

class LeadConfigurationRequest extends FormRequest
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
            'webhook_url' => ['nullable', 'array'],
            'webhook_url.id' => [new RequiredIf($this->webhook_url), 'integer', 'exists:settings,id'],
            'webhook_url.value' => [new RequiredIf($this->webhook_url), 'string', 'url', new NoHtmlInjection],

            // 'url' => ['nullable', 'array'],
            // 'url.id' => [new RequiredIf($this->url), 'integer', 'exists:settings,id'],
            // 'url.value' => [new RequiredIf($this->url), 'string', 'url', new NoHtmlInjection],
        ];
    }
}
