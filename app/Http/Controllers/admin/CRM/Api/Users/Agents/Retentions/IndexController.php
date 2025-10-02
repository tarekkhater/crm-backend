<?php

namespace App\Http\Controllers\admin\CRM\Api\Users\Agents\Retentions;
use Exception;
use App\Models\Permission;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\AgentUser;
use App\Models\Admin;
use App\Models\User;
use App\Models\AgentNotes;
use App\Models\UserManager;
use Carbon\Carbon;
use Illuminate\Support\Facades\Exceptions;
use Illuminate\Support\Facades\Hash;
class IndexController extends Controller
{
    public function __construct()
    {
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
    public function all()
    {
        try {
            // Check authentication
            if (!auth()->check()) {
                $this->setMessage("Unauthorized access.");
                return $this->sendApiResonse();
            }

            // Fetch agent IDs directly within the method
            $currentUserTypeId = auth()->user()->type_id;
            $ids = ($currentUserTypeId != 6)
                ? Admin::where('type_id', 7)->pluck('id')->toArray()
                : UserManager::where('admin_id', auth()->user()->id)
                    ->where('type', '1')
                    ->pluck('user_id')
                    ->toArray();

            // Fetch users based on the retrieved IDs
            $users = Admin::whereIn('id', $ids)->where('type_id', 7)->get();

            // Check if users were found
            if ($users->isEmpty()) {
                $this->setMessage("No agents found.");
            } else {
                $this->setData($users);
                $this->setMessage("Agents fetched successfully.");
            }

            return $this->sendApiResonse();
        } catch (\Exception $e) {
            // Handle exceptions
            $this->setMessage("An error occurred: " . $e->getMessage());
            return $this->sendApiResonse();
        }
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
    public function index(Request $request)
{
    // If the logged-in user is not type_id 6, retrieve agent IDs directly
    if (auth()->user()->type_id != 6) {
        // You can place the logic for fetching agent IDs directly here
        $ids = Admin::where('type_id', 8)->pluck('id');  // Fetch all agent IDs with type_id 8
        $users = Admin::whereIn('id', $ids)->paginate(15);
        $this->setData($users);
    } else {
        // If the logged-in user is of type_id 6, fetch specific agent IDs from UserManager
        $ids = UserManager::where('admin_id', auth()->user()->id)
                          ->where('type', '1')
                          ->pluck('agent_id');
        $users = Admin::whereIn('id', $ids)->where('type_id', 8)->paginate(15);
        $this->setData($users);
    }

    $this->setMessage("success");
    return $this->sendApiResonse();
}



    public function canFund(Request $request)
{
    // Validate the request to ensure 'id' is provided
    $request->validate([
        'id' => 'required|numeric|exists:admins,id', // Ensure the admin ID is valid
    ]);

    // Find the user by ID
    $user = Admin::find($request->id);

    // Check if the user exists
    if (!$user) {
        $this->setMessage("User not found.");
        return $this->sendApiResonse();
    }

    // Toggle the can_add_fund property
    $user->can_add_fund = $user->can_add_fund ? 0 : 1;

    // Save the changes
    $user->save();

    $this->setMessage("Successfully updated the user's ability to add funds.");
    return $this->sendApiResonse();
}
    public function show($id){
        $users = Admin::with(['clients.user'])->where('id',$id)->first();
        $this->setData($users);
        $this->setMessage("success");
        return $this->sendApiResonse();
    }
    public function assignUser(Request $request){
        $request->validate([
            'user_id'=>'required|numeric|exists:users,id',
            'agent_id'=>'required|numeric|exists:users,id',
        ]);
        foreach($request->ids as $value){
            $users = AgentUser::create([
                'user_id'=>$value,
                'agent_id'=>$request->agent_id,
                'status'=>'1',
            ]);
        }
        $this->setMessage("success");
        return $this->sendApiResonse();
    }
    public function Blocked(Request $request){
        $request->validate([
            'id' => 'required|numeric|exists:admins,id', // Ensure the ID exists
        ]);

        $user = Admin::find($request->id);

        if (!$user) {
            return response()->json(['message' => 'User not found.'], 404);
        }

        $user->block = $user->block == '1' ? '0' : '1';
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


    public function store(Request $request){
        // $this->validate($request, [
        //     'name' => ['required', 'string', 'max:255'],
        //     'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
        //     'branch_id' => ['required', 'numeric', 'exists:branchs,id'],
        //     'password' => ['required', 'string', 'min:6','required_with:confirm_password','same:confirm_password','min:6'],             // must be at least 7 characters in length
        // ]);
        try{
            $data = $request->all();
            $manager = Admin::find($data['manager_id']);
            $user = Admin::create([
                'name' => $data['name'],
                'email' => $data['email'],
                'branch_id' => $manager->branch_id,
                'type_id' => $manager->sub_type_id,
                'sub_type_id' => $manager->sub_type_id,
                'password' => Hash::make($data['password']),
                'pass' => $data['password'],
                'image' => 'faild',
            ]);
            $user->roles()->create([
                'role_id'=>$data['role'],
                'user_type'=>'App\Models\Admin',
            ]);
            $user->usermanager()->create([
                'admin_id'=>$data['manager_id'],
                'type'=>'1',
            ]);

        $this->setMessage("success");
        return $this->sendApiResonse();
        }catch(Exception $e){
            return $e;
        }

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


    public function Verify(Request $request){
        foreach($request->ids as $value){
            Admin::where('id',$value)->update(['email_verified_at' => Carbon::now()]);
        }
        $this->setMessage("Admin\'s email verified successfully");
        return $this->sendApiResonse();
    }
    public function assignUsers(Request $request){
        // dd("test");
        $request->validate([
            'user_id'=>'required|array',
            'user_id.*'=>'required|numeric|exists:users,id',
            'agent_id'=>'required|numeric|exists:users,id',
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
        foreach($request->ids as $value){
            $users = Admin::find($value);
            $users->delete();
        }
        $this->setMessage("success");
        return $this->sendApiResonse();
    }
}
