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


    /**
     * جيب قائمة الأعمدة المتاحة للـ Export
     */
    public function getExportableColumns()
    {
        $columns = [
            ['key' => 'id', 'label' => 'ID'],
            ['key' => 'surname', 'label' => 'Name'],
            ['key' => 'email', 'label' => 'Email'],
            ['key' => 'source', 'label' => 'Source'],
            ['key' => 'status_id', 'label' => 'Status'],
            ['key' => 'manager_id', 'label' => 'Manager'],
            ['key' => 'balance', 'label' => 'Balance'],
            ['key' => 'phone', 'label' => 'Phone'],
            ['key' => 'last_comment', 'label' => 'Last Comment Date'],
            ['key' => 'last_comment_content', 'label' => 'Last Comment'],
            ['key' => 'plan', 'label' => 'Plan'],
            ['key' => 'country', 'label' => 'Country'],
            ['key' => 'campaign', 'label' => 'Campaign'],
            ['key' => 'agent', 'label' => 'Agent'],
            ['key' => 'created_at', 'label' => 'Created At'],
            ['key' => 'category', 'label' => 'Category'],
        ];
        
        $this->setData($columns);
        $this->setMessage("success");
        return $this->sendApiResonse();
    }

    public function ExportLeads(Request $request)
    {
        $request->validate([
            'type' => 'required|in:0,1,2,4,5,9,10',
            'columns' => 'nullable|array', // الأعمدة المختارة
            'columns.*' => 'string'
        ]);
        
        // تحويل الـ type لـ integer بشكل صريح
        $exportType = (int)$request->type;
        
        
        
        // تحديد نوع الـ Export
        $typeNames = [
            0 => 'Leads (type_id=1)',
            1 => 'Potential (type_id=2, status_id=4)',
            2 => 'Active (type_id=2)',
            4 => 'FTD',
            5 => 'Public Customer',
            9 => 'Archive',
            10 => 'Leads Center'
        ];
        
        
        
        $user = auth()->user();
        $timestamp = now()->format('Y-m-d_H-i-s');
        $fileName = "Users_Export_{$timestamp}.xls";
        $filePath = "upload/excel/export/{$fileName}";
        
        // جيب الأعمدة المختارة أو الأعمدة الأساسية
        $selectedColumns = $request->has('columns') && !empty($request->columns) 
            ? $request->columns 
            : ['id', 'surname', 'email', 'source', 'status_id', 'balance', 'phone', 'plan', 'country'];
        
        // جمع كل الفلاتر من الـ Request
        $filters = $request->all();
        
        Log::info('📋 Export Request Details:', [
            'type' => $exportType,
            'columns' => $selectedColumns,
            'has_search' => isset($filters['search']),
            'search_count' => count($filters['search'] ?? []),
            'has_dateFilters' => isset($filters['dateFilters']),
            'sort_by' => $filters['sort_by'] ?? 'id',
            'sort_direction' => $filters['sort_direction'] ?? 'desc',
        ]);
        
        // زود الـ memory limit
        ini_set('memory_limit', '512M');
        set_time_limit(300); // 5 دقائق
        
        // مرر الأعمدة والفلاتر للـ Export
        try {
            Log::info('Creating Export Object...');
            $export = new UsersClientExport($exportType, $selectedColumns, $filters);
            
            Log::info('Storing Excel File...');
            $stored = Excel::store($export, $filePath, 'public');
            
            if (!$stored || !Storage::disk('public')->exists($filePath)) {
                throw new \Exception("Failed to create export file at path: {$filePath}");
            }

            // Log::info('✅ Export file created successfully, sending email...');
            // Mail::to("tarekkhater103@gmail.com")->send(new FileSendUsers($user, $filePath));
            // $this->setMessage("success, File sent to your email");
            // Storage::disk('public')->delete($filePath);
            
            // جيب الـ URL للـ file (الـ file موجود في public/upload/excel/export/)
            $fileUrl = asset($filePath);
            
            Log::info('📥 File URL generated', ['url' => $fileUrl]);
            
            $this->setMessage("success, File ready for download");
            $this->setData([
                'download_url' => $fileUrl,
                'file_path' => $filePath,
                'file_name' => $fileName
            ]);
            
            return $this->sendApiResonse();
            
        } catch (\Exception $e) {
            Log::error('❌ Export Failed', [
                'error' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString()
            ]);
            
            $this->setStatus(500);
            $this->setMessage("Export failed: " . $e->getMessage());
            return $this->sendApiResonse();
        }
    }




}
