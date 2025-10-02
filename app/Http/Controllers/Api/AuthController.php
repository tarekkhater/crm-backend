<?php

namespace App\Http\Controllers\Api;

use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Http;
use App\Http\Controllers\Controller;
use App\Models\Currency;
use App\Models\Role;
use App\Http\Resources\UserCollection;
use App\Models\User;
use App\Models\Country;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use DB;

class AuthController extends Controller
{
    public $loginAfterSignUp = true;

    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['login', 'loginUser', 'register', 'cur', 'countries', 'crmStore', 'webhookStore', 'pluginRegister']]);
    }


    public function cur()
    {
        if (setting('multi_currency', 0) == 1) {
            $curs = Currency::all();
        } else {
            $curs = Currency::where('code', 'USD')->get();
        }
        return response()->json($curs);
    }

    public function countries()
    {
        $countries = Country::all();
        return response()->json($countries);
    }

    public function crmStore(Request $request)
    {
        $data = $this->getCData($request);
        $data['password'] = bcrypt($data['password']);
        $user = User::create($data);
        return response()->json($user);
    }




    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            // 'username' => 'required|max:50|min:3|unique:users',
            'first_name' => 'required|max:50|min:3',
            'last_name' => 'required|max:50|min:3',
            'country' => 'nullable',
            'phone' => 'required|min:3|unique:users',
            'email' => 'required|max:50|email|unique:users',
            'password'  => 'required|min:6',
            'repeatPassword' => 'required|same:password',
            'city'  => 'nullable',
            'address'  => 'nullable',
            'permanent_address'  => 'nullable',
            'cur'  => 'required',
        ], [
            'cur.required' => 'The currency field is required'
        ]);

        if ($validator->fails()) {
            return $this->errorResponse($validator->errors()->all(), 422);
        }

        $data = $request->all();
        $role = Role::where('name', 'user')->first();
        $data['password'] = bcrypt($data['password']);
        $user = User::create($data);

        $user->attachRole($role);
        // login user
        try {
            // attempt to verify the credentials and create a token for the user
            if (! $token = $token = auth('api')->attempt(['email' => $request->email, 'password' => $request->password])) {
                return $this->errorResponse('Unauthorized!', 401);
            }

            return $this->successResponse('Login successful!', [
                '_id' => $user->id,
                'email' => $user->email,
                'name' => $user->name,
                'token' => $token
            ]);
        } catch (JWTException $e) {
            // something went wrong whilst attempting to encode the token
            return $this->errorResponse('Could not create token', 500);
        }
    }

    public function pluginRegister(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'first_name' => 'required|max:50|min:3',
            'last_name' => 'required|max:50|min:3',
            'country' => 'nullable',
            'phone' => 'required|min:3|unique:users',
            'phone_code' => 'required|min:3',
            'email' => 'required|max:50|email|unique:users',
            'password'  => 'required|min:6',
            'cur'  => 'required',
        ], [
            'cur.required' => 'The currency field is required'
        ]);

        if ($validator->fails()) {
            return $this->errorResponse($validator->errors()->all(), 422);
        }

        try {
            DB::beginTransaction();
            $data = $request->all();
            $role = Role::where('name', 'user')->first();
            $data['password'] = bcrypt($data['password']);
            $user = User::create($data);

            $user->attachRole($role);

            // send password to user's email
            $message = 'Your password is ' . $request->password;
            $this->message($user, $message, 'Account Creation!');

            // add account to GetResponse Platform
            $response = Http::withHeaders([
                'accept' => 'application/json',
                'X-Auth-Token' => 'api-key 4sa1hc723sd6f3saciyvoljrx3wb618e',
            ])->post('https://api.getresponse.com/v3/contacts', [
                'name' => $data['first_name'] . ' ' . $data['last_name'],
                'email' => $data['email'],
                'campaign' => [
                    'campaignId' => 'Z9VE2'
                ],
                'ipAddress' => $data['ip_address'],
            ]);

            if (!$response->successful()) {
                DB::rollback();
                return $this->errorResponse('An error occurred connecting to "Get Response"', 500);
            }

            DB::commit();
            return $this->successResponse('Login successful!', [
                '_id' => $user->id,
                'email' => $user->email,
                'url' => URL::temporarySignedRoute('instantlogin', now()->addMinutes(2), ['id' => $user->id]),
            ]);
        } catch (JWTException $e) {
            DB::rollback();
            return $this->errorResponse('Could not create token', 500);
        }
    }



    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|max:50|email|exists:users',
            'password'  => 'required|min:6'
        ], [
            'exists' => 'This email does not exist.',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()->first()], 422);
        }
        // assuming that the email or username is passed through a 'login' parameter
        $login = $request->input('email');
        $field = filter_var($login, FILTER_VALIDATE_EMAIL) ? 'email' : 'phone';
        $request->merge([$field => $login]);
        $credentials = $request->only($field, 'password');

        try {
            // attempt to verify the credentials and create a token for the user
            if (! $token = $token = auth('api')->attempt($credentials)) {
                return $this->errorResponse('Invalid Password', 401);
            }
            $data['user'] = new UserCollection(User::where('email', $request->email)->first());
            $data['token'] = $token;
            return $this->successResponse('Login Successful', $data);
        } catch (JWTException $e) {
            // something went wrong whilst attempting to encode the token
            return $this->successResponse('Could not create token', 500);
        }

        // return $this->respondWithToken($token);
    }

    public function user()
    {
        return  auth()->user();
    }

    public function update(Request $request)
    {
        $data = $this->updateData($request);
        $user = user::find(auth('api')->user()->id);
        $user->update($data);
        $user = user::find(auth('api')->user()->id);
        return response()->json($user);
    }

    public function logout()
    {

        auth('api')->logout();
        return response()->json(['message' => 'Successfully logged out']);
    }


    public function refresh()
    {
        return $this->respondWithToken(auth('api')->refresh());
    }

    protected function respondWithToken($token)
    {
        return response()->json([
            'access_token' => $token,
            'user' => $this->guard('api')->user(),
            'token_type' => 'bearer',
            'expires_in' => auth('api')->factory()->getTTL() * 60
        ]);
    }

    public function updateOnline()
    {
        $user = auth('api')->user();
        $user->last_seen = Carbon::now()->addMinutes(5);
        $user->save();
        return response()->json($user);
    }

    public function changePassword(Request $request)
    {
        if (!(Hash::check($request->get('current_password'), auth()->user()->password))) {
            // The passwords matches
            return response()->json('Your current password does not matches with the password you provided. Please try again.', 500);
        }
        if (strcmp($request->get('current_password'), $request->get('new_password')) == 0) {
            //Current password and new password are same
            return response()->json('New Password cannot be same as your current password. Please choose a different password', 500);
        }
        $validatedData = $request->validate([
            'current_password' => 'required',
            'new_password' => 'required|string|min:6',
        ]);
        //Change Password
        $user = auth()->user();
        $user->password = bcrypt($request->get('new_password'));
        $user->save();
        return response()->json('success');
    }

    public function guard()
    {
        return \Auth::Guard('api');
    }

    protected function getData(Request $request)
    {
        $rules = [
            'username' => 'required|max:50|min:3|unique:users',
            'first_name' => 'required|max:50|min:3',
            'last_name' => 'required|max:50|min:3',
            'country' => 'nullable',
            'phone' => 'required|min:3|unique:users',
            'email' => 'required|max:50|email|unique:users',
            'password'  => 'required|min:6',
            'repeatPassword' => 'required|same:password',
            'city'  => 'nullable',
            'address'  => 'nullable',
            'permanent_address'  => 'nullable',
            'cur'  => 'required',
        ];
        $data = $request->validate($rules);
        $data['can_trade'] = 1;
        return $data;
    }
    protected function updateData(Request $request)
    {
        $rules = [
            'first_name' => 'required|min:3|max:50',
            'last_name' => 'required|min:3',
            'country' => 'nullable',
            'city'  => 'nullable',
            'address'  => 'nullable',
            'permanent_address'  => 'nullable',
            'cur'  => 'nullable',
        ];
        return $request->validate($rules);
    }

    protected function getCData(Request $request)
    {
        $rules = [
            'username' => 'required|min:3|unique:users',
            'first_name' => 'required|min:3',
            'last_name' => 'required|min:3',
            'country' => 'nullable',
            'phone' => 'required|min:3|unique:users',
            'email' => 'required|email|unique:users',
            'password'  => 'required|min:6',
            'city'  => 'nullable',
            'address'  => 'nullable',
            'permanent_address'  => 'nullable',
            'cur'  => 'required',
        ];
        $data = $request->validate($rules);
        $data['can_trade'] = 1;
        return $data;
    }


    public function destroy($id)
    {
        $user = User::destroy($id);
        return response()->json($user);
    }
}
