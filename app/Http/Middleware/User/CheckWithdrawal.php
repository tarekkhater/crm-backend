<?php

namespace App\Http\Middleware\User;

use Closure;
use Illuminate\Http\Request;
use Auth;

use Illuminate\Http\Exceptions\HttpResponseException;

class CheckWithdrawal
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
        if($user->can_withdraw == '0'){
            // Auth::guard('apiUser')->logout();
             $response = [
                "message"   =>"You Are Not Allowed To Withdraw",
                "status"=>422,
            ];
           return throw new HttpResponseException(response()->json($response, 422));
        }

        return $next($request);
    }
}