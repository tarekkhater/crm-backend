<?php

namespace App\Http\Controllers\admin\CRM\Api\Users\Agents\TeamLeader;

use App\Http\Controllers\Controller;
use App\Models\AgentNotes;
use Illuminate\Http\Request;
use App\Models\AgentUser;
use App\Models\TeamleaderNotes;
use App\Models\Admin;
use App\Models\Permission;
use App\Models\User;
use App\Models\UserManager;
use Carbon\Carbon;
use Exception;
use App\Exports\UsersAgentsExport;
use Illuminate\Support\Facades\Exceptions;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Validator;

use App\Services\Users\Agents\IndexSearchServices;
use App\Services\Users\Agents\IndexFilterServices;

class IndexController extends Controller
{

    public $searchAgents ,$filterAgent;
    public function __construct()
    {
        
        $this->searchAgents = new IndexSearchServices();
        $this->filterAgents = new IndexFilterServices();
                
        $this->middleware('RoleMiddleware:view-settings_update-settings')->only('overnights');
        $this->middleware('RoleMiddleware:verify-all-emails')->only('verifyAccounts');
        $this->middleware('RoleMiddleware:view-transaction')->only('transactions');
        $this->middleware('RoleMiddleware:delete-transaction')->only('DelTrans');
        $array=[
            [
                "Dashboard-data-all","Dashboard data all",'Dashboard data all'
            ],
            [
                "Dashboard-data-manger","Dashboard data manger",'Dashboard data manger'
            ],
            [
                "count-customer","count customer",'count customer'
            ],  [
                "online-users","online-users",'online-users'
            ],
            [
                "view-withdrawals","view withdrawals",'view withdrawals'
            ],  [
                "view-deposits","view deposits",'view deposits'
            ], [
                "view-KYC-Verifications","view KYC Verifications",'view KYC Verifications'
            ],[
                "view-Plans","view Plans",'view Plans'
            ],
            [
                "login-manger","login manger",'login manger'
            ],
            [
                "show-user","Profile access",'Profile access'
            ],
            [
                "show-balance","show Trades",'show Trade'
            ], [
                "add-balance","add balance",'add balance'
            ], [
                "edit-balance","edit balance",'edit balance'
            ],
//            [
//            "view-packages","view packages",'view packages'
//        ],
//            [
//            "create-settings","create settings",'create settings'
//        ],
//            [
//            "customer-assignUsers","customer assignUsers",'customer assignUsers'
//        ],
            [
                "Login-static-ip","Login static Ip",'Login From Ip'
            ],
            [
                "edite-Users","Edit customers Date",'Edit customers Date'
            ],
            [
                "edite-conversion-manager","edite conversion manager",'edite conversion manager'
            ],  [
                "only-active","show only Active customer ",'show only Active customer'
            ],


        ];
        foreach ($array as $rows){

            Permission::updateorcreate(['name'=>$rows[0]],[
                'display_name'=>$rows[1],
                'description'=>$rows[2]
            ]);


        }

    }
    public function all(Request $request){

     
        // $ids = [];
        // if(isset($request->id)){
        //     $ids = UserManager::where('admin_id',$request->id)->pluck('user_id');
        // }else{
        //     $ids = UserManager::select()->pluck('user_id');
        // }
        // $users = Admin::whereIn('id',$ids)->where('type_id',6)->get();
        // $this->setData($users);
        $ids = getTeamLeaderIds();
        if($request->type ==1){
            
                // $ids = UserManager::where('admin_id',auth()->user()->id)->where('type','0')->pluck('user_id');
                $users = Admin::whereIn('id',$ids)->select('id','email','country')->where('type_id',6)->where('sub_type_id',7)->get();
                $this->setData($users);
           
        }else{
                $users = Admin::whereIn('id',$ids)->select('id','email','country')->where('type_id',6)->where('sub_type_id',8)->get();
                $this->setData($users);
            
        }
        
        
        $this->setMessage("success");
        return $this->sendApiResonse();
    }
    public function show($id){
        $users = Admin::with(['employee.user','clients.user'])->where('id',$id)->first();
        $this->setData($users);
        $this->setMessage("success");
        return $this->sendApiResonse();
    }

