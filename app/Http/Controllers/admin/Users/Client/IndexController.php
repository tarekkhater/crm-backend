<?php

namespace App\Http\Controllers\admin\Users\Client;
use Exception;
use App\Http\Controllers\Controller;
use App\Models\UserMoneyManager;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Admin;
use App\Imports\LeadImport;
use App\Exports\UsersClientExport;
use App\Models\AgentUser;
use Illuminate\Support\Facades\Exceptions;
use Maatwebsite\Excel\Facades\Excel;
use App\Models\AssignUserManager;
use App\Models\IBClient;
use App\Models\InfoTradeUser;
use App\Models\Message;
use App\Models\UserManager;
use App\Models\Permission;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use App\Mail\Files\FileSendUsers;
use Illuminate\Support\Facades\Storage;

class IndexController extends Controller
{
    public function __construct()
    {
        // $this->middleware('RoleMiddleware:view-settings_update-settings')->only('overnights');
        // $this->middleware('RoleMiddleware:verify-all-emails')->only('verifyAccounts');
        // $this->middleware('RoleMiddleware:view-transaction')->only('transactions');
        // $this->middleware('RoleMiddleware:delete-transaction')->only('DelTrans');

    }
    public function index(Request $request){
        $id = getUsersIds();

        $users = User::whereIn('id',$id)->paginate(15);
        $this->setData($users);
        $this->setMessage("success");
        return $this->sendApiResonse();
    }

    public function show($id){
        $user = User::find($id);
        $user->load(['Payments','myWithdrawals','deposits','transactions','accounts','wireAccounts','trades','messages']);
        $this->setData($user);
        $this->setMessage("success");
        return $this->sendApiResonse();
    }

    public function assignAccountSources(Request $request){
        $request->validate([
            'users'=>'required|array',
            'users.*'=>'required|numeric|exists:users,id',
            'account'=>'required|numeric',
        ]);
        if(isset($request->type) && $request->type == 'plan'){
            foreach($request->users as $id){
            $find = InfoTradeUser::where([['user_id',(int)$id]])->first();
            if($find){
                $find->plan_id = (int)$request->account;
                $find->save();
            }
        }
        }elseif(isset($request->type) && $request->type == 'status'){
            foreach($request->users as $id){
                $find = InfoTradeUser::where([['user_id',(int)$id]])->first();
                if($find){
                    $find->status_id = (int)$request->account;
                    $find->save();
                }
            }
        }
        elseif(isset($request->type) && $request->type == 'campaign'){
            foreach($request->users as $id){
                $find = InfoTradeUser::where([['user_id',(int)$id]])->first();
                if($find){
                    $find->campaign_id = (int)$request->account;
                    $find->save();
                }
            }
        }else{
            foreach($request->users as $id){
            $find = InfoTradeUser::where([['user_id',(int)$id]])->first();
            if($find){
                $find->source_id = (int)$request->account;
                $find->save();
            }
        }
        }
        
        $this->setMessage("success");
        return $this->sendApiResonse();
    }
        
    public function assignAccountMananger(Request $request){
        $request->validate([
            'users'=>'required|array',
            'users.*'=>'required|numeric|exists:users,id',
            'account'=>'required|numeric',
        ]);


        // if(isset($request->account_leader) && $request->account_leader > 0){
        //     foreach($request->users as $id){
        //         $find = AgentUser::where([['user_id',$id],['agent_id',$request->account_leader]])->first();
        //         if($find){
        //             $find->delete();
        //         }
        //         $user = User::find((int)$id);
        //         $user->AgentUser()->create([
        //             'agent_id'=>(int)$request->account_leader,
        //             'agent_type'=>'0',
        //             'status_id'=>3,
        //         ]);
        //     }
        //     if(isset($request->account_agent) && $request->account_agent > 0){
        //         $find1 = AgentUser::where([['user_id',$id],['agent_type'=>'1'],['agent_id',$request->account_leader]])->first();
        //         if($find1){
        //             $find1->delete();
        //         }
        //         $user = User::find((int)$id);
        //         $user->AgentUser()->create([
        //             'agent_id'=>(int)$request->account_leader,
        //             'agent_type'=>'1',
        //             'status_id'=>3,
        //         ]);
        //     }
        // }


        foreach($request->users as $id){
            $find = AssignUserManager::where([['user_id',(int)$id]])->first();
            if($find){
                $find->admin_id = (int)$request->account;
                $find->save();
            }else{
                $user = User::find((int)$id);
                $manager = Admin::find($request->account);
                $user->Manager()->create([
                    'admin_id'=>(int)$request->account,
                ]);
                if($manager->broker_id > 0 ){
                    $user->broker_id = $manager->broker_id;
                    $user->save();
                } 
            }
           
        }



        $this->setMessage("success");
        return $this->sendApiResonse();
    }

