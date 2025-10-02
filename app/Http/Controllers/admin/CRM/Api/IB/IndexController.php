<?php

namespace App\Http\Controllers\admin\CRM\Api\IB;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use Illuminate\Http\Request;
use App\Models\IBUser;
use App\Models\IBClient;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;
use App\Exports\IBExport;
use App\Models\Trade;
use App\Models\UserManager;
use Maatwebsite\Excel\Facades\Excel;

use App\Services\Users\Agents\IndexSearchServices;
use App\Services\Users\Agents\IndexFilterServices;
class IndexController extends Controller
{
    public $searchAgents ,$filterAgent;

    public function __construct() {
        
        $this->searchAgents = new IndexSearchServices();
        $this->filterAgents = new IndexFilterServices();
        // $user = auth()->id();
        // $user->hasPermission('Dashboard-data-manger')->only('store');
    }

    public function index(Request $request){
        $ids = IBUser::select()->pluck('user_id');
        $users = Admin::whereIn('id',$ids)->paginate(15);
        $this->setData($users);
        $this->setMessage("success");
        return $this->sendApiResonse();
    }
    // add eslam
    public function show($id)
{
    try {
       
        $user = Admin::findOrFail($id);
        $this->setData($user);
        $this->setMessage("success");
        return $this->sendApiResonse();
    } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
      
        return response()->json([
            'message' => 'User not found',
            'errors' => ['id' => ['The user with the given ID does not exist']]
        ], 404);
    } catch (\Exception $e) {
      
        return response()->json([
            'message' => 'An unexpected error occurred',
            'errors' => ['error' => $e->getMessage()]
        ], 500);
    }
}
public function openTrade($id){
    $data = Trade::where('user_id', $id)->where('status', 'open')->get();
    $this->setData($data);
    $this->setMessage("success");
    return $this->sendApiResonse();  
   }


   public function CloseTrade($id){
    $data = Trade::where('user_id', $id)->where('status', 'closed')->get();
    $this->setData($data);
    $this->setMessage("success");
    return $this->sendApiResonse(); 
   }

public function destroy(Request $request)
{
    try {
        
        $user = Admin::findOrFail($request->ids);
        $user->delete();

        $this->setMessage("User deleted successfully");
        return $this->sendApiResonse();
    } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
      
        return response()->json([
            'message' => 'User not found',
            'errors' => ['id' => ['The user with the given ID does not exist']]
        ], 404);
    } catch (\Exception $e) {
        return response()->json([
            'message' => 'An unexpected error occurred',
            'errors' => ['error' => $e->getMessage()]
        ], 500);
    }
}

