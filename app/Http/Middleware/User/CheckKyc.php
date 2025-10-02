<?php

namespace App\Http\Middleware\User;

use Closure;
use Illuminate\Http\Request;
use Auth;
use App\Models\ActiveUser;
use App\Models\Setting;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Contracts\Validation\Validator;
class CheckKyc
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
        // $user->load('identity');
        // $checkKYc = Setting::where('id',25)->first()->value;
        
        
        // if($checkKYc == '1'){
        //     if(count($user->identity) == 0){
        //     // Auth::guard('apiUser')->logout();
        //      $response = [
        //         "message"   =>"Please Upload Your Kyc",
        //         "status"=>422,
        //     ];
        //         return throw new HttpResponseException(response()->json($response, 422));
        //     }

        //     if($user->identity[0]->statuts == '0'){
        //         // Auth::guard('apiUser')->logout();
        //          $response = [
        //             "message"   =>"Your Kyc is Under Review",
        //             "status"=>422,
        //         ];
        //       return throw new HttpResponseException(response()->json($response, 422));
        //     }

        //     if($user->identity[0]->statuts == '2'){
        //         // Auth::guard('apiUser')->logout();
        //          $response = [
        //             "message"   =>"Your Kyc is Rejected",
        //             "status"=>422,
        //         ];
        //       return throw new HttpResponseException(response()->json($response, 422));
        //     }
        // }

        return $next($request);
    }
}