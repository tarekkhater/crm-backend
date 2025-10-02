<?php

namespace App\Http\Middleware\User;

use Closure;
use Illuminate\Http\Request;
use Auth;
use App\Models\ActiveUser;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Contracts\Validation\Validator;
class BlockedUser
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

        if($user->block == '1'){
            // Auth::guard('apiUser')->logout();
             $response = [
                "message"   =>"Your Account has Blocked By Support Admin",
                "status"=>401,
            ];
           return throw new HttpResponseException(response()->json($response, 401));
        }

        return $next($request);
    }
}
