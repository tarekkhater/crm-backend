<?php

namespace App\Http\Controllers\admin\CRM\Api\finance\Deposits;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Deposit;
use App\Models\Account;
use App\Exports\DepositExport;
use App\Models\AgentUser;
use App\Models\IBClient;
use Maatwebsite\Excel\Facades\Excel;
use Schema;
use App\Models\User;
use App\Models\Transaction;
use App\Models\UserManager;
use App\Services\Users\UserWalletService;

class IndexController extends Controller
{
    public function index(Request $request){
        $userids = getUsersIds();
        $deposites = Deposit::whereIn('user_id',$userids)->with(['user','user.TradingAccount','plan','account'])->paginate(16);
        // $result = [
        //     'data'         => $this->responseData($deposites->items()),
        //     'current_page' => $deposites->currentPage(),
        //     'from'         => $deposites->firstItem(),
        //     'last_page'    => $deposites->lastPage(),
        //     'links'        => $deposites->links(),
        //     'per_page'     => $deposites->perPage(),
        //     'to'           => $deposites->lastItem(),
        //     'total'        => $deposites->total(),
        // ];
        
        $this->setMessage("success");
        $this->setData($deposites);
        return $this->sendApiResonse();
    }
    public function userDeposit($id){
        $deposites = Deposit::with(['user','user.TradingAccount','amount','plan','account'])->where('user_id',$id)->paginate(20);
        // $result = [
        //     'data'         => $this->responseData($deposites->items()),
        //     'current_page' => $deposites->currentPage(),
        //     'from'         => $deposites->firstItem(),
        //     'last_page'    => $deposites->lastPage(),
        //     'links'        => $deposites->links(),
        //     'per_page'     => $deposites->perPage(),
        //     'to'           => $deposites->lastItem(),
        //     'total'        => $deposites->total(),
        // ];
        
        $this->setMessage("success");
        $this->setData($deposites);
        return $this->sendApiResonse();
    }

    public function statusDeposit(Request $request){
        $request->validate([
            'id'=>'required|numeric|exists:deposits,id',
            'status'=>'required'
        ]);
        Deposit::find($request->id)->update([
            'status'=>$request->status,
            'message'=>isset($request->message)?$request->message:null,
        ]);
        $this->setMessage("success");
        return $this->sendApiResonse();
    }

    public function store(Request $request){
        // $data = $request->validate([
        //     'user_id'=>'required|numeric|exists:users,id',
        //     'payment_method'=>'required|string',
        //     'currency'=>'required|string|exists:currencies,sign',
        //     'amount'=>'required|numeric',
        //     'plan_id'=>'required|numeric|exists:plans,id',
        //     'message'=>'sometimes|string',
        //     'proof'=>'sometimes|string',
        //     'promo_code'=>'sometimes|numeric',
        //     'net_amount'=>'sometimes|numeric',
        // ]);
        $debosit = Deposit::create($data);
        $account = Account::where('user_id',$request->user_id)->first();
        if($account){
            $debosit->account_id = $account->id;
            $debosit->save();
        }
        $this->setMessage("success");
        return $this->sendApiResonse();
    }



    public function FilterText(Request $request){
        $data = [];
        $users = User::query();
        $columns = Schema::getColumnListing('users');
        foreach($columns as $column){
            $users->orWhere($column,'LIKE','%'.$request->input.'%');
        }
        $users = $users->where($data)->pluck('id');
        $users =  Deposit::with(['user','user.TradingAccount','plan','account'])->whereIn('user_id',$users)->paginate(15);

        $this->setData($users);
        $this->setMessage("success");
        return $this->sendApiResonse();
    }

    public function Filter(Request $request)
{
    // Initialize the user IDs for filtering deposits
    $idsUser = getUsersIds();

    // Start building the query with the initial condition
    $query = Deposit::whereIn('user_id', $idsUser);

    // Check if 'search' parameter is set and is an array
    if (isset($request->search) && is_array($request->search)) {
        foreach ($request->search as $req) {
            // Ensure both key and value are set in each search item
            if (isset($req['key'], $req['value'])) {
                // Add a 'where' clause for each search condition dynamically
                $query->where($req['key'], 'LIKE', "%{$req['value']}%");
            }
        }
    }

    // Paginate the query results
    $users = $query->paginate(15);

    // Return the paginated results as a JSON response
    return response()->json($users);
}




    public function Export(Request $request)
    {
        try {
            // Store the export as an Excel file
            Excel::store(new DepositExport(), 'upload/excel/export/Deposit.xls', 'public');

            // Set the response message and data
            $this->setMessage("success");
            $this->setData('upload/excel/export/Deposit.xls');

            return $this->sendApiResonse();
        } catch (\Exception $e) {
            // Handle any exceptions
            return response()->json([
                'message' => 'Export failed',
                'error' => $e->getMessage()
            ], 500);
        }
    }


    public function approve(Request $request)
    {
        $data = $request->all();

        $user = User::findOrFail($data['user_id']);
        $user->load('userInfo');

        UserWalletService::ensureSynced($user->userInfo);
        UserWalletService::applyCreditToMain($user->userInfo, (float) $data['amount']);
        $user->userInfo->save();

        Transaction::create(['user_id' => $data['user_id'], 'amount' => $data['amount'], 'type' => 'deposit', 'account_type' => 'balance', 'note' => 'deposit']);

        $deposit = Deposit::findOrFail($data['id']);
        if ($deposit->plan_id) {
            $package = Package::whereId($deposit->plan_id)->first();
            if ($package) {
                $user->plan = $package->name;
                $user->can_upgrade = false;
            }
        }
        $deposit->status = true;

        $user->save();

        $deposit->save();

    }
    
    
    public function responseData($deposits){
        $data = [];
        foreach($deposits as $deposit){
                $data[] =[
                  'id'=>$deposit->id,
                    'user_id'=>$deposit->user->id,
                   'name'=>$deposit->user->name != null?$deposit->user->name:"name",
                    'email'=>$deposit->user->email,
                    'number'=>$deposit->user->phone,
                    'date'=>date('Y M d',strtotime($deposit->created_at)),
                    "amount"=> $deposit->amount,
                    "currency"=> $deposit->currency,
                    "country"=> $deposit->country,
                    'status'=>$deposit->status,
                ];
        }
        return $data;
    }


 


}
