<?php

namespace App\Http\Requests\Admin\Settings\Dev;

use App\Rules\Base64Image;
use App\Rules\NoHtmlInjection;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\RequiredIf;

class CustomPaymentLinkRequest extends FormRequest
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
            'title' => ['nullable', 'array'],
            'title.id' => [new RequiredIf($this->title), 'integer', 'exists:settings,id'],
            'title.value' => [new RequiredIf($this->title), 'string', 'max:32', new NoHtmlInjection],

            'url' => ['nullable', 'array'],
            'url.id' => [new RequiredIf($this->url), 'integer', 'exists:settings,id'],
            'url.value' => [new RequiredIf($this->url), 'string', 'active_url', 'max:255', new NoHtmlInjection],

            'button_name' => ['nullable', 'array'],
            'button_name.id' => [new RequiredIf($this->button_name), 'integer', 'exists:settings,id'],
            'button_name.value' => [new RequiredIf($this->button_name), 'string', 'max:16', new NoHtmlInjection],

            'image' => ['nullable', 'array'],
            'image.id' => [new RequiredIf($this->image), 'integer', 'exists:settings,id'],
            'image.value' => [new RequiredIf($this->image), 'string', new Base64Image, new NoHtmlInjection],

            'text' => ['nullable', 'array'],
            'text.id' => [new RequiredIf($this->text), 'integer', 'exists:settings,id'],
            'text.value' => ['nullable', 'string', 'max:255', new NoHtmlInjection],
        ];
    }
}