    public function assignAccountMoneyMananger(Request $request){
        $request->validate([
            'users'=>'required|array',
            'users.*'=>'required|numeric|exists:users,id',
            'account'=>'required|numeric',
        ]);


        foreach($request->users as $id){
            $find = UserMoneyManager::where([['user_id',$id],['admin_id',$request->account]])->first();
            if($find){
                $find->delete();
            }
            $user = User::find((int)$id);
            $user->MoneyManager()->create([
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
            'status'=>'sometimes|nullable|numeric|exists:statuses,id',
             'source'=>'sometimes|nullable|numeric|exists:sources,id',
             'campaign'=>'sometimes|nullable|numeric|exists:campaigns,id',
            'plan'=>'sometimes|nullable|numeric|exists:plans,id',
        ]);
        if(isset($request->status)){
                InfoTradeUser::whereIn('user_id',$request->users)->update([
            'status_id'=>$request->status,
        ]);
        }
        
        if(isset($request->plan)){
                InfoTradeUser::whereIn('user_id',$request->users)->update([
            'plan_id'=>$request->plan,
        ]);
        }
        
        if(isset($request->source)){
            InfoTradeUser::whereIn('user_id',$request->users)->update([
            'source_id'=>$request->source,
        ]);
        }
        
        if(isset($request->campaign)){
            InfoTradeUser::whereIn('user_id',$request->users)->update([
            'campaign_id'=>$request->campaign,
        ]);
        }
        

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
            'file' => 'required|mimes:xlsx,xls,csv|mimetypes:application/vnd.openxmlformats-officedocument.spreadsheetml.sheet,application/vnd.ms-excel,text/csv|max:2048',
        ]);
        
        

        // try{
            Excel::import(new LeadImport(), request()->file('file'));
         
        // }catch(Exception $e){
        //     return $e;
        // }
        $this->setMessage("success");
        return $this->sendApiResonse();
    }

    public function export(Request $request)
    {
        try {
            // Retrieve the type from the request, default to a specific type if necessary
            $type = $request->get('type', 1); // Example default type is 1

            // Store the export as an Excel file
            Excel::store(new UsersClientExport($type), 'upload/excel/export/users.xls', 'public');

            // Set the response message and data
            $this->setMessage("success");
            $this->setData('upload/excel/export/users.xls');

            return $this->sendApiResonse();
        } catch (\Exception $e) {
            // Handle any exceptions and provide a meaningful error response
            return response()->json([
                'message' => 'Export failed',
                'error' => $e->getMessage()
            ], 500);
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


    public function autoProfitLoss(Request $request){
        $user = User::find($request->id);
        if( $user->auto_profit_loss == '0'){
            $user->auto_profit_loss = '1';
        }else{
            $user->auto_profit_loss = '1';
        }
        $user->save();
        $this->setMessage("success");
        return $this->sendApiResonse();
    }
    public function DisableTrade(Request $request){
        $user = User::find($request->id);

        if( $user->allow_trade == '0'){
            $user->allow_trade = '1';
        }else{
            $user->allow_trade = '1';
        }
        $user->save();
        $this->setMessage("success");
        return $this->sendApiResonse();
    }


    public function BlockUser(Request $request){
        $user = User::find($request->id);
        if( $user->block == '0'){
            $user->block = '1';
        }else{
            $user->block = '1';
        }
        $user->save();
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

    public function messagesUser(Request $request){

        $user= User::find($request->id);
        $user->messages()->create([
            'subject'=>$request->subject,
            'message'=>$request->message,
        ]);
        $this->setMessage("success");
        return $this->sendApiResonse();
    }


    public function ExportLeads(Request $request)
    {
        $request->validate(['type' => 'required|in:0,1,2,4,5,9,10']);
        $user = auth()->user();
        $timestamp = now()->format('Y-m-d_H-i-s');
        $fileName = "Users_Export_{$timestamp}.xls";
        $filePath = "upload/excel/export/{$fileName}";
        
        $stored = Excel::store(new UsersClientExport($request->type), $filePath, 'public');
        if (!$stored || !\Storage::disk('public')->exists($filePath)) {
            throw new \Exception("Failed to create export file at path: {$filePath}");
        }
        
        Mail::to("$user->email")->send(new FileSendUsers($user,$filePath));
        $this->setMessage("success،File");
        Storage::disk('public')->delete($filePath);
        return $this->sendApiResonse();
    }




}
