<?php

namespace App\Http\Middleware\User;

use Closure;
use Illuminate\Http\Request;
use Auth;
use App\Models\ActiveUser;
use App\Models\Setting;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Contracts\Validation\Validator;
use Carbon\Carbon;
use App\Models\CurrencyPair;
use DB;
class CheckTrade
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
        // $checkTTrade = Setting::where('id',28)->first()->value;
        // if($user->allow_trade == '0' && $checkTTrade == '1'){
        //     // Auth::guard('apiUser')->logout();
        //      $response = [
        //         "message"   =>"You Are Not Allowed To Trade",
        //         "status"=>422,
        //     ];
        //   return throw new HttpResponseException(response()->json($response, 422));
        // }
        
        // $todayTradeCount = DB::table('trades')->where('user_id',$user->id)
        //     ->whereDate('created_at', now()->toDateString())
        //     ->count();
        // if($user->userInfo->plan_id!=null && $user->userInfo->plan_id > 0){
        //     $plan = \App\Models\Plan::find($user->userInfo->plan_id);
        //     $features = $plan->trade_count;
        //     if($todayTradeCount > 0  && $todayTradeCount>=$features){
        //          $response = [
        //             "message"   =>"You have reached your trade limit for today.",
        //             "status"=>422,
        //         ];
        //       return throw new HttpResponseException(response()->json($response, 422));
        //     }
        //     if($request->total < $plan->trade_qty){
        //          $response = [
        //             "message"   =>'Your request total is less than the allowed trade quantity in your plan.',
        //             "status"=>422,
        //         ];
        //       return throw new HttpResponseException(response()->json($response, 422));
        //     }
        // }else{
        //     $response = [
        //             "message"   =>"You do not have a subscription plan. Please choose one to start trading.",
        //             "status"=>422,
        //         ];
        //       return throw new HttpResponseException(response()->json($response, 422)); 
        // }
        
            
        // $currentTime = Carbon::now();
        // $id = $request->get('coinId');
        // $asset = CurrencyPair::where('id',$id)->first();
        // if($asset &&  $user->allow_trade_after_hours== '0'){
        //     $startTime = Carbon::parse($asset->open_at!=null?"$asset->open_at":'12:00');
        //     $endTime = Carbon::parse($asset->close_at!=null?"$asset->close_at":'12:00');
        //     $allowedDays = json_decode($asset->days);
        //     $currentDay = $currentTime->format('l'); 
        //     if (in_array(strtolower($currentDay), $allowedDays)) {
               
        //         if ($currentTime->between($startTime, $endTime)) {
        //             return $next($request); 
        //         } else {
        //              return response()->json([
        //                 "message"   =>'الوقت غير مسموح لإجراء هذه العملية.',
        //                 "status"    =>422,
        //                 'data' => []], 200);
        //         }
        //     } else {
        //         return response()->json([
        //                 "message"   =>'اليوم غير مسموح لإجراء هذه العملية.',
        //                 "status"    =>422,
        //                 'data' => []], 200);
                
        //     }
         
        // }

        return $next($request);
    }
    
 
}