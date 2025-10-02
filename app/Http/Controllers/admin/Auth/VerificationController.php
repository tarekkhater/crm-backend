<?php

namespace App\Http\Controllers\admin\Auth;
use App\Models\Admin;
use App\Models\ActiveUser;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Auth\ResendRequest;
use App\Http\Requests\Admin\Auth\VerificationRequest;
use App\Http\Resources\CRM\APi\Auth\Login_Admin_Resource as LoginResource;
use Tymon\JWTAuth\Facades\JWTAuth;
use App\Mail\Auth\OTPAccountVerified;
use Carbon\Carbon;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Hash;

class VerificationController extends Controller
{
    public function index(VerificationRequest $request){

        $user = Admin::where("email", $request->email)->first();
        $active = ActiveUser::where([['user_id',$user->id],['status','0'],['type','1']])->first();
        if($active){
            $otpCreationTime = Carbon::parse($active->code_timer);
            if ($otpCreationTime->diffInMinutes(Carbon::now()) > 5) {
                $this->setStatus(400);
                $this->setMessage("Code has expired.");
                return $this->sendApiResonse();
            }
            if (Hash::check($request->code, $active->code)) {

            $active->update([
                'status'=>'1'
            ]);
            $user = Admin::find($active->user_id);
            $user->email_verified_at = date('Y-m-d');
            $user->save();
            $user->token = JWTAuth::fromUser($user);
            $this->setData(new LoginResource($user));
            $this->setMessage("success");
        }else{
            $active->code_request +=1;
            $active->save();
            if($active->code_request >= 5){
                    $active->try_request = Carbon::now();
                    $active->save();
                }
            $this->setStatus(422);
            $this->setMessage(__('app.code.wrong'));
        }
        }else{
            $this->setStatus(422);
            $this->setMessage(__('app.code.wrong'));
        }
        return $this->sendApiResonse();
    }


    public function resend(ResendRequest $request){
        try{
                $user = Admin::where("email",$request->email)->first();
                $code  = generateRandomString(6);
                $activeuser = ActiveUser::where("user_id",$user->id)->where('type','1')->first();
                if($activeuser){
                  $user->Active()->update([
                    "code"=>Hash::make($code),
                    "status"=>"0",
                    "type"=>"1",
                    'code_timer'=>Carbon::now(),
                ]);
                }else{
                     $user->Active()->Create([
                    "code"=>Hash::make($code),
                    "status"=>"0",
                    "type"=>"1",
                    'code_timer'=>Carbon::now(),
                ]);
                }

            Mail::to("$request->email")->send(new OTPAccountVerified($user,$code));

            $this->setMessage(__('app.register.sent').$code);
            $this->setStatus(200);
        }catch(\Exception $e){
            $this->setStatus(422);
             $this->setMessage(__('mobile.unathintacted'));
        }
        return $this->sendApiResonse();
    }
}
