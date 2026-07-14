<?php

namespace App\Http\Controllers\admin\CRM\Api\Users\Customer;

use App\Http\Controllers\Controller;
use App\Models\AgentUser;
use App\Models\IBClient;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\UserManager;
use App\Models\Permission;
use Illuminate\Support\Facades\Log;

class ActiveController extends Controller
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
    public function index(Request $request){
        // dd("test");
        $users = $this->getUsers();
        $this->setData($users);
        $this->setMessage("success");
        return $this->sendApiResonse();
    }

    public function getUsers(){
        $data = [];
        if(auth()->user()->type_id == 3){
            $data = User::where('type_id',2)->paginate(15);
        }else if(auth()->user()->type_id == 4){
            $ids = UserManager::where('admin_id',auth()->user()->id)->pluck('user_id');
            $data = User::whereIn('id',$ids)->where('type_id',2)->paginate(15);
        }else if(auth()->user()->type_id == 5){
            $ids = IBClient::where('ib_id',auth()->user()->id)->pluck('user_id');
            $data = User::whereIn('id',$ids)->where('type_id',2)->paginate(15);
        }else{
            $ids = AgentUser::where('agent_id',auth()->user()->id)->pluck('user_id');
            $data = User::whereIn('id',$ids)->where('type_id',2)->paginate(15);
        }
        return  $data;
    }

    public function all(){
        $users = User::where('type_id',5)->get();
        $this->setData($users);
        $this->setMessage("success");
        return $this->sendApiResonse();
    }
    public function ConvertUSers(Request $request){
        // Validate the request
        $request->validate([
            'ids' => 'required|array',
            'ids.*' => 'required|numeric|exists:users,id', // Ensure each ID exists
            'type' => 'required|numeric', // Ensure type is numeric
        ]);

        // Keep track of updated users
        $updatedUsers = [];

        foreach($request->ids as $value){
            $user = User::find($value);
            if ($user) { // Check if the user exists
                $user->type_id = $request->type;
                $user->save();
                $updatedUsers[] = $user; // Optionally track which users were updated
            } else {
                // Log the missing user ID or handle the case as needed
                \Log::warning("User with ID $value not found.");
            }
        }

        // Optionally return the updated users or a count
        $this->setMessage("success");
        return $this->sendApiResonse(['updated_users' => $updatedUsers]); // Include updated users if needed
    }



    public function destroy(Request $request){
        // Validate the request to ensure ids are provided
        $request->validate([
            'ids' => 'required|array',
            'ids.*' => 'required|numeric|exists:users,id', // Ensure each ID exists
        ]);

        // Iterate through the provided IDs and attempt to delete each user
        foreach($request->ids as $value){
            $user = User::find($value);
            if ($user) { // Check if the user exists
                $user->delete(); // Delete the user
            } else {
                // Log or handle the case of a missing user
                \Log::warning("User with ID $value not found for deletion.");
            }
        }

        $this->setMessage("success");
        return $this->sendApiResonse();
    }

}
