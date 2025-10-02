<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Providers\RouteServiceProvider;

use Carbon\Carbon;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Auth;


class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
//    protected $redirectTo = RouteServiceProvider::HOME;


    public function showLoginForm(Request $request)
    {


        $this->checkApi();
        if(setting('login_page','default') == 'default'){
            $view = 'auth.login';
        }else{
            $view = 'auth.'.setting('login_page').'.login';
        }

        return view($view);
    }


    public function authenticated(Request $request)
    {


        if(Auth::check())
        {

            $user = Auth::user();
//             if(check_permission('Login-static-ip')){
// $ip=setting('static_Ip','192.0.0.1');
// if($ip!=$request->ip()){
//     $previous_session = $user->session_id;
//     // dd($previous_session);
//     \Illuminate\Support\Facades\Auth::user()->session_id = NULL;
//     Auth::user()->no_of_logins = 0;
//     Auth::user()->save();

//     $this->guard()->logout();

//     $request->session()->invalidate();

//     $request->session()->regenerateToken();

//     return $this->sendFailedLoginResponse($request);
// }

            // }

               $previous_session = $user->session_id;
               $login = $user->no_of_logins;
               if ($login > 0){
                    if ($previous_session)
                        {
                        \Session::getHandler()->destroy($previous_session);
                        Auth::user()->no_of_logins--;
                        }
                }
                //dd("hello");
               Auth::user()->session_id = \Session::getId();
               Auth::user()->no_of_logins++;
               Auth::user()->save();


        }
        // check if this user has the "login" admin notifications
        if (in_array('login', auth()->user()->admin_notifications)){
            // notify admin of this login
            $subject = "Login Notification";
            $message = ucfirst(auth()->user()->name) . " just logged into his account.";
            $adminEmail = setting('notification_receiver_email');
            if ($adminEmail) $this->notifyAdmin($message, $subject, $adminEmail);
            // notify the manager of this user
            if (auth()->user()->manager()->exists()) {
                $this->notifyAdmin($message, $subject, auth()->user()->manager->email);
            }
        }

        if(check_permission('login-manger'))
        {
            return redirect('/admin/dashboard');
        }
    }
// $2y$10$MNG3IYD9n5mB923GFkJp9eILhY18TXJy2WQItjKrGbMRW06i0h.EG
    protected function redirectTo()
    {

        if (check_permission('login-manger')) {
            return '/admin/dashboard';
        }
        return '/dashboard';
    }

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(Request $request)
    {

$route=\Request::route()->getName();

if($route=='logout'){


        User::where('last_seen','!=',null)->update([
            "last_seen"=>date('Y-m-d H:i:s',strtotime('-1 days'))
        ]);
    }


        $this->middleware('guest')->except('logout');

    }
}
