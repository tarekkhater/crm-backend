<?php

namespace App\Http\Controllers\admin\CRM\Api\Users;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use App\Imports\LeadImport;
use App\Imports\UserClientImport;
use App\Exports\UsersClientExport;
use App\Exports\UsersLeadExport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Exceptions;
use Illuminate\Support\Facades\Schema;
use App\Models\Message;
use App\Services\Users\Leads\IndexSearchServices;
use App\Services\Users\Leads\IndexFilterServices;
use App\Models\AgentNotes;
use App\Models\Transaction;
use Illuminate\Support\Facades\Validator;
class IndexController extends Controller
{
    public $searchpotential;
    public function __construct() {
        // $user = auth()->id();
        // $user->hasPermission('Dashboard-data-manger')->only('store');
                $this->searchpotential = new IndexSearchServices();
                $this->filterpotential = new IndexFilterServices();

    }

    public function index(){
        $user = Auth::user();
        $this->setMessage("success");
        return $this->sendApiResonse();
    }


    public function profile(){
        $user = Auth::user();
        $this->setData($user);
        $this->setMessage("success");
        return $this->sendApiResonse();
    }
    

    public function show($id){
        $user = User::find($id);
        $user->load('activePlans','identity','plans','deposits','trades');

        $this->setData($user);
        $this->setMessage("success");
        return $this->sendApiResonse();
    }

    public function store(Request $request){
        // ,'exists:type_users,id'
        $this->validate($request, [
            'first_name' => ['required', 'string', 'max:255'],
            'last_name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'country' => ['required','numeric'],
            'type' => ['required','numeric'],
            'phone' => ['nullable'],
            'address' => ['nullable'],
            'permanent_address' => ['nullable'],
            'password' => ['required', 'string', 'min:6','required_with:confirm_password','same:confirm_password','min:6'],             // must be at least 10 characters in length
        ]);

            $data = $request->all();
            $user = User::create([
                'name' => $data['first_name'],
                'surname' => $data['last_name'],
                'email' => $data['email'],
                'phone_code' => $data['phone_code']?$data['phone_code']:'+20',
                'country' => $data['country'],
                'phone' => $data['phone'],
                'address' => $data['address'],
                'permanent_address' => $data['permanent_address'],
                'type_id'=>$data['type'],
                'postal'=>isset($data['postal'])??'0',
                'password' => Hash::make($data['password']),
                'pass' => $data['password'],
            ]);
            $user->userInfo()->create([
                'source_id' => $data['source']??null,
                'status_id' => isset($data['status'])?$data['status']:3,
                'branch_id' => $data['branch']??null,
                'plan_id' => $data['plan']??null,
                'profit' => $data['profit'] ?? '0',
                'fee' => $data['fee'] ?? '0',
            ]);

            // $user->roles()->create([
            //     'role_id'=>$data['role'],
            //     'user_type'=>'App\Models\User',
            // ]);

            if(Auth()->user()->type_id == 5){
                $user->IBClient()->create([
                    'ib_id'=>auth()->user()->id,
                    'currency'=>"USD",
                    'balance'=>0,
                    'offer_id'=>null
                ]);
            }


        $this->setMessage("success");
        return $this->sendApiResonse();
    }
    
    public function storeAbstractDesposit(Request $request){
            $this->validate($request, [
            'id' => ['required', 'integer'],
            'amount' => ['required'],
            
            ]);
        $data = $request->all();
        $data['account_type'] = $data['type'];
        if($request->get('note')){
            $note = $data['note'];
        }else{
            $note = 'Admin '. $data['account_type'];
        }
        $user = User::findOrFail($data['id']);
        $user->load("userInfo");
        if($data['type'] == 'deposit'){
            $user->userInfo->balance = (float)$user->aBalance() + (float)$data['amount'];
        }elseif($data['type'] == 'bonus'){
            $user->userInfo->bonus = (float)$user->userInfo->bonus + (float)$data['amount'];
        }else{
            $user->userInfo->money = (int)$user->aBalance() - (int)$data['amount'];
        }

        $user->userInfo->save();
        Transaction::create(['user_id' => $data['id'], 'amount' => $data['amount'], 'type' => $data['type'], 'account_type' => $user->type_id,'note' => $note]);
        // if($data['notify'] > 0){
        //     $this->message($user, $note,'Account fund updated');
        // }
            $this->setMessage("Successful, balance modified");
            return $this->sendApiResonse();
    }

