<?php

namespace App\Http\Controllers\User\Profile;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Imports\LeadImport;
use App\Exports\UsersClientExport;
use Illuminate\Support\Facades\Exceptions;
use Maatwebsite\Excel\Facades\Excel;
use App\Models\AssignUserManager;
use App\Models\InfoTradeUser;
use App\Models\Message;
use App\Models\Deposit;
use App\Models\Trade;
use App\Models\Favourite;
use App\Models\Withdrawal;
use App\Models\Position;
use App\Services\Users\UserWalletService;
class IndexController extends Controller
{

    public function index(){
        $user = AuthApi();
        $user->load(['userInfo','countries','transactions','wireAccounts','trades','identity']);
         $deposit = Deposit::where('user_id',$user->id)->sum('amount');
         $withdrawal = Withdrawal::where('user_id',$user->id)->sum('amount');
        
         $data= $user->toArray();
                $base_url=baseUrl();
        $mainBal = UserWalletService::mainBalance($user->userInfo);

        $resilt = [
            'id'=>$data["id"],
            'email'=>$data["email"],
            'name'=>$data["name"],
            'surname'=>$data["surname"],
            'phone'=>$data["phone"],
            'avatar'=>$data["avatar"],
            'address'=>$data["address"],
            'permanent_address'=>$data["permanent_address"],
            'currency'=>$data['user_info']["cur"]??0,
            'country'=>$data["country"]??0,
            'countries'=>[
                'name'=>$data["countries"]["name"]??'-',
                'pc'=>$data["countries"]["phonecode"]??'-',
            ],
            'phone_code'=>$data["phone_code"]??"-",
            'birth'=>$data["birth"] != null?$data["birth"]:"not enter",
            'postal'=>$data["postal"],
            'plan'=>'stander',
            'join_at'=>date('Y M d',strtotime($data["created_at"])),
            'document'=>count($data["identity"]) > 0 ?true:false,
            'ai_trading'=>($data['ai_trading'] ?? '0') == '1',

        ];

        $resilt['money'] = [
                'total'=>$mainBal,
                'balance'=>$mainBal,
                'trading_balance'=>$mainBal,
                'pnl'=>round(Position::where('user_id',$user->id)->where('close_at','<>',null)->sum('net_profit'),2),
                'bouns'=>Deposit::where('user_id',$data['id'])->where('type','bonus')->sum('amount'),
            ];  
            $resilt['trades'] = [
                'open'=>Position::where('user_id',$data['id'])->where('close_at',null)->count(),
                'close'=>Position::where('user_id',$data['id'])->where('close_at','<>',null)->count(),
                'pending'=>Trade::where('user_id',$data['id'])->whereStatus(0)->Where('is_pending_order','<>',null)->count(),
                'assets'=>Favourite::where('user_id',$data['id'])->count(),
            ];  

        $this->setData($resilt);
        $this->setMessage("success");
        return $this->sendApiResonse();
    }

    public function getstatistics(){
        $user = AuthApi();
        $deposit = Deposit::where('user_id',$user->id)->sum('amount');
        $withdrawal = Withdrawal::where('user_id',$user->id)->sum('amount');
        $user->load(['userInfo','transactions','wireAccounts','trades']);
         $data= $user->toArray();
        $mainBal = UserWalletService::mainBalance($user->userInfo);
        $resilt = [
                [
                'title'=>"Total",
                "value"=> $mainBal + (float) ($data['user_info']["pnl"] ?? 0)
                ],
                [
                'title'=>"Estimated Balance",
                "value"=>$mainBal
                ],
                [
                'title'=>"BNL Balance",
                "value"=>Position::where('user_id',$user->id)->where('close_at','<>',null)->sum('net_profit'),
                ],
                [
                'title'=>"Bonus",
                "value"=>$data['user_info']["bonus"]
                ],

                [
                'title'=>"Deposit",
                "value"=>$deposit
                ],

                [
                'title'=>"Withdrawal",
                "value"=>$withdrawal
                ],
        ];


        $this->setData($resilt);
        $this->setMessage("success");
        return $this->sendApiResonse();
    }

    public function aiTrading(Request $request)
    {
        $request->validate([
            'ai_trading' => ['required', 'boolean'],
        ]);

        $user = AuthApi();
        $user->ai_trading = $request->boolean('ai_trading') ? '1' : '0';
        $user->save();

        $this->setData(['ai_trading' => $user->ai_trading == '1']);
        $this->setMessage('success');
        return $this->sendApiResonse();
    }





