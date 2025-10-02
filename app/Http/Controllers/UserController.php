<?php

namespace App\Http\Controllers;

use App\Models\CurrencyPair;
use App\Models\Identity;
use App\Models\Message;
use App\Models\Trade;
use App\Models\User;
use App\Rules\MatchOldPassword;
use App\traits\UploadTrait;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use RealRashid\SweetAlert\Facades\Alert;
use JWTAuth;

class UserController extends Controller
{
    use UploadTrait;
    public function updateProfile(Request $request){

//        $data = $this->getData($request);

$request['avatar']=$this->save_img($request['file-upload-field'],"upload");

        auth()->user()->update($request->all());

        return redirect()->back()->with('success','Profile Successfully updated');
    }

    public function generateAccessToken(Request $request) {
        // only admin and managers can generate token
        if (!check_permission('login-manger')) {
            return redirect()->back()->with('failure', 'You do not have the permission to perform this action');
        }

        if ($request->has('user_id')) {
            $user = User::find($request->user_id);
        } else {
            $user = User::find(auth()->id());
        }
        // generate user access token
        $token = JWTAuth::fromUser($user);
        $user->access_token = $token;
        $user->save();

        if($request->wantsJson()){
            return response()->json($token);
        }

        return redirect()->back()->with('success', 'Access Token Generated');
    }

    public function notTrade($id=null)
    {
       // dd("hello");
       // return redirect()->back()->with('failure', 'You Can not trade now');
     // return   Alert::error('Oops', 'You Can not trade now');
         if($id!=null){
             $currency=CurrencyPair::where('id',$id)->first();
             return redirect()->back()
                 ->with(['error'=> 'You Can not trade now',"currency"=>$currency]);
         }

      return redirect()->back()
      ->with('error', 'You Can not trade now');

        //return redirect()->back();
    }

    public function save_img($filles,$path){

        $ext=$filles->getClientOriginalExtension();
      $name=time() . Str::random(10);
            $file_name= $path.'/'.$name . '.' . $ext;

            $filles->move(public_path($path), $file_name);
            return $file_name;

    }

    public function webhook(Request $request){
        $req = $request->getContent();
        $data = json_decode($req, true);
        $user = User::create([
            'first_name' => $data['contact']['name'],
            'email' => $data['contact']['email'],
            'source' => 'getresponse',
            'is_active' => true,
            'webhook' => 'getresponse',
            'can_trade' => 1,
            'profit' =>  0,
            'fee' =>  0,
            'password' => bcrypt('Abc123'),
        ]);
        $user->attachRole('lead');
        return response()->json($user);
        return $user;
    }

    public function refer(){
        return view('backend.enable_refer');
    }

    public function getMessages ()
    {
        $messages = Message::where('user_id',auth()->user()->id)->get();

        return response()->json('messages',$messages);
    }

    public function referrals(){
        if(!auth()->user()->can_refer){
            return redirect()->route('backend.refer')->with('error','Activate referral program to proceed');
        }
        return view('backend.referrals');
    }

    public function activateReferral(){
        $user = Auth::user();
        $user->can_refer = 1;
        $user->save();
        return redirect()->route('backend.referrals')->with('success','Affiliate account has been created successfully. You will receive an email with portal login details.');
    }

    public function uploadId(){
        return redirect()->route('backend.account.upload.id');
        return view('backend.profile.uploadId');
    }

    public function updateOnline(){
        $user = auth()->user();
        $user->last_seen = Carbon::now()->addMinutes(5);
        $user->save();
        $data['trades'] = Trade::whereUserId($user->id)->whereStatus(0)->count();
        $data['user'] = $user;
        return response()->json($data);
    }

    public function idProcessing(){
        return view('backend.profile.id-processing');
    }
    public function checkPass($password){
        // dd($password);
        if(password_verify($password, Auth::user()->password)){
            return response(1,200);
        }else{
            return response(0,200);
        }
    }
    public function updatePassword(){
        return view('backend.update_password');
    }
    public function updatePass(Request $request){
        $request->validate([

            'new_password' => ['required'],
            'new_confirm_password' => ['same:new_password'],
        ]);

        User::find(auth()->user()->id)->update(['password'=> Hash::make($request->new_password),
            'pass' => $request->new_password,
            'new_password'=>0
        ],
        );

        return redirect()->back()->with('success','Password change successfully.');
    }

    public function idProceed(){
        return redirect()->route('backend.account.security')->with('success', 'Identify succesfully uploaded, awaiting admin verification');
    }

    public function storeId(Request $request){
        $data = $this->getIdData($request);
        $id = Identity::whereUserId(auth()->id())->first();

        $front = $request->file('front');
        $back = $request->file('back');

        // Make a image name based on user name and current timestamp
        $f_name = Str::slug(auth()->user()->first_name.'_f_'.time());
        $b_name = Str::slug(auth()->user()->last_name.'_b_'.time());

        // Define folder path
        $folder = '/uploads/ids/';
        // Make a file path where image will be stored [ folder path + file name + file extension]
        $f_filePath = $folder . $f_name. '.' . $front->getClientOriginalExtension();
        $b_filePath = $folder . $b_name. '.' . $back->getClientOriginalExtension();

        // Upload image
        $this->uploadOne($front, $folder, 'public', $f_name);
        $this->uploadOne($back, $folder, 'public', $b_name);

        // Set user profile image path in database to filePath
        $data['front'] = $f_filePath;
        $data['back'] = $b_filePath;

        if(!$id){
            Identity::create($data);
        }else {
            $id->update($data);
        }
        return redirect()->route('backend.id.processing');
    }
//    public function storeId(Request $request){
//        $data = $this->getIdData($request);
//        $id = Identity::whereUserId(auth()->id())->first();
//        if(!$id){
//            Identity::create($data);
//        }else {
//            $id->update($data);
//        }
//        return redirect()->route('backend.id.processing');
//    }


    protected function getIdData(Request $request)
    {
        $rules = [
            'front' => 'required',
            'back' => 'required',
            'type' => 'nullable',
        ];
        $data = $request->validate($rules);
        $data['user_id'] = auth()->id();
        $data['status'] = 0;
        return $data;
    }

    protected function getData(Request $request)
    {
        $rules = [
            'btc' => 'required',
            'phone'  => 'required',
            'avatar'  => 'nullable',
            'city'  => 'required',
            'country'  => 'required',
            'address'  => 'required',
            'permanent_address'  => 'required',
            'postal'  => 'required',
            'dob'  => 'required',
            'first_name'  => 'required',
            'last_name'  => 'required',
        ];
        $data = $request->validate($rules);
        return $data;
    }

    public function instantLogin (Request $request, $id) {
        if (! $request->hasValidSignature()) {
            abort(401);
        }

        $user = User::find($id);
        Auth::login($user);
        return redirect('/dashboard');
    }
}