    public function index(Request $request){

        // $permissions = [
            // ['name' => 'Broker-Analytics', 'display_name' =>'Broker Analytics','description' =>'Broker Analytics','title' =>'Broker Analytics'],
            // ['name' => 'sales-Dashboard', 'display_name' =>'sales Dashboard','description' =>'sales Dashboard','title' =>'sales Dashboard'],
            // ['name' => 'view-Clients', 'display_name' =>'view Clients','description' =>'view Clients','title' =>'Clients'],
            // ['name' => 'view-Leads', 'display_name' =>'view Leads','description' =>'view Leads','title' =>'Leads'],
            // ['name' => 'view-trading-Account', 'display_name' =>'view trading Account','description' =>'view trading Account','title' =>'Trading Account'],
            // ['name' => 'view-Deposit', 'display_name' =>'view Deposit','description' =>'view Deposit','title' =>'Deposit'],
            // ['name' => 'view-withdrawals', 'display_name' =>'view Withdrawals','description' =>'view Withdrawals','title' =>'Withdrawals'],
            // ['name' => 'view-Action-Trading-Account', 'display_name' =>'view Acction trading Account','description' =>'view Acction trading Account','title' =>'Action Tab'],
            // ['name' => 'view-Action-KYC', 'display_name' =>'view Acction KYC','description' =>'view Acction KYC','title' =>'Action Tab'],
            // ['name' => 'view-Action-Mailing', 'display_name' =>'view Acction Mailing','description' =>'view Acction Mailing','title' =>'Action Tab'],
            // ['name' => 'view-IB-Account', 'display_name' =>'view IB Account','description' =>'view IB Account','title' =>'IB'],
            // ['name' => 'view-IB-Request', 'display_name' =>'view IB Request','description' =>'view IB Request','title' =>'IB'],
            // ['name' => 'view-Manager', 'display_name' =>'view  Manager','description' =>'view Manager','title' =>'Crm Manager'],
            // ['name' => 'view-Crm-Leader', 'display_name' =>'view Crm Leader','description' =>'view Leader','title' =>'Crm Manager'],
            // ['name' => 'view-Crm-Sales', 'display_name' =>'view Crm Sales','description' =>'view Sales','title' =>'Crm Manager'],
            // ['name' => 'view-Crm-Retenations', 'display_name' =>'view Crm Retenations','description' =>'view Retenations','title' =>'Crm Manager'],
            // ['name' => 'view-Risk-Trade', 'display_name' =>'view Risk Management Trade','description' =>'view Risk Management Trade','title' =>'Risk Management'],
            // ['name' => 'view-Risk-Transactions', 'display_name' =>'view Risk Management Trade','description' =>'view Risk Management Trade','title' =>'Risk Management'],
            // ['name' => 'view-Risk-Trading-Hourse', 'display_name' =>'view Risk Management Trading Hourse','description' =>'view Risk Management Trading Hourse','title' =>'Risk Management'],
            // ['name' => 'view-Risk-Assets', 'display_name' =>'view Risk Management Assets','description' =>'view Risk Management Assets','title' =>'Risk Management'],
            // ['name' => 'view-configuration-users', 'display_name' =>'view Users','description' =>'view Users','title' =>'Risk configuration'],
            // ['name' => 'view-configuration-roles', 'display_name' =>'view Manage Roles','description' =>'view Manage Roles','title' =>'Risk configuration'],

            // ['name' => 'view-Setting', 'display_name' =>'view Setting','description' =>'view Setting','title' =>'Settings'],
            // ['name' => 'view-Dev-Settings', 'display_name' =>'view Dev Settings','description' =>'view Dev Settings','title' =>'Dev Settings'],

        //  ];

        if($request->type ==1){
                $ids = getTeamLeaderIds();
                $users = Admin::whereIn('id',$ids)->where('type_id',6)->where('sub_type_id',7)->paginate(15);
                $this->setData($users);
            
        }else{
                $ids = getTeamLeaderIds();
                $users = Admin::whereIn('id',$ids)->where('type_id',6)->where('sub_type_id',8)->paginate(15);
                $this->setData($users);
        }

        $this->setMessage("success");
        return $this->sendApiResonse();
    }
    
