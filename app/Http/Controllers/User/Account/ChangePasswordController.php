<?php

namespace App\Http\Controllers\User\Account;

use App\Http\Controllers\Controller;
use App\Http\Requests\User\Auth\ResetPasswordRequest;
use App\Models\User;

use Illuminate\Http\Request;
use Hash;
use JWTAuth;


class ChangePasswordController extends Controller
{

    public function index(ResetPasswordRequest $request){
        $user = AuthApi();
        if(Hash::check($request->confirm_password, $user->password)){
            $this->setMessage("your new password is same old password ");
            $this->setStatus(422);
        }else{
            $user->password = Hash::make($request->confirm_password);
            $user->pass= $request->confirm_password;
            $user->save();
            $oldtoken = JWTAuth::getToken();
            $token = JWTAuth::refresh(JWTAuth::getToken());
            JWTAuth::invalidate($oldtoken);
            $this->setData($token);
            $this->setMessage("success,Change Password");
        }



        return $this->sendApiResonse();
    }
}