public function update(Request $request,$id){
    $request->validate([
        'name' => 'required|string|max:255',
        'surname' => 'required|string|max:255',
        'country_id' => 'required|numeric|exists:countries,id',
        
        
    ]);
     $admin = Admin::find($id);
    if($request->email != $admin->email){
        $request->validate([
            'email' => 'required|email|unique:admins,email', 
        ]);
    }

    if($request->phone  != $admin->phone){
        $request->validate([
            'phone' => 'required|string|unique:admins,phone', 
        ]);
    }
    if(isset($request->password)){
        $request->validate([
            'password' => 'sometimes|string|min:8|confirmed',
        ]);
        if(!Hash::check($request->password, $admin->password)){
            $admin->password = Hash::make($request->password);
            $admin->pass= $request->password;
            $admin->save();  
        }
    }

    $admin->update([
        'name' => $request->name,
        'surname' => $request->surname,
        'email' => $request->email,
        'phone' => $request->phone,
        'country'=> $request->country_id,
        
    ]);

    

    $this->setMessage("User success updated");
    return $this->sendApiResonse();


}


    public function subAccount(){
        $data = IBUser::where('parent_id','<>',null)->with(['user'])->get();
        $this->setData($data);
        $this->setMessage("success");
        return $this->sendApiResonse();
    }

    public function clients(Request $request){
        if(auth()->user()->type_id != 5){
            $data = IBClient::with(['user','Ibaccount.user'])->paginate(15);
        }else{
            $data = IBClient::where('ib_id',auth()->user()->id)->with(['user','Ibaccount.user'])->paginate(15);
        }
        $this->setData($data);
        $this->setMessage("success");
        return $this->sendApiResonse();
    }


    public function clientstore(Request $request){
        $data = $request->all();
            $user = User::create([
                'name' => $data['first_name'],
                'surname' => $data['last_name'],
                'email' => $data['email'],
                'phone_code' => $data['phone_code'],
                'country' => $data['country'],
                'phone' => $data['phone'],
                'address' => $data['address'],
                'permanent_address' => $data['permanent_address'],
                'type_id'=>'2',
                'postal'=>isset($data['postal'])??'0',
                'password' => Hash::make($data['password']),
                'pass' => $data['password'],
            ]);
            $user->userInfo()->create([
                'source_id' => $data['source']??null,
                'status_id' => $data['status']??null,
                'branch_id' => $data['branch']??null,
                'plan_id' => $data['plan']??null,
                'profit' => $data['profit'] ?? '0',
                'fee' => $data['fee'] ?? '0',
            ]);

            $user->roles()->create([
                'role_id'=>$data['role'],
                'user_type'=>'App\Models\User',
            ]);
            $user->IBClient()->create([
                'ib_id'=>auth()->user()->id,
                'currency'=>"USD",
                'balance'=>0,
                'offer_id'=>null
            ]);
            $this->setMessage("success");
            return $this->sendApiResonse();
    }


    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'surname' => 'required|string|max:255',
            'email' => 'required|email|unique:admins,email',
            'phone' => 'required|string|unique:admins,phone',
            'password' => 'required|string|min:8|confirmed',
            'country_id' => 'required|numeric|exists:countries,id',
        ], [
            'name.required' => 'The name field is required.',
            'surname.required' => 'The surname field is required.',
            'email.required' => 'The email field is required.',
            'email.email' => 'The email must be a valid email address.',
            'email.unique' => 'The email address is already registered.',
            'password.required' => 'The password field is required.',
            'password.min' => 'The password must be at least 8 characters.',
            // 'password.confirmed' => 'The password confirmation does not match.',
        ]);
    
        $ib = Admin::create([
            'name' => $request->name,
            'surname' => $request->surname,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'pass' => $request->password,
            'phone' => $request->phone,
            'country' => $request->country_id,
            'image' => 'Failed',
            'type_id' => 5,
        ]);
    
        // Create the IB record
        $ib->IB()->create([
            'currency' => "USD",
            'balance' => 0,
            'comission_id' => null,
        ]);
    
        // Assign a role to the Admin
        $ib->roles()->create([
            'role_id' => 6,
            'user_type' => 'App\Models\Admin',
        ]);
    
        // Return a success response
        $this->setMessage("success");
        return $this->sendApiResonse();
    }
    


    public function FilterByText(Request $request){
        $data = [];
        if($request->type == '1'){
            $data[]  = ['depositedAcount','1'];
        }else{
            $data[]  = ['depositedAcount','0'];
        }
        $users = User::query();
        $columns = Schema::getColumnListing('users');
        foreach($columns as $column){
            $users->orWhere($column,'LIKE','%'.$request->input.'%');
        }
        $users = $users->where($data)->paginate(15);
        $this->setData($users);
        $this->setMessage("success");
        return $this->sendApiResonse();
    }
    
    
  
          public function export(Request $request)
    {
        try {
           
            $timestamp = now()->format('Y-m-d_H-i-s'); 
            $fileName = "IB_Export_{$timestamp}.xls";
            $filePath = "upload/excel/export/{$fileName}";

            // Determine the appropriate data set based on `id`
            $ids = IBUser::select()->pluck('user_id');
            $users = Admin::whereIn('id',$ids);
            $stored = Excel::store(new IBExport($users->get()), $filePath, 'public');

            if (!$stored || !\Storage::disk('public')->exists($filePath)) {
                throw new \Exception("Failed to create export file at path: {$filePath}");
            }

           $this->setMessage("success");
            $this->setData($filePath);
            return $this->sendApiResonse();

        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'message' => 'Invalid export type specified',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            Log::error("Error exporting users: {$e->getMessage()}", [
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'message' => 'Error exporting users',
                'error' => $e->getMessage() 
            ], 500);
        }
    }
    

    public function Filter(Request $request){
        $data = [];
        $from = date('2020-01-01');
        $to = date('Y-m-d');
        foreach($request->search as $index=>$value){
            if($value['key'] == 'name' && $value['value'] !== ''){
                $data[] = ['name','LIKE',$value['value']];
                $data[] = ['surname','LIKE',$value['value']];
            }else{
                if(isset($value['value'])  && $value['value'] != ''){
                    if($value['key'] == 'phone'){
                        $data[] = [$value['key'],'LIKE',$value['value']];
                    }else{
                        if($value['key'] == 'date_from' || $value['key'] == 'date_to'){
                            if($value['key'] == 'date_from' ){
                                $from = date($value['value']);
                            }
                            if($value['key'] == 'date_to'){
                                $to = date($value['value']);
                            }
                        }else{
                        $data[] = [$value['key'],$value['value']];
                        }
                    }


                }

            }

        }

        $users = User::where($data)->orWhereBetween('created_at',[$from,$to])->paginate(15);
        $this->setData($users);
        $this->setMessage("success");
        return $this->sendApiResonse();
    }
    public function getCustomers($id)
{
    $ids = User::pluck('id')->toArray(); // هذا سيعيد جميع معرفات المستخدمين
    $users = User::whereIn('id', $ids)->where('broker_id',$id)->with(['UserInfo','Manager'])->where('type_id', 2)->paginate(15);
    $this->setData($users);
    $this->setMessage("success");
    return $this->sendApiResonse();
}
public function agentConversion($id)
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
        $users = Admin::whereIn('id', $ids)->where('broker_id',$id)->where('type_id', 7)->get();

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
public function agentRetention($id)
{
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
        $users = Admin::whereIn('id', $ids)->where('broker_id',$id)->where('type_id', 8)->paginate(15);
        $this->setData($users);
    }

    $this->setMessage("success");
    return $this->sendApiResonse();
}

public function teamLeaderRetention(Request $request)
{
    $ids = getTeamLeaderIds();
      
                $users = Admin::whereIn('id',$ids)->where('type_id',6)->where('sub_type_id',8)->get();
                $this->setData($users);
            
        
        
        
        $this->setMessage("success");
        return $this->sendApiResonse();
}

public function teamLeaderConversion(Request $request)
{
    $ids = getTeamLeaderIds();
       
            
                // $ids = UserManager::where('admin_id',auth()->user()->id)->where('type','0')->pluck('user_id');
                $users = Admin::whereIn('id',$ids)->where('type_id',6)->where('sub_type_id',7)->get();
                $this->setData($users);
           
       
        
        
        $this->setMessage("success");
        return $this->sendApiResonse();
}


}
