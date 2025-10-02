<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class ChecckImage implements Rule
{

    // Constructor to pass the field name to compare with
    public function __construct()
    {
       
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
       $file = $value;
        $mimeType = $file->getMimeType();
        $realMime = $this->getRealMimeType($file);
        return $realMime == 'image/jpeg' && $realMime == 'image/png' && $realMime == 'image/jpg';
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'Invalid image file.';
    }
    
    private function getRealMimeType($file)
    {
    // Check the magic bytes of the file
    $filePath = $file->getPathname();
    $finfo = finfo_open(FILEINFO_MIME_TYPE); // Open fileinfo resource
    $realMime = finfo_file($finfo, $filePath); // Get the real MIME type
    finfo_close($finfo); // Close resource
    
    return $realMime;
    }

}
