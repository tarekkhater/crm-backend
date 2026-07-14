<?php

namespace App\Http\Controllers\admin\CRM\Api\Users\Customer;
use App\Models\Permission;
use App\Exports\UsersClientExport;
use App\Exports\UsersLeadExport;
use App\Http\Controllers\Controller;
use App\Models\AgentUser;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\UserManager;
use App\Models\IBClient;
use App\Models\Wallet;
use App\Models\Deposit;
use App\Models\Withdrawal;
use App\Models\Identity;
use App\Models\Message;
use App\Models\Trade;
use App\Models\AgentNotes;
use App\Http\Resources\CRM\APi\User\UserResource;

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
    public function index(Request $request) {
        // الحصول على معرفات المستخدمين بطريقة مباشرة
        $ids = User::pluck('id')->toArray(); // هذا سيعيد جميع معرفات المستخدمين
        $users = User::whereIn('id', $ids)->where('type_id', 2)->paginate(15);
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


        public function show($id){
        $user = User::find($id);
        $user->load(['UserInfo','Payments','myWithdrawals','deposits','transactions','accounts','wireAccounts','trades','messages']);
        $this->setData(new UserResource($user));
        $this->setMessage("success");
        return $this->sendApiResonse();
        }


   public function Deposit($id){
    $result = Deposit::where('user_id',$id)->with(['user','user.TradingAccount','plan','account'])->paginate(15);     
    $this->setData($result);
    $this->setMessage("success");
    return $this->sendApiResonse();   
   }
//    public function export()
//    {
//        try {
//            // Store the export as an Excel file
//            Excel::store(new UsersClientExport(), 'upload/excel/export/users.xls', 'public');

//            // Set the response message and data
//            $this->setMessage("success");
//            $this->setData('upload/excel/export/users.xls');

//            return $this->sendApiResonse();
//        } catch (\Exception $e) {
//            // Handle any exceptions and provide a meaningful error response
//            return response()->json([
//                'message' => 'Export failed',
//                'error' => $e->getMessage()
//            ], 500);
//        }
//    } 
public function ExportLeads(Request $request)
{
    try {
        $request->validate(['type' => 'required|in:1,2']); // Validate 'type' to be either 1 or 2

        $timestamp = now()->format('Y-m-d_H-i-s'); 
        $fileName = $request->type == 1 ? "Clients_{$timestamp}.xls" : "Leads_{$timestamp}.xls";
        $filePath = "upload/excel/export/{$fileName}";

        // Instantiate export class
        $exportClass = new UsersLeadExport($request);

        // Store the Excel file
        $stored = Excel::store($exportClass, $filePath, 'public');

        if (!$stored || !\Storage::disk('public')->exists($filePath)) {
            throw new \Exception("Failed to create export file at path: {$filePath}");
        }

        return response()->json([
            'message' => "Export successful",
            'file_path' => $filePath
        ]);

    } catch (\Illuminate\Validation\ValidationException $e) {
        return response()->json([
            'message' => 'Invalid export type specified',
            'errors' => $e->errors()
        ], 422);
    } catch (\Exception $e) {
        Log::error("Error exporting leads: {$e->getMessage()}", [
            'trace' => $e->getTraceAsString()
        ]);

        return response()->json([
            'message' => 'Error exporting leads',
            'error' => $e->getMessage() 
        ], 500);
    }
}
   public function Withdrawals($id){
    $result = Withdrawal::where('user_id',$id)->with(['user','user.TradingAccount','plan','account'])->paginate(15);     
    $this->setData($result);
    $this->setMessage("success");
    return $this->sendApiResonse();   
   }

   public function documents($id){
    $result = Identity::where('user_id',$id)->paginate(15);     
    $this->setData($result);
    $this->setMessage("success");
    return $this->sendApiResonse();   
   }
   


   public function openTrade($id){
    $result = Trade::whereStatus(0)->where('user_id',$id)->paginate(15);     
    $this->setData($result);
    $this->setMessage("success");
    return $this->sendApiResonse();   
   }


   public function CloseTrade($id){
    $result = Trade::whereStatus(1)->where('user_id',$id)->paginate(15);     
    $this->setData($result);
    $this->setMessage("success");
    return $this->sendApiResonse();   
   }

  

  

   

        public function transactionwallet($id){
        $wallets = Wallet::where('user_id',$id)->with(['user'])->get();
        $this->setData($wallets);
        $this->setMessage("success");
        return $this->sendApiResonse();
        }
        
        //  public function Deposit($id){
        // $Deposits = Deposit::where('user_id',$id)->with(['user'])->paginate(15);
        // $this->setData($Deposits);
        // $this->setMessage("success");
        // return $this->sendApiResonse();
        // }
        
        // public function Withdrawals($id){
        //     $Deposits = Withdrawal::where('user_id',$id)->with(['user'])->paginate(15);
        // $this->setData($Deposits);
        // $this->setMessage("success");
        // return $this->sendApiResonse();
        // }
        
        public function notes($id){
             $notes = AgentNotes::where('user_id',$id)->with(['user','agent'])->paginate(15);
             $result = [
                'data'         => [],
                'current_page' => $notes->currentPage(),
                'from'         => $notes->firstItem(),
                'last_page'    => $notes->lastPage(),
                'links'        => [],
                'per_page'     => $notes->perPage(),
                'to'           => $notes->lastItem(),
                'total'        => $notes->total(),
             ];
           
            foreach($notes->items() as $note){
               $result['data'][]=[
                    'content'=>$note->content,
                    'message_by'=>$note->message_by == 0?'agent':'teamleader',
                    'agent'=>$note->agent->agent->email,
                    'user'=>$note->user,
                    'created_at'=>date("Y M d",strtotime($note->created_at))
                 ]; 
            }
            $this->setData($result);
            $this->setMessage("success");
            return $this->sendApiResonse();
        }
        
        public function mailing($id){
            $messgaes = Message::where('user_id',$id)->paginate(15);
        // $result = [
        //     'data'         => $this->responseData($messgaes->items()),
        //     'current_page' => $messgaes->currentPage(),
        //     'from'         => $messgaes->firstItem(),
        //     'last_page'    => $messgaes->lastPage(),
        //     'links'        => [],
        //     'per_page'     => $messgaes->perPage(),
        //     'to'           => $messgaes->lastItem(),
        //     'total'        => $messgaes->total(),
        // ];
        $messgaes->load('user');
        $this->setMessage("success");
        $this->setData($messgaes);
        return $this->sendApiResonse();
        }
        
        public function kyc($id){

        $idantity = Identity::where('user_id',$id)->with(['user','modified'])->paginate(15);
        $result = [
            'data'         => $this->responseData($idantity->items()),
            'current_page' => $idantity->currentPage(),
            'from'         => $idantity->firstItem(),
            'last_page'    => $idantity->lastPage(),
            'links'        => [],
            'per_page'     => $idantity->perPage(),
            'to'           => $idantity->lastItem(),
            'total'        => $idantity->total(),
        ];
        
        $this->setMessage("success");
        $this->setData($result);
        return $this->sendApiResonse();
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
    
    public function responseData($documents){
        $data = [];
        foreach($documents as $document){
            if($document->user != null){
            $data [] = [
                'id'=>$document->id,
                'user_id'=>$document->user->id,
                'name'=>$document->user->name != null?$document->user->name:"name",
                'email'=>$document->user->email,
                'number'=>$document->user->phone,
                'front_id'=>baseUrl().$document->front_id,
                'back_id'=>baseUrl().$document->back_id,
                'front_credit_card'=>baseUrl().$document->front_credit_card,
                'back_credit_card'=>baseUrl().$document->back_credit_card,
                'selfie'=>baseUrl().$document->selfie,
                'por'=>baseUrl().$document->por,
                'status'=>$document->status,
                'proof'=>$document->proof,
            ];
            }
        }
        return $data;
    }
}
