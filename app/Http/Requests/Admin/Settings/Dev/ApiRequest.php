<?php

namespace App\Http\Requests\Admin\Settings\Dev;

use App\Rules\NoHtmlInjection;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\RequiredIf;

class ApiRequest extends FormRequest
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
            'crypto_api' => ['nullable', 'array'],
            'crypto_api.id' => [new RequiredIf($this->crypto_api), 'integer', 'exists:settings,id'],
            'crypto_api.value' => [new RequiredIf($this->crypto_api), 'boolean'],

            'stock_api' => ['nullable', 'array'],
            'stock_api.id' => [new RequiredIf($this->stock_api), 'integer', 'exists:settings,id'],
            'stock_api.value' => [new RequiredIf($this->stock_api), 'string', 'max:64', new NoHtmlInjection],

            'indices_api' => ['nullable', 'array'],
            'indices_api.id' => [new RequiredIf($this->indices_api), 'integer', 'exists:settings,id'],
            'indices_api.value' => [new RequiredIf($this->indices_api), 'boolean'],

            'api_interval' => ['nullable', 'array'],
            'api_interval.id' => [new RequiredIf($this->api_interval), 'integer', 'exists:settings,id'],
            'api_interval.value' => [new RequiredIf($this->api_interval), 'boolean'],

            'crypto_provider' => ['nullable', 'array'],
            'crypto_provider.id' => [new RequiredIf($this->crypto_provider), 'integer', 'exists:settings,id'],
            'crypto_provider.value' => [new RequiredIf($this->crypto_provider), 'string', 'max:64', new NoHtmlInjection],

            'stock_provider' => ['nullable', 'array'],
            'stock_provider.id' => [new RequiredIf($this->stock_provider), 'integer', 'exists:settings,id'],
            'stock_provider.value' => [new RequiredIf($this->stock_provider), 'string', 'max:64', new NoHtmlInjection],

            'com_provider' => ['nullable', 'array'],
            'com_provider.id' => [new RequiredIf($this->com_provider), 'integer', 'exists:settings,id'],
            'com_provider.value' => [new RequiredIf($this->com_provider), 'string', 'max:64', new NoHtmlInjection],

            'indices_provider' => ['nullable', 'array'],
            'indices_provider.id' => [new RequiredIf($this->indices_provider), 'integer', 'exists:settings,id'],
            'indices_provider.value' => [new RequiredIf($this->indices_provider), 'string', 'max:64', new NoHtmlInjection],

            'forex_provider' => ['nullable', 'array'],
            'forex_provider.id' => [new RequiredIf($this->forex_provider), 'integer', 'exists:settings,id'],
            'forex_provider.value' => [new RequiredIf($this->forex_provider), 'string', 'max:64', new NoHtmlInjection],

            'com_api' => ['nullable', 'array'],
            'com_api.id' => [new RequiredIf($this->com_api), 'integer', 'exists:settings,id'],
            'com_api.value' => [new RequiredIf($this->com_api), 'string', 'max:64', new NoHtmlInjection],

            'forex_api' => ['nullable', 'array'],
            'forex_api.id' => [new RequiredIf($this->forex_api), 'integer', 'exists:settings,id'],
            'forex_api.value' => [new RequiredIf($this->forex_api), 'string', 'max:64', new NoHtmlInjection],

            'disable_api' => ['nullable', 'array'],
            'disable_api.id' => [new RequiredIf($this->disable_api), 'integer', 'exists:settings,id'],
            'disable_api.value' => [new RequiredIf($this->disable_api), 'boolean'],
        ];
    }
}
