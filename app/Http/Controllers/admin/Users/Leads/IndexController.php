<?php

namespace App\Http\Controllers\admin\Users\Leads;

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
use App\Models\Admin;
use Carbon\Carbon;
use Auth;
use App\Models\Identity;
use App\Http\Resources\Admin\User\UsersResource;

class IndexController extends Controller
{
    public $potential;
    public function __construct()
    {
        // $this->middleware('RoleMiddleware:Sales-Dashboard')->only(['index']);
        $this->potential = new IndexServices();
    }
    public function index(Request $request)
    {
        $user = $this->potential->index($request);
        if (isset($request->type) && $request->type > 0) {
            $idspotinal = InfoTradeUser::whereIn('user_id', getUsersIds())->where('status_id', '<>', 4)->pluck('user_id');
            $users = User::whereIn('id', $idspotinal)->with(['Manager'])->where('type_id', $request->type)->paginate(15);
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
    public function Potential(Request $request)
    {
        $this->setData($this->potential->index($request));
        $this->setMessage("success");
        return $this->sendApiResonse();
    }
    
    public function leadCenterDashboard(Request $request){
        $statues = Status::get();
       
        $ids = [];
        if($request->id > 0 ){
            $ids = AssignUserManager::where('admin_id',$request->id)->pluck('user_id');
        }else{
            $ids = User::where('type_id', 1)->pluck('id');
        }
         $total = count($ids);
        $data = [
            'customer'=>[['id' => 0, 'title' => 'Total Leads', 'count' => $total, 'icon' => asset('/src/images/phone.png')]],
            'kyc'=>['completed'=>Identity::whereIn('user_id', $ids)->wherestatus('1')->count(),'pendingk'=>Identity::whereIn('user_id', $ids)->wherestatus('0')->count()],
        ];
       
        foreach ($statues as $status) {
            
                $data['customer'][] = [
                    'id' => $status->id,
                    'title' => $status->name,
                    'count' => InfoTradeUser::whereIn('user_id', $ids)->where('status_id', $status->id)->count(),
                    'icon' => asset('/src/' . $status->icon)
                ];
            
        }
        if($request->id  > 0 ){
            $idspotinals = InfoTradeUser::whereIn('user_id',$ids)->where('status_id',4)->pluck('user_id');
            $pusers = User::whereIn('id', $idspotinals)->where('type_id',2)->orderByDESC('created_at')->count();
            $data['customer'][] = ['id' => 10, 'title' => 'Potential Leads', 'count' => $pusers, 'icon' => asset('/src/images/phone.png')];
        }else{
            $adminids = Admin::where('manager_id',auth()->user()->id)->pluck('id');
            $ids = AssignUserManager::whereIn('admin_id',$adminids)->pluck('user_id');
            $idspotinals = InfoTradeUser::where('status_id',4)->pluck('user_id');
            $pusers = User::whereIn('id',$ids)->whereIn('id', $idspotinals)->where('type_id',2)->orderByDESC('created_at')->count();
            
            $idspotinalss = InfoTradeUser::where('status_id',4)->pluck('user_id');
            $puserss = User::whereIn('id', $idspotinalss)->where('type_id',2)->orderByDESC('created_at')->count();
            $count = $pusers + $puserss;
            $data['customer'][] = ['id' => 10, 'title' => 'Potential Leads', 'count' => $pusers, 'icon' => asset('/src/images/phone.png')];
        }
        
        $this->setData($data);
        $this->setMessage("success");
        return $this->sendApiResonse();
    } 

    public function leadCenter(Request $request)
    {
        $statues = Status::get();
        $idspotinal = InfoTradeUser::whereIn('user_id', getUsersIds())->pluck('user_id');
        $total = $this->checkFilterLeaderTotalCenter($request->type, $idspotinal);
        $data = [
            ['id' => 0, 'title' => 'Total Leads', 'count' => $total, 'icon' => asset('/src/images/phone.png')]
        ];
        $ids = User::whereIn('id', getUsersIds())->where('type_id', 2)->pluck('id');
        foreach ($statues as $status) {
            
                $data[] = [
                    'id' => $status->id,
                    'title' => $status->name,
                    'count' => $this->checkFilterLeaderCenter($request->type, $status, $ids),
                    'icon' => asset('/src/' . $status->icon)
                ];
            
        }
        $this->setData($data);
        $this->setMessage("success");
        return $this->sendApiResonse();
    }

    public function publicLead(Request $request)
    {

        $idspotinal = InfoTradeUser::whereIn('user_id', getUsersIds())->where('status_id', '<>', 4)->pluck('user_id');
        $users = User::whereIn('id', $idspotinal)->with(['Manager.manager', 'userInfo','countries'])->where('type_id', 1)->orderByDESC('created_at')->limit(8)->paginate(15);

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
            'account' => 'required|numeric|exists:admin,id',
        ]);

        foreach ($request->users as $id) {
            $user = User::find($id);
            $assign = AssignUserManager::where('user_id', $user->id)->first();
            if ($assign) {
                $assign->update([
                    'admin_id' => $request->account,
                ]);
            } else {
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
    
    public function ConvertUSersTopotential(Request $request)
    {
        /**
         * Get the users
         * @var \Illuminate\Database\Eloquent\Collection
         */
        $users = User::whereIn('id', $request->ids)->get();

        $users->each(function (User $user) {
            $infouser = InfoTradeUser::where('user_id', $user->id)->first();
            $infouser->status_id = 4;
            $user->type_id = 2;
            $user->save();
            $infouser->save();
        });


        $this->setMessage("success");
        return $this->sendApiResonse();
    }

    public function ConvertUSers(Request $request)
    {
        /**
         * Get the users
         * @var \Illuminate\Database\Eloquent\Collection
         */
        $users = User::whereIn('id', $request->ids)->get();

        foreach($users as  $user) {
            $infouser = InfoTradeUser::where('user_id', $user->id)->first();
            $infouser->status_id = 1;
            $infouser->save();
            if ($user && $user->userInfo->money > 0) {
                $user->TradingAccount()->create([
                    'offer_id' => 1,
                    'branch_id' => 1,
                    'status_id' => 1,
                ]);
                $agentUser = AgentUser::where('user_id', $user->getKey())->first();
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
        };


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

    public function checkFilterLeaderCenter($type, $status, $ids)
    {
        $records = 0;
        switch ($type) {
            case 'Daily':
                // Filter by today (daily)
                $records = InfoTradeUser::whereIn('user_id', $ids)->where('status_id', $status->id)->whereDate('created_at', Carbon::today())->count();
                break;

            case 'Weekly':
                // Filter by the current week
                $startOfWeek = Carbon::now()->startOfWeek();
                $endOfWeek = Carbon::now()->endOfWeek();
                $records = InfoTradeUser::whereIn('user_id', $ids)->where('status_id', $status->id)->whereBetween('created_at', [$startOfWeek, $endOfWeek])->count();
                break;

            case 'Monthly':
                // Filter by the current month
                $startOfMonth = Carbon::now()->startOfMonth();
                $endOfMonth = Carbon::now()->endOfMonth();
                $records = InfoTradeUser::whereIn('user_id', $ids)->where('status_id', $status->id)->whereBetween('created_at', [$startOfMonth, $endOfMonth])->count();
                break;

            case 'Yearly':
                // Filter by the current year
                $startOfYear = Carbon::now()->startOfYear();
                $endOfYear = Carbon::now()->endOfYear();
                $records = InfoTradeUser::whereIn('user_id', $ids)->where('status_id', $status->id)->whereBetween('created_at', [$startOfYear, $endOfYear])->count();
                break;

            default:
                // If no valid filter is passed, return all records
                $records = InfoTradeUser::whereIn('user_id', $ids)->where('status_id', $status->id)->count();
                break;
        }
        return $records;
    }

    public function checkFilterLeaderTotalCenter($type, $idspotinal)
    {
        $records = 0;
        switch ($type) {
            case 'Daily':
                $records = User::whereIn('id', $idspotinal)->where('type_id', 2)->whereDate('created_at', Carbon::today())->orderByDESC('created_at')->count();
                break;
            case 'Weekly':
                $startOfWeek = Carbon::now()->startOfWeek();
                $endOfWeek = Carbon::now()->endOfWeek();
                $records = User::whereIn('id', $idspotinal)->where('type_id', 2)->orderByDESC('created_at')->whereBetween('created_at', [$startOfWeek, $endOfWeek])->count();
                break;

            case 'Monthly':
                // Filter by the current month
                $startOfMonth = Carbon::now()->startOfMonth();
                $endOfMonth = Carbon::now()->endOfMonth();
                $records = User::whereIn('id', $idspotinal)->where('type_id', 2)->orderByDESC('created_at')->whereBetween('created_at', [$startOfMonth, $endOfMonth])->count();
                break;
            case 'Yearly':

                $startOfYear = Carbon::now()->startOfYear();
                $endOfYear = Carbon::now()->endOfYear();
                $records = User::whereIn('id', $idspotinal)->where('type_id', 2)->orderByDESC('created_at')->whereBetween('created_at', [$startOfYear, $endOfYear])->count();
                break;
            default:
                // If no valid filter is passed, return all records
                $records = User::whereIn('id', $idspotinal)->where('type_id', 2)->orderByDESC('created_at')->count();

                break;
        }
        return $records;
    }
}
