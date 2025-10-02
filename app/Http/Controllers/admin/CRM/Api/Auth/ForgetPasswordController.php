<?php

namespace App\Http\Controllers\admin\CRM\Api\Auth;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Auth\ForgetPasswordChangeRequest;
use App\Http\Requests\Admin\Auth\ForgetPasswordEmailRequest;
use App\Http\Requests\Admin\Auth\ForgetPasswordRequest;

use App\Models\ForgetPassword;
use App\Http\Resources\CRM\APi\Auth\Login_Admin_Resource as LoginResource;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Mail\Auth\OTPAccountForgetPassword;
use App\Models\Admin;
use Carbon\Carbon;
use Illuminate\Support\Facades\Mail;
use Tymon\JWTAuth\Facades\JWTAuth;

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
    public function ReciveEmail(ForgetPasswordEmailRequest $request) {

        $code=generateRandomString(6);
        $user = Admin::where('email',$request->email)->first();
        $existsuser = ForgetPassword::where('user_id',$user->id)->where('type','1')->first();
        if($existsuser){
            $existsuser->update([
                'code'=>Hash::make($code),
                'type'=>'1',
                'code_timer'=>Carbon::now(),
                'status'=>1,
            ]);
        }else{
            ForgetPassword::create([
                'email'=>$request->email,
                'user_id'=>$user->id,
                'code'=>Hash::make($code),
                'code_timer'=>Carbon::now(),
                'type'=>'1'
            ]);
        }
        Mail::to("$request->email")->send(new OTPAccountForgetPassword($user,$code));
        $this->setMessage("success");
        return $this->sendApiResonse();
    }

    public function CheckCodeForget(ForgetPasswordRequest $request) {
        $user = Admin::whereEmail($request->email)->first();
        $userForget = ForgetPassword::where('user_id',$user->id)->where("status","1")->where('type','1')->first();
        if($userForget){


            $otpCreationTime = Carbon::parse($userForget->code_timer);
            if ($otpCreationTime->diffInMinutes(Carbon::now()) > 5) {
                $this->setStatus(400);
                $this->setMessage("Code has expired.");
                return $this->sendApiResonse();
            }
            if (Hash::check($request->code, $userForget->code)) {
                // return response()->json(['error' => 'Invalid OTP.'], 400);
                $userForget->update([
                    'status'=>'0',
                    'code_timer'=>null,
                    'code_request'=>0,
                    'try_request'=>null,
                ]);
                $token = JWTAuth::fromUser($user);
                $user->token=$token;
                $user->load('roles.role.PermissionRole.Permission');

                $this->setData(new LoginResource($user));
                $this->setMessage("success");
            }else{
                $userForget->code_request +=1;
                $userForget->save();
                if($userForget->code_request >= 5){
                    $userForget->try_request = Carbon::now();
                    $userForget->save();
                }
                $this->setStatus(422);
                $this->setMessage("your Code  not Right");
            }
        }else{
            $this->setStatus(422);
            $this->setMessage("your Code  not Right");
        }
        return $this->sendApiResonse();
    }

    public function changePassword(ForgetPasswordChangeRequest $request) {

        if(password_verify($request->password, Auth::user()->password)){
            $this->setMessage("password is same old password");
        }else{
            $user = AuthApiAdmin();
            $user->password = Hash::make($request->password);
            $user->save();
            $this->setMessage("success to change password");
        }
        return $this->sendApiResonse();
    }












}
