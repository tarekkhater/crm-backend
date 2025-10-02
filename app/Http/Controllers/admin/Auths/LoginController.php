<?php

namespace App\Http\Controllers\admin\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Providers\RouteServiceProvider;
use App\Models\Admin;
use Carbon\Carbon;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use App\Http\Requests\CRM\APi\Admin\Auth\LoginRequest;
use App\Http\Resources\CRM\APi\Auth\Login_Admin_Resource;
use Illuminate\Support\Facades\Auth;
use Tymon\JWTAuth\Facades\JWTAuth;
use Illuminate\Support\Facades\Hash;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */
    public function login(LoginRequest $request){
        $cred = $request->only('email','password');
        $token = Auth::guard('api')->attempt($cred);
        if(!$token){
            $this->setStatus(422);
            $this->setMessage("your Email Or password not Right");
            return $this->sendApiResonse();
        }

        $user = Auth::guard('api')->user();
        $user->token=$token;
        // $user->load('roles.role.PermissionRole.Permission');

        $user->load('roles.role.PermissionRole.Permission');
        // $this->setData(new LoginResource($user));
    //   if($user->email_verified_at == null){
    //         return $this->CheckActiveAccount($user);
    //   }

    //   if($user->status == '0'){
    //     return $this->CheckBlockAccount($user);
    //   }

       $this->setData(new Login_Admin_Resource($user));
        $this->setMessage("success");
        return $this->sendApiResonse();
    }




    // public function CheckActiveAccount($user){
    //     $this->setMessage("please Active Your Account");
    //     $this->setStatus(401);
    //     return $this->sendApiResonse();
    // }

    // public function CheckBlockAccount($user){
    //     $this->setMessage("Your Account has Blocked By Support Admin");
    //     $this->setStatus(401);
    //     return $this->sendApiResonse();
    // }



    // public function CheckCode(Request $request){

    //     // $request->validate([
    //     //     'email'=>'required|email|exists:forget_passwords,email'
    //     // ]);
    //     $user = Admin::whereEmail($request->email)->first();


    //     if($request->code == "1234"){
    //         $user->update([
    //             'email_verified_at'=>new Carbon(),
    //         ]);
    //         $token = JWtAuth::fromUser($user);
    //         $user->token=$token;
    //         $user->load('roles.role.PermissionRole.Permission');
    //         $this->setData(new Login_Admin_Resource($user));
    //         $this->setMessage("success");
    //     }else{
    //         $this->setStatus(422);
    //         $this->setMessage("your Code  not Right");
    //     }
    //     return $this->sendApiResonse();
    // }


public function CheckToken(Request $request)
{
    try {
        $token = JWTAuth::getToken();
        $status = JWTAuth::checkOrFail($token);
        $this->setData(['status'=>200]);
        $this->setMessage("success");
    } catch (\Tymon\JWTAuth\Exceptions\TokenExpiredException $e) {
            $this->setStatus(401);
            $this->setData(['status'=>401]);
            $this->setMessage("Token expired");
    } catch (\Tymon\JWTAuth\Exceptions\JWTException $e) {
        $this->setStatus(401);
        $this->setData(['status'=>401]);
            $this->setMessage("Invalid token");
    }
    return $this->sendApiResonse();

}






}
