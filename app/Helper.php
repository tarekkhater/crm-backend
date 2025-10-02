<?php

use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Mail\NewUser;



if (!function_exists('amt')) {
    function amt($value){
        return optional(auth()->user()->currency)->sign.''.number_format($value,2);
    }
}


if (!function_exists('convertFloat')) {
    function convertFloat($floatAsString)
    {
        $norm = strval(floatval($floatAsString));

        if (($e = strrchr($norm, 'E')) === false) {
            return $norm;
        }

        return number_format($norm, -intval(substr($e, 1)));
    }
}
function check_permission($key){
    $roles=explode(',',$key);
    foreach ($roles as $role){
$permission=\App\Models\Permission::whereName($key)->first();

        $roles_id=auth()->user()->roles()->get();
        foreach ($roles_id as $id){
            $permission_id=$id->permissions()->get()->pluck('name')->toarray();

          if(in_array($role,$permission_id)){
              return  true;
          }
        }

    }

    return false;


}
function check_permission_user($key,$user){
    $roles=explode(',',$key);
    foreach ($roles as $role){
$permission=\App\Models\Permission::whereName($key)->first();

        $roles_id=$user->roles()->get();

        foreach ($roles_id as $id){
            $permission_id=$id->permissions()->get()->pluck('name')->toarray();

          if(in_array($role,$permission_id)){
              return  true;
          }
        }

    }

    return false;


}



function sendEmail(Request $request)
{
    // dd("test");
   
    $request->validate([
        'email' => 'required|email',
        'name' => 'required|string',
        'message' => 'required|string',
    ]);

    $data = [
        'name' => $request->input('name'),
        'message' => $request->input('message'),
    ];

    try {
        // Send email to the specified address
        Mail::to($request->input('email'))->send(new NewUser($data));
        return response()->json(['message' => 'Email sent successfully!'], 200);
    } catch (\Exception $e) {
        return response()->json(['error' => 'Failed to send email', 'message' => $e->getMessage()], 500);
    }
}
