<?php

use Illuminate\Support\Facades\Auth;
use App\Models\Admin;
use App\Models\AgentUser;
use App\Models\AssignUserManager;
use App\Models\IBClient;
use App\Models\Message;
use App\Models\UserManager;
use App\Models\User;
use Illuminate\Support\Facades\URL;
use Carbon\Carbon;

if (!function_exists('amt')) {
    function amt($value)
    {
        return optional(auth()->user()->currency)->sign . '' . number_format($value, 2);
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


if (!function_exists('getRealMimeType')) {
    function getRealMimeType($file)
    {

        $filePath = $file->getPathname();
        $finfo = finfo_open(FILEINFO_MIME_TYPE); // Open fileinfo resource
        $realMime = finfo_file($finfo, $filePath); // Get the real MIME type
        finfo_close($finfo); // Close resource

        return $realMime;
    }
}

if (!function_exists('uploadRealImage')) {
    function uploadRealImage($file, $path)
    {
        $fileName = time() . '_' . $file->getClientOriginalName();
        $filePath = $file->storeAs('storage/' . $path, $fileName, 'public');
        return $filePath;
    }
}

if (!function_exists("uploadImage")) {
    function uploadImage($base64String, $path)
    {
        $image = explode('base64,', $base64String);
        $image = end($image);
        $image = str_replace(' ', '+', $image);
        $file =  uniqid() . '.png';

        Storage::disk('public')->put("storage/" . $path . '/' . $file, base64_decode($image));

        return $path . '/' . $file;
    }
}



function check_permission($key)
{
    $roles = explode(',', $key);
    foreach ($roles as $role) {
        $permission = \App\Models\Permission::whereName($key)->first();

        $roles_id = auth()->user()->roles()->get();
        foreach ($roles_id as $id) {
            $permission_id = $id->permissions()->get()->pluck('name')->toarray();

            if (in_array($role, $permission_id)) {
                return  true;
            }
        }
    }

    return false;
}



function check_permission_user($key, $user)
{
    $roles = explode(',', $key);
    foreach ($roles as $role) {
        $permission = \App\Models\Permission::whereName($key)->first();

        $roles_id = $user->roles()->get();

        foreach ($roles_id as $id) {
            $permission_id = $id->permissions()->get()->pluck('name')->toarray();

            if (in_array($role, $permission_id)) {
                return  true;
            }
        }
    }

    return false;
}

if (!function_exists('sendMessage')) {
    function sendMessage($data)
    {
        Message::create($data);
    }
}

if (!function_exists('getUsersIds')) {
    function getUsersIds()
    {
        $ids = [];
        if (auth()->user()->type_id == 3) {
            if(auth()->user()->sub_type_id == 4){
                $idss = Admin::where('desk_id',auth()->user()->desk_id)->pluck('id');  
                $ids = AssignUserManager::whereIn('admin_id', $idss)->pluck('user_id');
            }else{
                $ids = User::select()->pluck('id');
            }
        } else if (auth()->user()->type_id == 5) {
            $ids = User::where('broker_id', auth()->user()->id)->pluck('id');
            // $ids = IBClient::where('ib_id',auth()->user()->id)->pluck('user_id');
        } else if (auth()->user()->type_id == 4) {
            $ids = AssignUserManager::where('admin_id', auth()->user()->id)->pluck('user_id');
        } else if (auth()->user()->type_id == 6) {
            $idss = Admin::where('manager_id',auth()->user()->id)->pluck('id');
            $ids = AssignUserManager::whereIn('admin_id',$idss)->OrWhere('admin_id',auth()->user()->id)->pluck('user_id');
        } else {
            if (auth()->user()->type_id == 7) {
$ids = AssignUserManager::where('admin_id', auth()->user()->id)->pluck('user_id');
} else {
                $ids = AssignUserManager::where('admin_id', auth()->user()->id)->pluck('user_id');
            }
        }
        return  $ids;
    }
}

if (!function_exists('getTeamLeaderIds')) {
    function getTeamLeaderIds()
    {
        if (auth()->user()->type_id == 5) {
            $ids = Admin::where('broker_id', auth()->user()->id)->pluck('id');
        } else {
             if (auth()->user()->type_id == 3 && auth()->user()->sub_type_id == 4) {
                 $ids = Admin::select('id', 'name')->where('desk_id',auth()->user()->desk_id)->pluck('id');
             }else{
                 $ids = Admin::select('id', 'name')->pluck('id');
             }
            
        }
        return  $ids;
    }
}


if (!function_exists('getAgentsIds')) {
    function getAgentsIds()
    {
        $ids = [];
        if (auth()->user()->type_id == 3) {
            if(auth()->user()->sub_type_id == 4){
                $ids = Admin::where('desk_id',auth()->user()->desk_id)->pluck('id');   
            }else{
                $ids = Admin::select()->pluck('id');   
            }
        } else if (auth()->user()->type_id == 5) {
            $idsTeamLeader = Admin::where('broker_id', auth()->user()->id)->whereIn('type_id', [7, 8])->pluck('id');
            $ids = UserManager::where('admin_id', $idsTeamLeader)->where('type', '0')->pluck('user_id');
        } else if (auth()->user()->type_id == 6) {
            $ids = UserManager::where('admin_id', auth()->user()->id)->where('type', '0')->pluck('user_id');
        }
        return  $ids;
    }
}

if (!function_exists("AuthApi")) {
    function AuthApi()
    {
        return Auth::guard("apiUser")->user();
    }
}


if (!function_exists("AuthApiAdmin")) {
    function AuthApiAdmin()
    {
        return Auth::guard("api")->user();
    }
}

if (!function_exists("getDifInDays")) {
    function getDifInDays($start, $end)
    {
        $to = Carbon::createFromFormat('Y-m-d', $end);
        $from = Carbon::createFromFormat('Y-m-d', $start);
        return $to->diffInDays($from);
    }
}



if (!function_exists('generateRandomString')) {
    function generateRandomString($length = 6)
    {
        $characters = '0123456789101112345789748784498848744144546554541545458778789545451qwertyuiopasdfghjklzxcvbnmQWERTYUIOPASDFGHJKLMNBVCXZ';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomString;
    }
}


if (!function_exists('baseUrl')) {
    function baseUrl()
    {
        $base_url = URL::to('/') . '/';
        return $base_url;
    }
}

if (!function_exists('isBase64Image')) {

    /**
     * Check if the value is a valid Base64 image
     * @param mixed $base64
     * @return bool
     */
    function isBase64Image($base64): bool
    {
        // Check if the value is a valid Base64 string
        if (base64_decode($base64, true) === false) {
            return false;
        }

        // Decode the Base64 string
        $decoded = base64_decode($base64);

        // Check if the decoded string is a valid image
        $finfo = finfo_open();
        $mimeType = finfo_buffer($finfo, $decoded, FILEINFO_MIME_TYPE);
        finfo_close($finfo);

        // List of allowed mime types
        $allowedMimeTypes = ['image/jpeg', 'image/png', 'image/gif'];

        return in_array($mimeType, $allowedMimeTypes);
    }
}
