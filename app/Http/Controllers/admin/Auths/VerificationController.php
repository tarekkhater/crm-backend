<?php
namespace App\Http\Controllers\admin\Auth;
use App\Models\Admin;
use App\Models\ActiveUser;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Resources\CRM\APi\Auth\Login_Admin_Resource;

use Tymon\JWTAuth\Facades\JWTAuth;

class VerificationController extends Controller
{
    public function index(Request $request){
        $request->validate([
            'email'=>"required|email|exists:admins,email",
            'code'=>"required|string|min:4"
        ]);
        $user = Admin::where("email", $request->email)->first();
        $active = ActiveUser::where([['user_id',$user->id],['status','0'],['code',$request->code],['type','1']])->first();
        if($active){
            $active->update([
                'status'=>'1'
            ]);
            $user = Admin::find($active->user_id);
            $user->email_verified_at = date('Y-m-d');
            $user->save();
            $user->token = JWTAuth::fromUser($user);
            $user->load('roles.role.PermissionRole.Permission');

            $this->setData(new Login_Admin_Resource($user));
            $this->setMessage("success");
        }else{
            $this->setStatus(422);
            $this->setMessage(__('app.code.wrong'));
        }
        return $this->sendApiResonse();
    }


    public function resend(Request $request){
        $request->validate([
            'email'=>"required|email|exists:admins,email",
        ]);
        try{
                $user = Admin::where("email",$request->email)->first();
                $code  = generateRandomString();
                $activeuser = ActiveUser::where("user_id",$user->id)->where('type','1')->first();
                if($activeuser){
                  $user->Active()->update([
                    "code"=>$code,
                    "status"=>"0",
                ]);
                }else{
                     $user->Active()->Create([
                    "code"=>$code,
                    "type"=>'1',
                    "status"=>"0",
                ]);
                }
            $this->setMessage(__('app.register.sent').$code);
            $this->setStatus(200);
        }catch(\Exception $e){
            $this->setStatus(422);
             $this->setMessage(__('mobile.unathintacted'));
        }
        return $this->sendApiResonse();
    }
}
