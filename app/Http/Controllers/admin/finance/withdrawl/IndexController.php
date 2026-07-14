<?php

namespace App\Http\Controllers\admin\finance\withdrawl;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Withdrawal;
use App\Exports\WithDrawlExport;
use App\Models\AgentUser;
use App\Models\IBClient;
use Maatwebsite\Excel\Facades\Excel;
use Schema;
use App\Models\User;
use App\Models\InfoTradeUser;
use App\Models\Transaction;
use App\Services\Users\UserWalletService;
use App\Models\UserManager;

class IndexController extends Controller
{
    public function index(Request $request){
        $ids = getUsersIds();
        $deposites = Withdrawal::whereIn('user_id',$ids)->with(['user','user.TradingAccount','user.countries','plan'])->paginate(20);
        $this->setMessage("success");
        $this->setData($deposites);
        return $this->sendApiResonse();
    }

    public function user($id){
        abort_unless(in_array((int)$id, array_map('intval', getUsersIds()->toArray())), 403);
        $deposites = Withdrawal::with(['user','user.TradingAccount','user.countries','plan'])->where('user_id',$id)->paginate(20);
        $this->setMessage("success");
        $this->setData($deposites);
        return $this->sendApiResonse();
    }

    public function statuswithdrawal(Request $request){
        $request->validate([
            'id'=>'required|numeric|exists:withdrawals,id',
             'status'=>'required'
        ]);
        $withdrawals = Withdrawal::where('id',$request->id)->where('status','<>',1)->first();
        if(!$withdrawals){
            $this->setMessage("You Not Allowed To Change Status Request");
            $this->setStatus(422);
            return $this->sendApiResonse();
        }
        $withdrawals->update([
            'status'=>$request->status,
            'message'=>isset($request->message)?$request->message:null,
        ]);
        if(isset($request->proof)){
            $withdrawals->update([
                'proof'=> uploadRealImage($request->proof,"withdrawals"),
            ]);  
        }
        
       
         if($request->status == 1){
            $user = InfoTradeUser::where('user_id',$withdrawals->user_id)->first();
            if($user && UserWalletService::canAfford($user, (float) $withdrawals->amount)
                && UserWalletService::applyDebit($user, (float) $withdrawals->amount)){
                $withdrawals->update([
                    'status'=>$request->status
                ]);
                $user->save();
            }else{
                $this->setStatus(422);
                $this->setMessage("you Dont Have Money to success proccess");
                return $this->sendApiResonse();
            }
             
        }    
        $this->setMessage("success");
        return $this->sendApiResonse();
    }

    public function store(Request $request){
        $request->validate([

        ]);

        Withdrawal::create([

        ]);

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
         $ids = getUsersIds();
        $users = $users->whereIn('id',$ids)->where($data)->pluck('id');
        $users =  Withdrawal::with(['user','user.TradingAccount','user.countries','plan'])->whereIn('user_id',$users)->paginate(15);

        $this->setData($users);
        $this->setMessage("success");
        return $this->sendApiResonse();
    }

    public function Filter(Request $request)
{
    // Get the user IDs for filtering deposits
    $idsUser = getUsersIds();

    // Define a mapping of allowed keys to their respective model fields or relationship fields
    $allowedFilters = [
        'Email' => 'user.email',
        'Country' => 'country',
        'Number' => 'number',
        'Amount' => 'amount',
        'Currency' => 'currency'
    ];
        $data = [];
    // Start the query with the base condition

    // Check if the 'search' parameter is provided and is an array
    if (isset($request->search) && is_array($request->search)) {
        foreach ($request->search as $filter) {
            // Validate each filter has both a 'key' and a 'value'
            // if (isset($filter['key'], $filter['value']) && array_key_exists($filter['key'], $allowedFilters)) {
            //     $field = $allowedFilters[$filter['key']];

            //     if (str_contains($field, '.')) {
            //         // Handle relationship fields (e.g., `user.email`)
            //         [$relation, $column] = explode('.', $field);
            //         $query->whereHas($relation, function ($q) use ($column, $filter) {
            //             $q->where($column, 'LIKE', "%{$filter['value']}%");
            //         });
            //     } else {
            //         // Handle direct fields in the Deposit model
            //         $query->where($field, 'LIKE', "%{$filter['value']}%");
            //     }
            // }
            
             if($filter['value'] != ''){
                     $data[] = [$filter['key'],$filter['value']];
                }
        }
    }

    // Paginate the query results
    $results = Withdrawal::whereIn('user_id', $idsUser)->where($data)->with(['user', 'user.TradingAccount','user.countries', 'plan'])->paginate(15);

    // Return the results as a JSON response
    return response()->json($results);
}


    public function Export(Request $request){
        try {
            // Export and store the file
            Excel::store(new WithDrawlExport(), 'upload/excel/export/WithDrawl.xls', 'public');

            // Set success response
            $this->setMessage("success");
            $this->setData('upload/excel/export/WithDrawl.xls');

            return $this->sendApiResonse();
        } catch (\Exception $e) {
            // Handle exceptions and provide a meaningful error response
            return response()->json(['message' => 'Export failed', 'error' => $e->getMessage()], 500);
        }
    }



    public function withdrawApprove(Request $request)
    {
        $data = $request->all();

        $user = User::findOrFail($data['user_id']);

        $wd = Withdrawal::findOrFail($data['id']);
        $wd['status'] = $data['status'];
        $wd['note'] = $data['note'];

        if ($data['status'] == 'approved') {
            if ($wd->approved < 1) {
                $user->load('userInfo');
                if (!$user->userInfo || !UserWalletService::applyDebit($user->userInfo, (float) $wd->amount)) {
                    return redirect()->back()->with('failure', 'Insufficient balance for withdrawal');
                }
                $user->userInfo->save();
            }
            $wd->approved = 1;
        }elseif ($data['status'] == 'declined') {
            $wd->status ='declined';
        }

        $wd->save();

       Transaction::create(['user_id' => $data['user_id'], 'amount' => $data['amount'], 'type' => 'deposit', 'account_type' => 'balance','note' => 'deposit']);

        $user->save();

        return redirect()->back()->with('success', 'Successfully Updated Withdrawal');
    }


}
