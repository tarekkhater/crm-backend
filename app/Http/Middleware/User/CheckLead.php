<?php

namespace App\Http\Middleware\User;

use Closure;
use Illuminate\Http\Request;
use Auth;
use Illuminate\Http\Exceptions\HttpResponseException;
use App\Models\User;
use Tymon\JWTAuth\Facades\JWTAuth;
class CheckLead
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        
        $request->validate([
            'email'=>['sometimes','email','exists:users,email'],
             "token" => "sometimes|string",
        ]);
        
        if(isset($request->token)){
             $user = JWTAuth::setToken($request->token)->toUser();
        }else{
            $user = User::where('email',$request->input('email'))->first();
        }
        
        if($user->type_id == 1){
            $response = [
                "message"   =>"NOT Have Account ",
                "status"=>422,
            ];
          return throw new HttpResponseException(response()->json($response, 422));
        }
        // $user = Auth::user('apiUser');
        // $user->load('userInfo');
        // if($user->user_info->balance == $amount){
        //     // Auth::guard('apiUser')->logout();
        //      $response = [
        //         "message"   =>"Your Account has Blocked By Support Admin",
        //         "status"=>422,
        //     ];
        //   return throw new HttpResponseException(response()->json($response, 401));
        // }

        return $next($request);
    }
}