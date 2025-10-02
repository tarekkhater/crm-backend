<?php

namespace App\Http\Middleware\Admin;

use Closure;
use Illuminate\Http\Request;
use Auth;
use App\Models\ActiveUser;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Contracts\Validation\Validator;
use Hash;
class AdminVerified
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
        $user = Auth::user('api');
        $user->load(['Active']);
        $code  = generateRandomString(6);
        if($user->email_verified_at == null){
            if($user->Active){
                ActiveUser::where([['user_id',$user->id],['status','0'],['type','1']])->update([
                    'code' => Hash::make($code),
                ]);
            }else{
                $user->Active()->create([
                    'code' => Hash::make($code),
                    'status' => '0',
                    'type'=>'1'
                ]);
            }
            // Auth::guard('apiUser')->logout();
             $response = [
                "message"=>"please Active Your Account",
                "status"=>401,
            ];
            return throw new HttpResponseException(response()->json($response, 401));
       }
        return $next($request);
    }
}
