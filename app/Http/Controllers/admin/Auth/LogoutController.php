<?php

namespace App\Http\Controllers\admin\Auth;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Tymon\JWTAuth\Facades\JWTAuth;

class LogoutController extends Controller
{
    public function logout(Request $request){
        Auth::guard("api")->logout();
        JWTAuth::invalidate(JWTAuth::getToken());
        $this->setStatus(200);
        $this->setMessage(__('app.logout'));
       return $this->sendApiResonse();
    }
}
