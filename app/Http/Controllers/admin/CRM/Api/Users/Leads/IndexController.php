<?php

namespace App\Http\Controllers\admin\CRM\Api\Users\Leads;

use App\Http\Controllers\Controller;

use App\Exports\UsersClientExport;
use App\Exports\UsersLeadExport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\AgentUser;
use App\Models\TypeUser;
use App\Models\Status;
use App\Models\InfoTradeUser;
use App\Services\Users\Leads\IndexServices;
use App\Models\AssignUserManager;
class IndexController extends Controller
{
    public $potential;
    public function __construct() {
        $this->potential = new IndexServices();
    }
    public function index(Request $request)
    {
        $user = $this->potential->index($request);
        if(isset($request->type) && $request->type > 0){
            $idspotinal = InfoTradeUser::whereIn('user_id',getUsersIds())->where('status_id','<>',4)->pluck('user_id');
            $users = User::whereIn('id', $idspotinal)->with(['Manager'])->where('type_id',$request->type)->paginate(15);
        }
        $this->setData($user);
        $this->setMessage("success");
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
    public function Potential(Request $request){
        $this->setData($this->potential->index($request));
        $this->setMessage("success");
        return $this->sendApiResonse();
    }
    
    public function leadCenter(Request $request)
    {
        $statues = Status::get();
        $idspotinal = InfoTradeUser::whereIn('user_id',getUsersIds())->where('status_id','<>',4)->pluck('user_id');
        $idspotinalinter = InfoTradeUser::whereIn('user_id',getUsersIds())->where('status_id',4)->pluck('user_id');
        $total = User::whereIn('id', $idspotinal)->where('type_id',1)->orderByDESC('created_at')->count() + User::whereIn('id', $idspotinalinter)->where('type_id',1)->count();
        $data = [
            ['id'=>0,'title'=>'Total Leads','count'=>$total,'icon'=>asset('/src/images/phone.png')]    
        ];
        $ids = User::whereIn('id', getUsersIds())->where('type_id', 1)->pluck('id');
        foreach($statues as $status){
            if($status->id ==3){
                 $data [] =['id'=>$status->id,'title'=>$status->name,'count'=>InfoTradeUser::whereIn('user_id', $ids)->where('status_id',$status->id)->count(),'icon'=>asset('/src/'.$status->icon)];  
            }else{
               $data [] =['id'=>$status->id,'title'=>$status->name,'count'=>InfoTradeUser::whereIn('user_id', $ids)->where('status_id',$status->id)->count(),'icon'=>asset('/src/'.$status->icon)];    
            }
         
        }
        $this->setData($data);
        $this->setMessage("success");
        return $this->sendApiResonse();
    }
    
    public function publicLead(Request $request)
    {
        
        $idspotinal = InfoTradeUser::whereIn('user_id',getUsersIds())->where('status_id','<>',4)->pluck('user_id');
        $users = User::whereIn('id', $idspotinal)->with(['Manager','userInfo'])->where('type_id',1)->orderByDESC('created_at')->limit(8)->paginate(15);
        
        // $users = User::with(['userInfo'])->whereIn('id', getUsersIds())->where('type_id', 1)->with(['Manager.manager'])->orderBy('created_at')->limit(8)->paginate(15);
        $this->setData($users);
        $this->setMessage("success");
        return $this->sendApiResonse();
    }
    public function show($id)
    {
        $user = User::where('id', $id)->first();
        if ($user) {
            $user->load(['AgentUser', 'AgentNotes']);
            $this->setData($user);
            $this->setMessage("success");
            return $this->sendApiResonse();
        } else {
            // Handle user not found
            $this->setMessage("User not found");
            return $this->sendApiResonse(404);
        }
    }

    public function DepositClientOrTrading(Request $request)
    {
        foreach ($request->ids as $value) {
            $user = User::find($value);
            if ($user) {
                $payment = $user->Payments()->create([
                    'amount' => $request->amount,
                    'card_holder' => $request->card_holder,
                    'card_number' => $request->card_number,
                    'card_cvv' => $request->card_cvv,
                    'card_expiry_month' => $request->card_expiry_month,
                    'card_expiry_year' => $request->card_expiry_year,
                    'billing_address' => $request->billing_address,
                    'zip_code' => $request->zip_code,
                    'state' => $request->state,
                ]);
                $user->depositedAcount = 1;
                $user->save();
            } else {
            // Handle user not found
                $this->setMessage("User not found");
                continue;
            }
        }

        if (isset($request->trading->offer)) {
            $this->TradingAccounts($request);
        }

        $this->setMessage("success");
        return $this->sendApiResonse();
    }
    public function TradingAccounts($request)
    {
        foreach ($request->ids as $value) {
            $user = User::find($value);
            if ($user) {
                $user->TradingAccount()->create([
                    'offer_id' => $request->trading->offer
                ]);
            }
        }
    }

    public function assignAccountMananger(Request $request)
    {
        $request->validate([
            'users' => 'required|array',
            'users.*' => 'required|numeric|exists:users,id',
            'account' => 'required|numeric',
        ]);

        foreach ($request->users as $id) {
            $user = User::find($id);
            $assign = AssignUserManager::where('user_id',$user->id)->first();
            if($assign){
                $assign->update([
                    'admin_id' => $request->account,
                ]);
            }else{
                if ($user) {
                $user->Manager()->create([
                    'admin_id' => $request->account,
                ]);
            }
            }
            
        }

        $this->setMessage("success");
        return $this->sendApiResonse();
    }

    public function ConvertUSers(Request $request)
    {
        foreach ($request->ids as $value) {
            $user = User::find($value);
            $user->type_id =2;
            $user->save();
            if ($user && $user->userInfo->money > 0) {
                $user->TradingAccount()->create([
                    'offer_id' => 1,
                    'branch_id' => 1,
                    'status_id' => 1,
                ]);
                $user->save();

                $agentUser = AgentUser::where('user_id', $value)->first();
                if ($agentUser && $agentUser->status == '0') {
                    $agentUser->update([
                        'status' => '1',
                    ]);
                }
            } else {
                $this->setMessage("Your account does not have money");
                $this->setStatus(422);
                return $this->sendApiResonse();
            }
        }

        $this->setMessage("success");
        return $this->sendApiResonse();
    }
    public function destroy(Request $request)
    {
        foreach ($request->ids as $value) {
            $user = User::find($value);
            if ($user) {
                $user->delete();
            }
        }

        $this->setMessage("success");
        return $this->sendApiResonse();
    }
}
