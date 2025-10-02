<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;
use App\Models\User;
use App\Models\ActiveUser;
class verifiedUserAfterAnyThing implements Rule
{
    protected $field;

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
        // Get the value of the other field to compare against
        $otherValue = $value;

        // Check if the value is the reverse of the other value
        $user = User::where("email", $otherValue)->first();
        if($user){
             $active = ActiveUser::where([['user_id',$user->id],['status','0'],['type','2']])->first();
            return !isset($active) && $user->email_verified_at != NULL;
        }
       
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'you must verified your account befor make anything.';
    }
}
