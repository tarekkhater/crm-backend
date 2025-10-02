<?php

namespace App\Http\Middleware\User;

use Closure;
use Illuminate\Http\Request;
use Auth;
use App\Models\ActiveUser;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Contracts\Validation\Validator;
class UserVerified
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
        $user = Auth::user('apiUser');
        $user->load(['Active']);
        if($user->email_verified_at == null){
            if($user->Active){
                ActiveUser::where([['user_id',$user->id],['status','0']])->update([
                    'code' => '1234',
                ]);
            }else{
                $user->Active()->create([
                    'code' => '1234',
                    'status' => '0',
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