    public function update(Request $request,$id){
        $user = Auth()->user();
        if($request->email != $user->email){
               $this->validate($request, [
                'email' => ['required','email', 'max:255', 'unique:users',new NoHtmlInjection],
                'password'=>['required', new MatchOldPassword], 
                ]);
                $user->email_verified_at = null;
            $user->save();
        }
        $user->update([
            'name'=>$request->name,
            'surname'=>$request->surname,
            'email'=>$request->email,
            'postal'=>$request->postal,
            'address'=>$request->address,
            'phone'=>$request->phone,
            'address'=>$request->address,
        ]);
        if(isset($request->password) && $request->password !== ''){
            $user->update([
                'password'=>Hash::make($request->paasword),
            ]);
        }
        if($user->email_verified_at == null){
            Auth::guard('apiUser')->logout();
        }

        $this->setMessage("success");
        return $this->sendApiResonse();
    }

    public function investments(){
        $investments = [];
        $data['completed_nvestments'] = auth()->user()->plans()->whereStatus(2)->get();
        $data['active_investments'] = auth()->user()->activePlans()->whereStatus(1)->get();
        $data['pending_investments'] = auth()->user()->plans()->whereStatus(0)->get();
        $this->setData($data);
        $this->setMessage("success");
        return $this->sendApiResonse();
    }



    public function assignAccountMananger(Request $request){
        $request->validate([
            'users'=>'required|array',
            'users.*'=>'required|numeric|exists:users,id',
            'account'=>'required|numeric',
        ]);


        foreach($request->users as $id){
            $find = AssignUserManager::where([['user_id',$id],['admin_id',$request->account]])->first();
            if($find){
                $find->delete();
            }
            $user = User::find((int)$id);
            $user->Manager()->create([
                'admin_id'=>(int)$request->account,
            ]);
        }


        $this->setMessage("success");
        return $this->sendApiResonse();
    }

    public function assignLeadStatus(Request $request){

        $request->validate([
            'users'=>'required|array',
            'users.*'=>'required|numeric|exists:users,id',
            'status'=>'required|numeric',
        ]);

        InfoTradeUser::whereIn('user_id',$request->users)->update([
            'status_id'=>$request->status,
        ]);

        $this->setMessage("success");
        return $this->sendApiResonse();
    }

    public function verificationLeadStatus(Request $request){
        $request->validate([
            'users'=>'required|array',
            'users.*'=>'required|numeric|exists:users,id',
            // 'status'=>'required|numeric',
        ]);


        User::whereIn('id',$request->users)->update([
            'email_verified_at'=>date('Y-m-d'),
        ]);


        // foreach($request->users as $user){

        // }

        $this->setMessage("success");
        return $this->sendApiResonse();
    }
    public function assignBranch(Request $request){
        $request->validate([
            'users'=>'required|array',
            'users.*'=>'required|numeric|exists:users,id',
            'branch'=>'required|numeric',
        ]);

        // foreach( as $user){
            InfoTradeUser::whereIn('user_id',$request->users)->update([
                'branch_id'=>$request->branch,
            ]);
        // }

        $this->setMessage("success");
        return $this->sendApiResonse();
    }
    public function MassMailing(Request $request){
        $request->validate([
            'users'=>'required|array',
            'users.*'=>'required|numeric|exists:users,id',
            'subject'=>'required|string',
            'message'=>'required|string',
        ]);

        foreach($request->users as $user){
            $find = User::find($user);
            $find->messages()->create([
                'subject'=>$request->subject,
                'message'=>$request->message,
                'status'=>'1',
            ]);
        }

        $this->setMessage("success");
        return $this->sendApiResonse();
    }
    public function BrokerNotification(Request $request){
        $request->validate([
            'users'=>'required|array',
            'users.*'=>'required|numeric|exists:users,id',
            'account'=>'required|numeric',
        ]);

        foreach($request->users as $user){

        }

        $this->setMessage("success");
        return $this->sendApiResonse();
    }

    public function import(Request $request){
        $request->validate([
            'file' => 'required'
        ]);

        try{
            Excel::import(new LeadImport(), request()->file('file'));
         return $this->sendApiResonse();
        }catch(Exceptions $e){
            return $e;
        }
        $this->setMessage("success");
        return $this->sendApiResonse();
    }

    public function export(){
        try{

            $res = Excel::store(new UsersClientExport(),'upload/excel/export/users.xls','public');
            $this->setMessage("success");
            $this->setData('upload/excel/export/users.xls');
            return $this->sendApiResonse();
        }catch(Exceptions $e){
            return $e;
        }
    }

    public function filter(Request $request){

        $this->setMessage("success");
        return $this->sendApiResonse();
    }

    public function ConvertUSers(Request $request){
        foreach($request->ids as $value){
            $user = User::find($value);
            $user->type_id = $request->type;
            // $user->attachRole($request->type);
            $user->save();
        }
        $this->setMessage("success");
        return $this->sendApiResonse();
    }


    public function destroy(Request $request){
        foreach($request->ids as $value){
            $users = User::find($value);
            $users->delete();
        }
        $this->setMessage("success");
        return $this->sendApiResonse();
    }
}
