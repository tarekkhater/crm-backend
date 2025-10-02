<?php

namespace App\Http\Requests;

use App\Rules\CheckSettingItemValueType;
use App\Rules\NoHtmlInjection;
use Illuminate\Foundation\Http\FormRequest;

class UpdateDevSettingsRequest extends FormRequest
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
            'data' => "required|array",
            'data.*.id' => "required|integer|exists:settings,id",
            'data.*.value' => ["required", new NoHtmlInjection],
        ];
    }
}
