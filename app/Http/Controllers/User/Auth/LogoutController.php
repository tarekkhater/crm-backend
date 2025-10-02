<?php

namespace App\Http\Controllers\User\Auth;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Tymon\JWTAuth\Facades\JWTAuth;

class LogoutController extends Controller
{
    public function logout(Request $request){
       
        JWTAuth::invalidate(JWTAuth::getToken());
         Auth::guard("apiUser")->logout();
        $this->setStatus(200);
         $user->no_of_logins = '0';
        $user->save();
        $this->setMessage(__('app.logout'));
        return $this->sendApiResonse();
    }
}
