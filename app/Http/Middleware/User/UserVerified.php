<?php

namespace App\Http\Middleware\User;

use Closure;
use Illuminate\Http\Request;
use Auth;
use App\Models\ActiveUser;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Support\Facades\Hash;
use App\Mail\Auth\OTPAccountVerified;
use Carbon\Carbon;
use Illuminate\Support\Facades\Mail;
use App\Models\User;
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
            $user->update([
                'email_verified_at' => now(),
            ]);
            //  $code  = generateRandomString(6);
            // if($user->Active){
            //     ActiveUser::where([['user_id',$user->id],['status','0'],['type','2']])->update([
            //         'code' => Hash::make($code),
            //         'type'=>'2'
            //     ]);
            // }else{
            //     $user->Active()->create([
            //         'code' => Hash::make($code),
            //         'status' => '0',
            //         'type'=>'2'
            //     ]);
            // }
            // // Auth::guard('apiUser')->logout();
            //     $users = User::find($user->id);
            //     Mail::to("$request->email")->send(new OTPAccountVerified($users,$code));

            //  $response = [
            //     "message"=>"please Check Email for resend code",
            //     "status"=>401,
            // ];
            // return throw new HttpResponseException(response()->json($response, 401));
       }
        return $next($request);
    }
}
