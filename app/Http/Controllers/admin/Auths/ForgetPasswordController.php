<?php

namespace App\Http\Controllers\admin\Auth;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use App\Models\ForgetPassword;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Resources\CRM\APi\Auth\Login_Admin_Resource;
use Auth;
use JWTAuth;
use Hash;

class ForgetPasswordController extends Controller
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
    public function ReciveEmail(Request $request){

        $request->validate([
            'email'=>'required|email|exists:admins,email'
        ]);
        
         $code = generateRandomString(6);
        $user = ForgetPassword::whereEmail($request->email)->where('type','1')->first();
        if($user){
            $user->update([
                'code'=>Hash::make($code),
                'status'=>1,
            ]);
        }else{
            $user = Admin::whereEmail($request->email)->first();
            ForgetPassword::create([
                'email'=>$request->email,
                'user_id'=>$user->id,
                'code'=>Hash::make($code)
            ]);
        }
        Mail::to("$request->email")->send(new OTPAccountForgetPassword($user,$code));
        $this->setMessage("success");
        return $this->sendApiResonse();
    }

    public function CheckCodeForget(Request $request){

        $request->validate([
            'email'=>'required|email|exists:forget_passwords,email'
        ]);
        $user = Admin::whereEmail($request->email)->first();
            $userForget = ForgetPassword::where([
                ['email',$request->email],
                ['user_id',$user->id],
                ['code',$request->code],
                ['type','1'],
                ['status',1],
            ])->first();

        if($userForget){
            $userForget->update([
                'status'=>'0'
            ]);
            $token = JWtAuth::fromUser($user);
            $user->token=$token;
            $user->load('roles.role.PermissionRole.Permission');

            $this->setData(new Login_Admin_Resource($user));
            $this->setMessage("success");
        }else{
            $this->setStatus(422);
            $this->setMessage("your Code  not Right");
        }
        return $this->sendApiResonse();
    }

    public function changePassword(Request $request){
        $request->validate([
            'password'=>'required|string|min:6',
            'confirm_password'=>'required|string|min:6|same:password',
        ]);

        if(password_verify($request->password, Auth::user()->password)){
            $this->setMessage("password is same old password");
        }else{
            $user = auth()->user();
            $user->update([
                'password' => Hash::make($request->password)
            ]);
            $this->setMessage("success to change password");
        }
        return $this->sendApiResonse();
    }












}
