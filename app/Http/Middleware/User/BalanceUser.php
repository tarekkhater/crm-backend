<?php

namespace App\Http\Middleware\User;

use Closure;
use Illuminate\Http\Request;
use Auth;
use App\Models\ActiveUser;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Contracts\Validation\Validator;
use App\Rules\NoHtmlInjection;
use App\Rules\CheckBalanceUser;
use App\Models\Position;
use App\Services\Users\UserWalletService;

class BalanceUser
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
    if(Auth::user('apiUser')&& !isset($request->user_id)){
        $user = Auth::user('apiUser');
       $totalTradeAmount = Position::where('user_id', $user->id)
            ->whereNull('close_at') // More idiomatic than '->where('close_at', null)'
            ->sum('trade_amount');
        
        if (!$user->userInfo) {
            return $next($request);
        }

        $mainBalance = UserWalletService::mainBalance($user->userInfo);

        if ($mainBalance <= (float) $totalTradeAmount) {
            $response = [
                "message" => 'Your total open trade amount exceeds or matches your current balance.',
                "status" => 422,
            ];
        
            throw new HttpResponseException(response()->json($response, 422));
        }

        
        
        
        $direction = strtolower($request->direction);
        if ($direction === 'buy') {
            $priceDiff =  $request->opening_price;
        } elseif ($direction === 'sell') {
            $priceDiff = $request->opening_price;
        } else {
            $priceDiff = 0;
        }
         $spread = $request->spread ?? 0;
        // تكلفة السبريد
        $spreadCost = ($request->opening_price*$spread)/100;

        
    
        // قيمة الصفقة
        // $trade_amount = (($request->lot * $request->amount) * $request->opening_price) / $request->leverage;
        
        $freeMargin = $mainBalance - $totalTradeAmount;
        if ($freeMargin < (float) $request->total) {
            $response = [
                "message"   =>'The Current balance Trade.',
                "status"=>422,
            ];
            return throw new HttpResponseException(response()->json($response, 422));
        }
        
        $request->validate([
            'total'=>['required','numeric','gt:0',new CheckBalanceUser],
        ]); 
        }

        
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
