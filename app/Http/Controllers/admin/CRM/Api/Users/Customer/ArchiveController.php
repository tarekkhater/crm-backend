<?php

namespace App\Http\Controllers\admin\CRM\Api\Users\Customer;
use App\Models\Permission;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
class ArchiveController extends Controller
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
    public function index(Request $request){

        $users = User::where('type_id',6)->paginate(15);
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
