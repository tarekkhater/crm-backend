<?php

namespace App\Http\Middleware\Admin;

use Closure;
use Illuminate\Http\Request;
use Auth;
use App\Models\Admin;
use App\Models\ForgetPassword;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Contracts\Validation\Validator;
use Carbon\Carbon;
class CheckOtpEmail
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
       
        $email = $request->input('email'); // Check body, then query string

       $request->validate([
            'email'=>"required|email|exists:admins,email,deleted_at,NULL",
            'email'=>"required|email|exists:forget_passwords,email",
        ]);
        
        
        $user = Admin::where('email',"$request->email")->first();
        $existsuser = ForgetPassword::where('user_id',$user->id)->whereStatus('1')->where('type','1')->first();
      
           if($existsuser && $existsuser->code_request >= 5){
            if(!$this->calculateResidual($existsuser,$existsuser->try_request)){
                return response()->json([
                    "message"   =>"You cannot send now after the specified period has expired.",
                    "status"    =>400,
                    'data' => $this->calculateResidualMinut($existsuser->try_request)], 400);
            }else{
                return $next($request);
            }
            
        
        }
        
        return $next($request);
    }
    
    
    public function calculateResidual($existsuser,$data)
{
    // Example 'created_at' timestamp (replace with your actual created_at timestamp)
    $createdAt = Carbon::parse($data); // This would come from your database, e.g. $user->created_at

    // Get the current time (now)
    $now = Carbon::now();

    // Get the difference between 'created_at' and now in minutes
    $diffInMinutes = $createdAt->diffInMinutes($now);

    if($diffInMinutes >= 5){
        $existsuser->code_request = 0;
        $existsuser->try_request = null;
        $existsuser->save();
        return true;
    }
    return false;
}

    public function calculateResidualMinut($data)
    {
        // Example 'created_at' timestamp (replace with your actual created_at timestamp)
        $createdAt = Carbon::parse($data); // This would come from your database, e.g. $user->created_at
    
        // Get the current time (now)
        $now = Carbon::now();
    
        // Get the difference between 'created_at' and now in minutes
        $diffInMinutes = $createdAt->diff($now)->format('%i.%s');
        return $diffInMinutes;
    }
}