    public function ExportAgents(Request $request){
        $request->validate(['type' => 'required|in:0,1,2,3,4']); 
            $timestamp = now()->format('Y-m-d_H-i-s'); 
            $fileName = "Agents_Export_{$timestamp}.xls";
            $filePath = "upload/excel/export/Agents/{$fileName}";
            
            $stored = Excel::store(new UsersAgentsExport($request->type), $filePath, 'public');

            if (!$stored || !\Storage::disk('public')->exists($filePath)) {
                throw new \Exception("Failed to create export file at path: {$filePath}");
            }

            $this->setMessage("success");
            $this->setData($filePath);
            return $this->sendApiResonse();
    }
    public function ExportLeads(Request $request)
    {
        // Validate the 'type' parameter from the request
        $request->validate(['type' => 'required|in:0,1,2,5,9']); 
    
        // Generate a timestamp for the filename
        $timestamp = now()->format('Y-m-d_H-i-s'); 
        $fileName = "Users_Export_{$timestamp}.xlsx"; // Use xlsx extension
    
        // Define the path for storing the file
        $filePath = 'public/excel/export/' . $fileName; // Correct the file path
    
        try {
            // Get the filtered users by 'type' using the method defined in the Export class
            $stored = Excel::store(new UsersClientExport($request->type), $filePath);
    
            // Check if the file was successfully stored
            if (!$stored || !\Storage::disk('public')->exists($filePath)) {
                throw new \Exception("Failed to create export file at path: {$filePath}");
            }
    
            // Return the success message and file path
            return response()->json([
                'message' => 'Export successful',
                'file_path' => \Storage::url($filePath) // Return the public URL
            ], 200);
    
        } catch (\Exception $e) {
            // Handle exceptions and errors
            return response()->json([
                'message' => 'Failed to export data',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
    public function assignAgent(Request $request){
        $request->validate([
            'users'=>'required|array',
            'users.*'=>'required|numeric|exists:users,id',
            'account'=>'required|numeric',
        ]);

        foreach($request->users as $id){
            $user = User::find((int)$id);
            $user->AgentUser()->create([
                'agent_id'=>(int)$request->account,
                'agent_type'=>'0',
                'status_id'=>3,
            ]);
        }


        $this->setMessage("success");
        return $this->sendApiResonse();
    }

    public function store(Request $request){
    //    dd("test");
        // $this->validate($request, [
        //     'name' => ['required', 'string', 'max:255'],
        //     'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
        //     'branch_id' => ['required', 'numeric', 'exists:branchs,id'],
        //     'password' => ['required', 'string', 'min:6','required_with:confirm_password','same:confirm_password','min:6'],             // must be at least 10 characters in length
        // ]);
        try{
            $data = $request->all();
            $user = Admin::create([
                'name' => $data['name'],
               'surname' => $data['surname'],
                'email' => $data['email'],
                'phone' => $data['phone'],
                'country' => $data['country'],
                'type_id' => $data['type_id'],
                'sub_type_id' => isset($data['sub_type_id'])?$data['sub_type_id']:null,
                'password' => Hash::make($data['password']),
                'pass' => $data['password'],
                'image' => 'faild',
                            // $manager = Admin::find($data['manager_id']);
                            // 'branch_id' => $manager->branch_id,
                            // 'sub_type_id' => $manager->sub_type_id,

            ]);
            if($data['role']){
                $user->roles()->create([
                    'role_id'=>$data['role'],
                    'user_type'=>'App\Models\Admin',
                ]);
            }
            if(isset($data['manager_id'])){
                $user->usermanager()->create([
                'admin_id'=>$data['manager_id'],
                'type'=>'0',
            ]);
                $user->manager_id = $data['manager_id'];
                $user->save();
            }

        $this->setMessage("success");
        return $this->sendApiResonse();
        }catch(Exception $e){
            return $e;
        }

    }
    
    public function update(Request $request, $id)
    {
        // dd("test");
        // Define the validation rules with 'sometimes' for conditional fields
        $rules = [
            'name' => 'sometimes|string|max:255',
            'surname' => 'sometimes|string|max:255',
            'email' => 'sometimes|string|email|max:255|unique:users,email,' . $id,
            'phone' => 'sometimes|string|max:20',
            'country' => 'sometimes|string|max:100',
            'password' => [
                'sometimes',
                'string',
                'min:6',
                'required_with:confirm_password',  // Ensure confirm_password is required if password is provided
                'same:confirm_password',  // Ensure password and confirm_password match
            ], // Password must match confirmation if provided
            'manager_id' => 'sometimes|exists:admins,id', // Validate manager_id exists in the admins table
        ];
    
        // Validate the request data
        $validator = Validator::make($request->all(), $rules);
    
        // Check for validation failures
        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validation error',
                'errors' => $validator->errors()
            ], 422);
        }
    
        try {
            $data = $request->all();
    
            // Find the admin to update
            $user = Admin::findOrFail($id);
    
            // Prepare the fields to update conditionally
            $updateData = [
                'name' => $data['name'] ?? $user->name,
                'surname' => $data['surname'] ?? $user->surname,
                'email' => $data['email'] ?? $user->email,
                'phone' => $data['phone'] ?? $user->phone,
                'country' => $data['country'] ?? $user->country,
                'password' => isset($data['password']) ? Hash::make($data['password']) : $user->password,
                'pass' => $data['password'] ?? $user->pass,
                'image' => $data['image'] ?? $user->image,
            ];
    
            // Update user data
            $user->update($updateData);
    
            // Handle manager association if `manager_id` is provided
            if (isset($data['manager_id'])) {
                $user->usermanager()->updateOrCreate(
                    ['admin_id' => $data['manager_id']],
                    ['type' => '0']
                );
                $user->manager_id = $data['manager_id'];
                $user->save();
            }
    
            // Set success message and response
            $this->setMessage("success");
            return $this->sendApiResonse();
    
        } catch (\Exception $e) {
            // Handle exception and return error response
            return response()->json([
                'message' => 'Update failed',
                'error' => $e->getMessage()
            ], 500);
        }
    }
    
    
    public function Blocked(Request $request){
        $user = Admin::find($request->id);
        $user->block = $user->block == '1'?'0':'1';
        $user->save();
        $this->setMessage("success");
        return $this->sendApiResonse();
    }

    public function customers(){
        $pluckId = AgentUser::where('agent_type','1')->pluck('user_id');
        $users = User::whereNotIn('id',$pluckId)->where('type_id',2)->get();
        $this->setData($users);
        $this->setMessage("success");
        return $this->sendApiResonse();
    }
    
    
    
    public function getClints($id){
        $user = AgentUser::find($id);
        $user->load(['users']);

        $this->setData($user);
        $this->setMessage("success");
        return $this->sendApiResonse();
    }

    public function recoredNotice(Request $request){
        $request->validate([
            'user_id'=>'required|numeric|exists:users,id',
            'agent_id'=>'required|numeric|exists:agent_users,id',
            'content'=>'required|string|min:6',
        ]);
        $notes = AgentNotes::create([
            'user_id'=>$request->user_id,
            'agent_id'=>$request->agent_id,
            'content'=>$request->content,
        ]);
        $data = AgentNotes::where('agent_id',$request->agent_id)->get();
        $this->setData($data);
        $this->setMessage("success");
        return $this->sendApiResonse();
    }
    
    public function recoredNoticeAgent(Request $request){
        $request->validate([
            'id'=>'required|numeric|exists:admins,id',
            'content'=>'required|string|min:6',
        ]);
        $admin = Admin::find($request->id);
        if($admin && $admin->manager_id != null){
             $notes = TeamleaderNotes::create([
            'team_id'=>$admin->manager_id,
            'agent_id'=>$request->id,
            'message'=>$request->content,
        ]);
        $data = TeamleaderNotes::where('agent_id',$request->agent_id)->get();
        $this->setData($data);
        $this->setMessage("success");
        }
        $this->setStatus(422);
       $this->setMessage("error");
        return $this->sendApiResonse();
    }


    public function Verify(Request $request){
        foreach($request->ids as $value){
            Admin::where('id',$value)->update(['email_verified_at' => Carbon::now()]);
        }
        $this->setMessage("Admin\'s email verified successfully");
        return $this->sendApiResonse();
    }
    public function assignUsers(Request $request){
        $request->validate([
            'user_id'=>'required|array',
            'user_id.*'=>'required|numeric|exists:users,id',
            'agent_id'=>'required|numeric|exists:admins,id',
        ]);
        foreach($request->user_id as $value){
            $users = AgentUser::create([
                'user_id'=>$value,
                'agent_id'=>$request->agent_id,
                'status'=>'1',
            ]);
        }
        $this->setMessage("success");
        return $this->sendApiResonse();
    }


    public function DeleteClient(Request $request){
        foreach($request->ids as $value){
            $users = AgentUser::find($value);
            $users->delete();
        }
        $this->setMessage("success");
        return $this->sendApiResonse();
    }
    public function destroy(Request $request){
        $request->validate([
            'ids'=>'required|array',
            'ids.*'=>'required|numeric|exists:admins,id',
        ]);
        
        foreach($request->ids as $value){
            $users = Admin::find($value);
            $users->delete();
        }
        $this->setMessage("success");
        return $this->sendApiResonse();
    }
    
    
    public function FilterByText(Request $request)
    {
        // Start a query on the Admin model (or your relevant model)
        // $query = Admin::query();
    
        // // Apply filters conditionally using the `when` method
        // $query->when($request->filled('email'), function ($q) use ($request) {
        //     $q->where('email', 'LIKE', '%' . $request->input('email') . '%');
        // });
    
        // $query->when($request->filled('country'), function ($q) use ($request) {
        //     $q->where('country', 'LIKE', '%' . $request->input('country') . '%');
        // });
    
        // $query->when($request->filled('phone'), function ($q) use ($request) {
        //     $q->where('phone', 'LIKE', '%' . $request->input('phone') . '%');
        // });
    
        // $query->when($request->filled('amount'), function ($q) use ($request) {
        //     $q->where('amount', 'LIKE', '%' . $request->input('amount') . '%');
        // });
    
        // $query->when($request->filled('currency'), function ($q) use ($request) {
        //     $q->where('currency', 'LIKE', '%' . $request->input('currency') . '%');
        // });
    
        // Get the filtered results with pagination
        $users = $this->searchAgents->index($request);
    
        // Set data and message for API response
        $this->setData($users);
        $this->setMessage("success");
    
        // Return the response
        return $this->sendApiResonse();
    }
    

    public function Filter(Request $request){
        
         $users = $this->filterAgents->index($request);
        $this->setData($users);
        $this->setMessage("success");
        return $this->sendApiResonse();
    }
}
