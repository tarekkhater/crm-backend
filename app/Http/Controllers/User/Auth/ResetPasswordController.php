<?php

namespace App\Http\Controllers\User\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\User\Auth\ResetPasswordRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Tymon\JWTAuth\Facades\JWTAuth;

class ResetPasswordController extends Controller
{
    public $user;
    public function __construct(){
        $this->user = AuthApi();
    }
    public function Reset(ResetPasswordRequest $request){
        try{

            if(Hash::check($request->password,$this->user->password)){
                $this->setMessage(__('app.auth.samepassword'));
                $this->setStatus(422);
            }else{
                $this->user->update([
                    "password" => Hash::make($request->password)
                ]);

                $this->setMessage(__('app.auth.success'));
                $this->setStatus(200);
            }
            
        }catch(\Exception $e){
            $this->setMessage(__('mobile.unathintacted'));
            $this->setStatus(500);
        }

        return $this->SendApiResponse();
    }


    public function change(ResetPasswordRequest $request){
        try{
            $this->user->update([
                "password" => Hash::make($request->password)
            ]);
            JWTAuth::invalidate(JWTAuth::getToken());
            $this->setMessage(__('app.auth.success'));
            $this->setStatus(200);
        }catch(\Exception $e){
            $this->setMessage(__('mobile.unathintacted'));
            $this->setStatus(500);
        }

        return $this->SendApiResponse();
    }


}
