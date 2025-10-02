<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class NoHtmlInjection implements Rule
{
    public function passes($attribute, $value)
    {
        // Check if the value contains any HTML tags
        return strip_tags($value) === $value;
    }

    public function message()
    {
        return 'The :attribute field cannot contain HTML tags.';
    }
}
