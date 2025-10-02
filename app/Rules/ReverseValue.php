<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class ReverseValue implements Rule
{
    protected $field;

    // Constructor to pass the field name to compare with
    public function __construct($field)
    {
        $this->field = $field;
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
        // Get the value of the other field to compare against
        $otherValue = request()->input($this->field);

        // Check if the value is the reverse of the other value
        return $otherValue != $value;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'The :attribute must be the reverse of the ' . $this->field . ' field.';
    }
}
