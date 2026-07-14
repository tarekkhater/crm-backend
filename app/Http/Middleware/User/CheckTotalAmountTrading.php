<?php

namespace App\Http\Middleware\User;

use Closure;
use Illuminate\Http\Request;
use Auth;
use App\Models\ActiveUser;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Contracts\Validation\Validator;
use App\Models\Position;
use App\Services\Users\UserWalletService;

class CheckTotalAmountTrading
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
        $user->load('userInfo');
        $totalAmount = Position::whereUserId($user->id)->whereNull('close_at')->sum('trade_amount');;
        if($totalAmount >= UserWalletService::mainBalance($user->userInfo)){
             $response = [
                "message"   =>"Your Account Total Balance in trade bigger or equal you Balance",
                "status"=>422,
            ];
          return throw new HttpResponseException(response()->json($response, 422));
        }

        return $next($request);
    }
}
