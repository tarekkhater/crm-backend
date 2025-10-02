<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class CheckSettingItemValueType implements Rule
{

    /**
     * Create a new rule instance.
     *
     * @return void
     */
     public $id;
    public function __construct($id) {
        $this->id = $id;
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        $id = $this->id;

        $setting = (string) \App\Models\Setting::find($id)->type;

        switch ($setting) {
            case "0":
                return is_numeric($value);
            case "1":
                return !is_numeric($value);
        };

        return false;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'The data was not valid.';
    }
}
