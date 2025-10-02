<?php

namespace App\Http\Controllers\admin\CRM\Api\Users\Account;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Providers\RouteServiceProvider;

use Carbon\Carbon;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Hash;
use Auth;


class ChangePasswordController extends Controller
{

    public function index(Request $request){
        $request->validate([
            'id'=>"required|numeric|exists:users,id",
            'oldpassword'=>"required|string",
            'newpassword'=>"required|string|min:6",
            'confirm_password'=>"required_with:confirm_password|same:newpassword",
        ]);

        $user = User::find($request->id);
        if(Hash::check($request->confirmpassword, $user->password)){
            $this->setMessage("your new password is same old password ");
        }else{
            $user->password = Hash::make($request->confirmpassword);
            $user->pass= $request->confirmpassword;
            $user->save();
            // $this->setData($user);
            $this->setMessage("success,Change Password");
        }



        return $this->sendApiResonse();
    }
}
