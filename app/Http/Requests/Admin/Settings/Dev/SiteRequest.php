<?php

namespace App\Http\Requests\Admin\Settings\Dev;

use App\Rules\Base64Image;
use App\Rules\NoHtmlInjection;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\RequiredIf;

class SiteRequest extends FormRequest
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
            'logo' => ['nullable', 'array'],
            'logo.id' => [new RequiredIf($this->logo), 'integer', 'exists:settings,id'],
            'logo.value' => [new RequiredIf($this->logo), new Base64Image],

            'favicon' => ['nullable', 'array'],
            'favicon.id' => [new RequiredIf($this->favicon), 'integer', 'exists:settings,id'],
            'favicon.value' => [new RequiredIf($this->favicon), new Base64Image(1024 * 1024)],

            'name' => ['nullable', 'array'],
            'name.id' => [new RequiredIf($this->name), 'integer', 'exists:settings,id'],
            'name.value' => [new RequiredIf($this->name), 'string', 'max:32', new NoHtmlInjection],

            'email' => ['nullable', 'array'],
            'email.id' => [new RequiredIf($this->email), 'integer', 'exists:settings,id'],
            'email.value' => [new RequiredIf($this->email), 'string', 'email', new NoHtmlInjection],

            'phone' => ['nullable', 'array'],
            'phone.id' => [new RequiredIf($this->phone), 'integer', 'exists:settings,id'],
            'phone.value' => [new RequiredIf($this->phone), 'string', 'numeric'],

            'currency' => ['nullable', 'array'],
            'currency.id' => [new RequiredIf($this->currency), 'integer', 'exists:settings,id'],
            'currency.value' => [new RequiredIf($this->currency), 'string', new NoHtmlInjection],

            'site_info_url' => ['nullable', 'array'],
            'site_info_url.id' => [new RequiredIf($this->site_info_url), 'integer', 'exists:settings,id'],
            'site_info_url.value' => [new RequiredIf($this->site_info_url), 'url', new NoHtmlInjection],

            'privacy_policy' => ['nullable', 'array'],
            'privacy_policy.id' => [new RequiredIf($this->privacy_policy), 'integer', 'exists:settings,id'],
            'privacy_policy.value' => [new RequiredIf($this->privacy_policy), 'url', new NoHtmlInjection],

            'terms' => ['nullable', 'array'],
            'terms.id' => [new RequiredIf($this->terms), 'integer', 'exists:settings,id'],
            'terms.value' => [new RequiredIf($this->terms), 'url', new NoHtmlInjection],

            'primary_color' => ['nullable', 'array'],
            'primary_color.id' => [new RequiredIf($this->primary_color), 'integer', 'exists:settings,id'],
            'primary_color.value' => [new RequiredIf($this->primary_color), 'hex_color'],

            'primary_color_hover' => ['nullable', 'array'],
            'primary_color_hover.id' => [new RequiredIf($this->primary_color_hover), 'integer', 'exists:settings,id'],
            'primary_color_hover.value' => [new RequiredIf($this->primary_color_hover), 'hex_color'],

            'message_new_password' => ['nullable', 'array'],
            'message_new_password.id' => [new RequiredIf($this->message_new_password), 'integer', 'exists:settings,id'],
            'message_new_password.value' => [new RequiredIf($this->message_new_password), 'string', 'max:64', new NoHtmlInjection],

            'ip' => ['nullable', 'array'],
            'ip.id' => [new RequiredIf($this->ip), 'integer', 'exists:settings,id'],
            'ip.value' => [new RequiredIf($this->ip), 'ip', 'max:64', new NoHtmlInjection],
        ];
    }
}
