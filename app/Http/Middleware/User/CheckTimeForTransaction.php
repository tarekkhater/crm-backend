<?php
namespace App\Http\Middleware\User;

use Closure;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Models\CurrencyPair;
use Auth;

class CheckTimeForTransaction
{
    public function handle(Request $request, Closure $next)
    {
        $currentTime = Carbon::now('Europe/Warsaw');
        $id = $request->get('symbol');
        $asset = CurrencyPair::where('ex_sym',$id)->first();
        $user = Auth::user('apiUser');
            if ($asset) {
                $startTime = Carbon::parse($asset->open_at);
                $endTime = Carbon::parse($asset->close_at);
            
                $allowedDays = json_decode($asset->days, true);
                $currentDay = strtolower($currentTime->format('l'));
            
                if (in_array($currentDay, array_map('strtolower', $allowedDays))) {
                   
                    // إذا كانت فترة العمل تمتد لليوم التالي
                    if ($startTime->gt($endTime)) {
                        // إذا الوقت الحالي أصغر من وقت الإغلاق، نضيف يوم على وقت الإغلاق
                        if ($currentTime->lt($startTime)) {
                            $startTime = $startTime->copy()->subDay(); // لتوافق اليوم الصحيح
                        } else {
                            $endTime = $endTime->copy()->addDay();
                        }
                    }
                    
                    
                    
                    if ($currentTime->between($startTime, $endTime)) {
                        return $next($request);
                    }
            
                    return response()->json([
                        "message" => "The time is not allowed to perform this operation.",
                        "status"  => 422,
                        "data"    => []
                    ], 422);
                } 
            }

        // if ($asset && $user->allow_trade_after_hours == '0') {
        //     $startTime = Carbon::parse($asset->open_at);
        //     $endTime = Carbon::parse($asset->close_at);

        //     $allowedDays = json_decode($asset->days);
        //     $currentDay = strtolower($currentTime->format('l'));

        //     if (in_array($currentDay, $allowedDays)) {
        //         // الحالة العادية: الوقت بين البداية والنهاية في نفس اليوم
        //         if ($startTime->lt($endTime)) {
        //             if ($currentTime->between($startTime, $endTime)) {
        //                 return $next($request);
        //             }
        //         } else {
        //             if (
        //                 $currentTime->gte($startTime) || 
        //                 $currentTime->lte($endTime)
        //             ) {
        //                 return $next($request);
        //             }
        //         }

        //         return response()->json([
        //             "message" => "The time is not allowed to perform this operation.",
        //             "status"  => 422,
        //             "data"    => []
        //         ], 200);
        //     } 
        // }

        return $next($request);
    }
}
