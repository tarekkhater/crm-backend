<?php

namespace App\Http\Controllers\User\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Requests\User\Auth\LoginRequest;
use App\Http\Resources\CRM\APi\Auth\LoginResource;
use Illuminate\Support\Facades\Auth;
use Tymon\JWTAuth\Facades\JWTAuth;
use Illuminate\Support\Facades\Cookie;


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
    public function login(LoginRequest $request)
    {
        $token = "";
        if(isset($request->token)){
             $user = JWTAuth::setToken($request->token)->toUser();
             if(!$user){
                  $this->setStatus(422);
                $this->setMessage("success");
                return $this->sendApiResonse();
             }
             $user->no_of_logins = '1';
             $user->save();
             $user->token = $request->token;
             $token =  $request->token;
        }else{
            $cred = $request->only('email','password');
            $token = Auth::guard('apiUser')->attempt($cred);
            if(!$token) {
                $this->setStatus(422);
                $this->setMessage("your Email Or password not Right");
                return $this->sendApiResonse();
            }
            $user = Auth::guard('apiUser')->user();
            $user->no_of_logins = '1';
        $user->save();
            $user->token = $token;
        }
        
        
        
        
        $user->load('identity');
        Cookie::queue("jwt", $token, 60);
        $this->setData(new LoginResource($user));
        $this->setMessage("success");
        
        return $this->sendApiResonse();
    }
    
    public function autoLogin(Request $request)
    {
        $token = $request->query('token');
        try {
            $user = JWTAuth::setToken($token)->toUser();
            $user->no_of_logins = '1';
            $user->save();
            $user->token = $token;
            $user->load('identity');
            Cookie::queue("jwt", $token, 60);
            $this->setData(new LoginResource($user));
            $this->setMessage("success");
            return $this->sendApiResonse();
        } catch (\Exception $e) {
            $this->setStatus(422);
            $this->setMessage("success");
            return $this->sendApiResonse();
        }
    
    }


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
