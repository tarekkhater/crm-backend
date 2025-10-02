<?php

namespace App\Http\Controllers\admin\finance\Deposits;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Deposit;
use App\Models\Account;
use App\Models\InfoTradeUser;
use App\Exports\DepositExport;
use App\Models\AgentUser;
use App\Models\IBClient;
use Maatwebsite\Excel\Facades\Excel;
use Schema;
use App\Models\User;
use App\Models\Transaction;
use App\Models\UserManager;
use Illuminate\Support\Facades\Mail;
use App\Mail\depositMail;
class IndexController extends Controller
{
    public function index(Request $request){
        $userids = getUsersIds();
        $deposites = Deposit::whereIn('user_id',$userids)->where('type','deposit')->with(['user','user.countries','user.TradingAccount','plan'])->paginate(16);
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
        $deposites = Deposit::with(['user','user.countries','user.TradingAccount','amount','plan'])->where('user_id',$id)->paginate(20);
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
        $deposit = Deposit::where('id',$request->id)->where('status','<>',1)->first();
        if(!$deposit){
            $this->setMessage("You Not Allowed To Change Status Request");
            $this->setStatus(422);
            return $this->sendApiResonse();
        }
        $deposit->update([
            'status'=>$request->status,
            'message'=>isset($request->message)?$request->message:null,
        ]);
        if(isset($request->proof)){
            $deposit->update([
                'proof'=> uploadRealImage($request->proof,"Deposit"),
            ]);  
        }
        $olduser = InfoTradeUser::where('user_id',$deposit->user_id)->first();
        if($request->status == 1){
            $user = InfoTradeUser::where('user_id',$deposit->user_id)->first();
            $user->balance += $deposit->amount;
            $user->save();
            // ($user,$total,$money,$amount,$join_at)
            Mail::to("$user->email")->send(new depositMail($user,$user->balance,$olduser->balance,$deposit->amount,$deposit->created_at));
        }       
        $this->setMessage("success");
        return $this->sendApiResonse();
    }

    // public function store(Request $request){
    //     $debosit = Deposit::create($data);
    //     $account = Account::where('user_id',$request->user_id)->first();
    //     if($account){
    //         $debosit->account_id = $account->id;
    //         $debosit->save();
    //     }
    //     $this->setMessage("success");
    //     return $this->sendApiResonse();
    // }



    public function FilterText(Request $request){
        $data = [];
        $users = User::query();
        $columns = Schema::getColumnListing('users');
        foreach($columns as $column){
            $users->orWhere($column,'LIKE','%'.$request->input.'%');
        }
        $userids = getUsersIds();
        $users = $users->whereIn('id',$userids)->where($data)->pluck('id');
        $users =  Deposit::with(['user','user.TradingAccount','user.countries','plan'])->whereIn('user_id',$users)->paginate(15);

        $this->setData($users);
        $this->setMessage("success");
        return $this->sendApiResonse();
    }

    public function Filter(Request $request)
{
    // Initialize the user IDs for filtering deposits
    $idsUser = getUsersIds();
$data = [];
    // Start building the query with the initial condition
    

    // Check if 'search' parameter is set and is an array
    if (isset($request->search) && is_array($request->search)) {
        foreach ($request->search as $req) {
            // Ensure both key and value are set in each search item
            if (isset($req['key'], $req['value'])) {
                // Add a 'where' clause for each search condition dynamically
                // $query->where($req['key'], 'LIKE', "%{$req['value']}%");
                     $data[] = [$req['key'],$req['value']];
            }
        }
    }

    // Paginate the query results
    $users = Deposit::where($data)->with(['user','user.TradingAccount','user.countries','plan'])->whereIn('user_id', $idsUser)->paginate(15);

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

        $user->userInfo->balance = $user->userInfo->balance + $data['amount'];

        $user->save();

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
