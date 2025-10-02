<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class Base64Image implements Rule
{
    /**
     * The maximum size of the image in bytes.
     * @var int
     */
    protected int $size;

    /**
     * The error message.
     * @var string
     */
    protected string $message = "The :attribute must be a valid Base64.";

    /**
     * Create a new rule instance.
     * @param int $size The maximum size of the image by bytes.
     *
     * @return void
     */
    public function __construct(int $size = 1024 * 1024 * 2)
    {
        $this->size = $size;
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
        // Check if the value is a valid Base64 string
        if (base64_decode($value, true) === false) {
            return false;
        }

        // Decode the Base64 string
        $decoded = base64_decode($value);

        // Check the size of the decoded image
        if (strlen($decoded) > $this->size) {
            $currentSize = $this->size / 1024 / 1024;
            $this->message = "The :attribute must be less than {$currentSize} MB.";
            return false;
        }

        // Check if the decoded string is a valid image
        $finfo = finfo_open();
        $mimeType = finfo_buffer($finfo, $decoded, FILEINFO_MIME_TYPE);
        finfo_close($finfo);

        // List of allowed mime types
        $allowedMimeTypes = ['image/jpeg', 'image/png', 'image/gif'];

        return in_array($mimeType, $allowedMimeTypes);
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return $this->message;
    }
}
