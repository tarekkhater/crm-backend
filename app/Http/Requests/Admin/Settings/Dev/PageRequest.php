<?php

namespace App\Http\Requests\Admin\Settings\Dev;

use App\Rules\NoHtmlInjection;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\RequiredIf;

class PageRequest extends FormRequest
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
            'login_page' => ['nullable', 'array'],
            'login_page.id' => [new RequiredIf($this->login_page), 'integer', 'exists:settings,id'],
            'login_page.value' => [new RequiredIf($this->login_page), 'string', 'max:32', new NoHtmlInjection],

            'register_page' => ['nullable', 'array'],
            'register_page.id' => [new RequiredIf($this->register_page), 'integer', 'exists:settings,id'],
            'register_page.value' => [new RequiredIf($this->register_page), 'string', 'max:32', new NoHtmlInjection],
        ];
    }
}
