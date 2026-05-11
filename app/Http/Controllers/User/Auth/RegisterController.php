<?php

namespace App\Http\Controllers\User\Auth;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\User\Auth\RegisterRequest;
use App\Http\Requests\User\Auth\RegisterGoogelRequest;
use App\Mail\Auth\OTPAccountVerified;
use Illuminate\Support\Facades\Mail;
use Spatie\Permission\Models\Role;
use App\Http\Resources\CRM\APi\Auth\LoginResource;
use Illuminate\Support\Facades\Auth;
use Tymon\JWTAuth\Facades\JWTAuth;
use Illuminate\Support\Facades\Cookie;
use App\Models\Admin;
use App\Mail\NewUserDemo;
class RegisterController extends Controller
{
    public function Register(RegisterRequest $request){

        $data = $request->all();
        $code  = generateRandomString(6);
        $user = User::create([
            'name' => $data['name'],
            'surname' => $data['surname']??$data['name'],
            'email' => $data['email'],
            // 'phone_code' => $data['phone_code'],
            'country' => $data['country'],
            'phone' => $data['phone'],
            'address' => $data['address'],
            'permanent_address' => $data['permanent_address'],
            'type_id'=>2,
            'postal'=>isset($data['postal'])??'0',
            'password' => Hash::make($data['password']),
            'pass' => $data['password'],
            'type_account' => $data['type_account']??1,
            // 'birth' => $data['birth'],
        ]);
        $user->userInfo()->create([
            'source_id' => $data['source']??4,
            'status_id' => $data['status']??4,
            'branch_id' => $data['branch']??null,
            'plan_id' => $data['plan']??4,
            'profit' => $data['profit'] ?? '0',
            'fee' => $data['fee'] ?? '0',
        ]);
        $role = Role::find(8);
        $user->assignRole($role);
        $user->Active()->create([
            'code' => Hash::make($code),
            'status' => '0',
            'type' => '2',
        ]);
        
         $manager = Admin::where('sub_type_id',7)->first();
             if($manager){
                $user->Manager()->create([
                    'admin_id'=>(int)$manager->id,
                ]); 
                if($manager->broker_id > 0 ){
                $user->broker_id = $manager->broker_id;
                $user->save();
            }
             }
            
            
    // Mail::to("$request->email")->send(new OTPAccountVerified($user,$code));
    
    if($request->type_account == 0){
         Mail::to("austingreer290@yahoo.com")->send(new NewUserDemo($user));
    }
    
    // Generate token and login user after registration
    $token = JWTAuth::fromUser($user);
    $user->no_of_logins = '1';
    $user->save();
    $user->token = $token;
    $user->load('identity');
    Cookie::queue("jwt", $token, 60);
    
    $this->setData(new LoginResource($user));
    $this->setMessage("success");
    return $this->sendApiResonse();

    }
    
   public function RegisterGoogle(RegisterGoogelRequest $request){
    $data = $request->all();

    // Check if the user already exists using email
    $user = User::where('email', $data['email'])->first();

    if($user) {
        // If user exists, attempt to log in with the given credentials (Google is trusted, so we might not need the password)
        // If you're using JWT and want to issue a token right away, you can directly use the ID token to authenticate

        // Assuming Google login is trusted and no password is required for an existing user
        $cred = ['email' => $data['email']]; // Use only email for login
        $token = JWTAuth::fromUser($user);
        
        if (!$token) {
            $this->setStatus(422);
            $this->setMessage("Your email or password is incorrect");
            return $this->sendApiResponse();
        }

        // Successful login
        $user->token = $token;
        $user->load('identity'); // Assuming you want to load user relationships
        Cookie::queue("jwt", $token, 60);
        
        // Return user data along with the generated token
        $this->setData(new LoginResource($user));
return $this->sendApiResonse();
    } else {
        // If the user does not exist, create a new account with the Google data

        $user = User::create([
            'name' => $data['given_name'],
            'surname' => $data['family_name'],
            'email' => $data['email'],
            'email_verified_at' => now(), // Use Laravel's helper `now()`
            'country' => 0,
            'phone' => '0',
            'address' => "Not Found",
            'permanent_address' => "Not Found",
            'type_id' => 2,
            'postal' => $data['postal'] ?? '0',
            'password' => Hash::make('01024372350J@on@!'), // Create a random password for new users (if you still need it)
            'pass' => $data['email'],  // You can leave this as the email (or remove it if it's not needed)
            'google' => $data['sub'], // Store Google’s unique ID for this user
        ]);

        // Create user-related data (optional)
        $user->userInfo()->create([
            'source_id' => $data['source'] ?? 4,
            'status_id' => $data['status'] ?? 4,
            'branch_id' => $data['branch'] ?? null,
            'plan_id' => $data['plan'] ?? null,
            'profit' => $data['profit'] ?? '0',
            'fee' => $data['fee'] ?? '0',
        ]);

        // Assign a role to the new user
        $role = Role::find(8); // Assign role with ID 8
        $user->assignRole($role);
       $manager = Admin::where('sub_type_id',7)->first();
             if($manager){
                $user->Manager()->create([
                    'admin_id'=>(int)$manager->id,
                ]); 
                if($manager->broker_id > 0 ){
                $user->broker_id = $manager->broker_id;
                $user->save();
            }
             }
        // Send OTP email (optional)
        $code = generateRandomString(6);
        Mail::to($request->email)->send(new OTPAccountVerified($user, $code));

        // Generate a token for the newly registered user (login after registration)
        $token = JWTAuth::fromUser($user);
        $user->token = $token;
        $user->load('identity'); // Assuming you want to load user relationships
        Cookie::queue("jwt", $token, 60);
        // Return the response with token
        $this->setMessage("Success");
        $this->setData(new LoginResource($user));
        return $this->sendApiResonse();
    }

    // Fallback response
    $this->setMessage("An error occurred");
    return $this->sendApiResponse();
}

}
