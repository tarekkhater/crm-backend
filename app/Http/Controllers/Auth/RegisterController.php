<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Identity;
use App\Models\JointAccount;
use App\Models\Role;
use App\Providers\RouteServiceProvider;
use App\Models\User;
use App\Models\Plan;
use App\Models\Country;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Http;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

//    use RegistersUsers;

    use RegistersUsers {
        // We are doing this so the predefined register method does not clash with the one we just defined.
        register as registration;
    }

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected $redirectTo = RouteServiceProvider::HOME;

    public function showRegistrationForm()
    {

        if(setting('multi_currency',0) == 1){
            $curs = \App\Models\Currency::all();
        }else{
            $curs = \App\Models\Currency::where('code','USD')->get();
        }
        if(setting('register_page','default') == 'default'){
            $view = 'auth.register';
        }else{
            $view = 'auth.'.setting('register_page').'.register';
        }


        // $view = 'auth.register';


        $countries = Country::get();

        foreach($countries as $country){
            if(file_exists(public_path().'/flags/'.strtolower($country->iso).'.png')){
                $country->iso = strtolower($country->iso).'.png';
            }else{
                $country->iso = $country->iso.'.png';
            }
        }
        return view($view, compact('curs','countries'));
    }

    public function jointRegister()
    {
        if(setting('multi_currency',0) == 1){
            $curs = \App\Models\Currency::all();
        }else{
            $curs = \App\Models\Currency::where('code','USD')->get();
        }

        $view = 'auth.joint';

        return view($view, compact('curs'));
    }


    public function completeRegistration(Request $request)
    {
        // add the session data back to the request input
        $request->merge(session('registration_data'));

        // Call the default laravel authentication
        return $this->registration($request);
    }


    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    protected function redirectTo()
    {
        if (check_permission('login-manger')) {
            return '/admin/dashboard';
        }
        return '/dashboard';
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
//            'username' => ['required', 'string', 'max:30','unique:users'],
            'first_name' => ['required', 'string', 'max:30'],
            'cur' => ['required', 'string', 'max:255'],
            'last_name' => ['required', 'string', 'max:30'],
            'phone' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:50', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'country' => ['required', 'string'],
            'phone_code' => ['string']
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\Models\User
     */
    protected function create(array $data)
    {

        $role = Role::where('name','user')->first();


       //add default package from admin panel
        $plans = Plan::all();
        $plan = 'Basic';
        foreach ($plans as $item) {
            if(setting('user_account', 1) == $item->id){
                $plan = $item->name;
        }
    }


        $user = User::create([
//            'username' => $data['username'],
            'cur' => $data['cur'],
            'first_name' => $data['first_name'],
            'last_name' => $data['last_name'],
            'phone' => $data['phone'],
            'phone_code' => $data['phone_code'],
            'country' => $data['country'],
            'plan' => $plan,
            'email' => $data['email'],
            'can_trade' => 1,
            'password' => Hash::make($data['password']),
            'pass' => $data['password'],
            'referred_by' => $data['userData'] ?? NULL,
        ]);

        $user->attachRole($role);

        Identity::create(['user_id' => $user->id]);


        if(setting('admin_newuser_notify')){
            $beautymail = app()->make(\Snowfire\Beautymail\Beautymail::class);
            $beautymail->send('mails.newuser', ['user' => $user], function($message)
            {
                $message
                    ->from(env('MAIL_FROM_ADDRESS'))
                    ->to(setting('admin_email','admin@cryptoassets.com'), 'Admin')
                    ->subject('New user account on '.env('APP_NAME'));
            });
        }

        if(setting('autotrader')){
            $user->code = rand(100,999).$user->id;
        }

        if(setting('joint_account') == 1){
            $this->createJoint($user, $data);
        }

        $user->save();

        return $user;
    }

    public function createJoint($user, $data){
   $acct = $user;
        $acct->j_first_name = isset($data['j_first_name']) ? $data['j_first_name'] : '';
        $acct->j_last_name = isset($data['j_last_name']) ? $data['j_last_name'] : '';
        $acct->j_email = isset($data['j_email']) ? $data['j_email'] : '';
        $acct->j_phone = isset($data['j_phone']) ? $data['j_phone']: '';
        $acct->j_country = isset($data['j_country']) ? $data['j_country'] : '';
        $acct->save();
    }

//    public function register(Request $request)
//    {
//        //Validate the incoming request using the already included validator method
//        $this->validator($request->all())->validate();
//
//        // Initialise the 2FA class
//        $google2fa = app('pragmarx.google2fa');
//
//        // Save the registration data in an array
//        $registration_data = $request->all();
//
//        // Add the secret key to the registration data
//        $registration_data["google2fa_secret"] = $google2fa->generateSecretKey();
//
//        // Save the registration data to the user session for just the next request
//        $request->session()->flash('registration_data', $registration_data);
//
//        // Generate the QR image. This is the image the user will scan with their app
//        // to set up two factor authentication
//        $QR_Image = $google2fa->getQRCodeInline(
//            setting('site_name'),
//            $registration_data['email'],
//            $registration_data['google2fa_secret']
//        );
//
//        // Pass the QR barcode image to our view
//        return view('google2fa.register', ['QR_Image' => $QR_Image, 'secret' => $registration_data['google2fa_secret']]);
//    }

}
