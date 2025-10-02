<?php

namespace App\Http\Middleware\User;

use Closure;
use Illuminate\Http\Request;
use Auth;
use App\Models\ActiveUser;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Contracts\Validation\Validator;
use App\Rules\NoHtmlInjection;
use App\Rules\CheckMoneyUser;
class MoneyUser
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
            'total'=>['required','numeric','gt:0',new CheckMoneyUser],
        ]);
        
        // $amount = $request->input('amount'); // Check body, then query string

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