    public function updated(Request $request, $id)
    {
        $this->validate($request, [
            'first_name' => ['sometimes', 'string', 'max:255'],
            'last_name' => ['sometimes', 'string', 'max:255'],
            'email' => ['sometimes', 'string', 'email', 'max:255', 'unique:users,email,' . $id],
            'country' => ['sometimes', 'numeric'],
            'type' => ['sometimes', 'numeric', 'exists:type_users,id'],
            'phone' => ['sometimes'],
            'manager_id' => ['sometimes', 'exists:managers,id'],  // Validate manager_id exists in the managers table
            'address' => ['sometimes'],
            'permanent_address' => ['sometimes'],
            'password' => [
                'sometimes',
                'string',
                'min:6',
                'required_with:confirm_password',  // Make sure confirm_password is present when password is present
                'same:confirm_password',  // Ensure passwords match
            ],
            'confirm_password' => ['sometimes', 'string', 'min:6', 'same:password'],  // Ensure confirm_password matches password
        ]);
    
        try {
            $data = $request->all();
            $user = User::findOrFail($id); // Ensure user exists before updating
        
            // Prepare data for updating
            $updateData = [
                'first_name' => $data['first_name'] ?? $user->first_name,
                'last_name' => $data['last_name'] ?? $user->last_name,
                'email' => $data['email'] ?? $user->email,
                'is_active' => true,
                'can_trade' => 1,
                'type_id' => $data['type'] ?? $user->type_id,
                'source' => $data['source'] ?? $user->source,
                'status' => $data['status'] ?? $user->status,
                'phone_code' => $data['phone_code'] ?? $user->phone_code,
                'country' => $data['country'] ?? $user->country,
                'phone' => $data['phone'] ?? $user->phone,
                'manager_id' => $data['manager_id'] ?? $user->manager_id, // Use the provided manager_id or keep the existing one
                'address' => $data['address'] ?? $user->address,
                'permanent_address' => $data['permanent_address'] ?? $user->permanent_address,
                'profit' => $data['profit'] ?? $user->profit,
                'fee' => $data['fee'] ?? $user->fee,
            ];
        
            // Hash password only if it is provided
            if (!empty($data['password'])) {
                $updateData['password'] = Hash::make($data['password']);
                $updateData['pass'] = $data['password'];
            }
        
            $user->update($updateData);
        
            $this->setMessage("success");
            return $this->sendApiResonse();
        } catch (Exception $e) {
            return response()->json([
                'message' => 'Error occurred',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
    
    

    public function importLeads(Request $request)
    {
        // Step 1: Validate the file is present and of the correct type
        $validator = Validator::make($request->all(), [
            'file' => 'required|file|mimes:xlsx,xls,csv',
            'type' => 'nullable|integer' // Optional filtering type for import
        ]);

        // If validation fails, return a detailed error response
        if ($validator->fails()) {
            return response()->json([
                'message' => 'The given data was invalid.',
                'errors' => $validator->errors(),
            ], 422);
        }

        try {
            // Retrieve the file and optional type
            $file = $request->file('file');
            $type = $request->input('type', null); // Defaults to null if not provided

            // Check if file is uploaded and initiate import
            if ($file) {
                Excel::import(new UserClientImport($type), $file);

                // Return success response
                return response()->json([
                    'message' => 'File imported successfully',
                ], 200);
            } else {
                return response()->json([
                    'message' => 'No file provided.',
                ], 400);
            }

        } catch (\Exception $e) {
            // Catch and log any exceptions during import
            \Log::error('File import failed', ['error' => $e->getMessage()]);

            return response()->json([
                'message' => 'File import failed',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
    
    
    
    public function ExportLeads(Request $request)
    {
        try {
            $request->validate(['id' => 'required|in:0,1,2,5,9']); 
            $timestamp = now()->format('Y-m-d_H-i-s'); 
            $fileName = "Users_Export_{$timestamp}.xls";
            $filePath = "upload/excel/export/{$fileName}";

            // Determine the appropriate data set based on `id`
            $users = match ($request->id) {
                0 => $this->leads($request),
                1 => $this->potential($request),
                2 => $this->active($request),
                5 => $this->publicCustomer($request),
                9 => $this->archiveCustomer($request),
                default => $this->ftd($request),
            };
return $user;
            $stored = Excel::store(new UsersExport($users->get()), $filePath, 'public');

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
            Log::error("Error exporting users: {$e->getMessage()}", [
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'message' => 'Error exporting users',
                'error' => $e->getMessage() 
            ], 500);
        }
    }
    
    
    



    public function Updateproperties(Request $request){
        foreach($request->ids as $value){
            $users = User::find($value);
            $users->source = $request->source??$users->source;
            $users->status = $request->status??$users->status;
            $users->block = $request->status??$users->block;
            $users->save();
        }
        $this->setMessage("success");
        return $this->sendApiResonse();
    }




    public function FilterByText(Request $request){
        // $data = [];
        // if($request->type == '1'){
        //     $data[]  = ['depositedAcount','1'];
        // }else{
        //     $data[]  = ['depositedAcount','0'];
        // }
        // $users = User::query();
        // $columns = Schema::getColumnListing('users');
        // foreach($columns as $column){
        //     $users->orWhere($column,'LIKE','%'.$request->input.'%');
        // }
        // $users = $users->where($data)->paginate(15);
        $users = $this->searchpotential->index($request);
        $this->setData($users);
        $this->setMessage("success");
        return $this->sendApiResonse();
    }

    public function Filter(Request $request){
        
         $users = $this->filterpotential->index($request);
        $this->setData($users);
        $this->setMessage("success");
        return $this->sendApiResonse();
        
        
        $data = [];
        $from = date('2020-01-01');
        $to = date('Y-m-d');
        foreach($request->search as $index=>$value){
            if(isset($value['value']) && $value['value'] !== null){
                switch($value['key']):
                    case "name":
                        $data[] = [$value['key'],'LIKE','%'.$value['value'].'%'];
                        break;
                    case "surname":
                        $data[] = [$value['key'],'LIKE','%'.$value['value'].'%'];
                        break;
                    default:
                    if(isset($value['value'])  && $value['value'] != ''){
                        if($value['key'] == 'phone' || $value['key'] == 'email'){
                            $data[] = [$value['key'],'LIKE','%'.$value['value'].'%'];
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
                    break;
                endswitch;
            }

        }

        $users = User::where($data)->WhereBetween('created_at',[$from,$to])->paginate(15);
        $this->setData($users);
        $this->setMessage("success");
        return $this->sendApiResonse();
    }
    
    public function storeNotes(Request $request){
        $request->validate([
            'ids'=>'required|array',
            'ids.*'=>'required|numeric|exists:users,id',
            'note'=>'required|string',
            'contacted'=>'required',
        ]);
        foreach($request->ids as $id){
            if($id != null){
            AgentNotes::create([
                'agent_id'=>1,
                'user_id'=>$id,
                'content'=>$request->note,
                'message_by'=>'1'
            ]);
            }
        }
        $this->setMessage("success");
        return $this->sendApiResonse();
    }
     public function storeMessage(Request $request){
        
    
        $request->validate([
            'ids'=>'required|array',
            'ids.*'=>'required|numeric|exists:users,id',
            'message'=>'required|string',
            'subject'=>'required|string',
        ]);
        foreach($request->ids as $id){
            if($id != null){
                Message::create([
                'user_id'=>$id,
                'message'=>$request->message,
                'subject'=>$request->subject,
            ]);
            }
        }
        $this->setMessage("success");
        return $this->sendApiResonse();
    }
}
