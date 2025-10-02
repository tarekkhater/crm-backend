<?php

namespace App\Http\Controllers\admin\finance\Deposits;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Deposit;
use App\Models\Account;
class IndexController extends Controller
{
    public function index(Request $request){
        $deposites = Deposit::with(['user','plan','account'])->paginate(20);
        $this->setMessage("success");
        $this->setData($deposites);
        return $this->sendApiResonse();
    }
    public function userDeposit($id){
        $deposites = Deposit::with(['user','amount','plan','account'])->where('user_id',$id)->paginate(20);
        $this->setMessage("success");
        $this->setData($deposites);
        return $this->sendApiResonse();
    }

    public function statusDeposit(Request $request){
        $request->validate([
            'id'=>'required|numeric|exists:deposits,id'
        ]);
        Deposit::find($request->id)->update([
            'status'=>1
        ]);
        $this->setMessage("success");
        return $this->sendApiResonse();
    }

    public function store(Request $request){
        $data = $request->validate([
            'user_id'=>'required|numeric|exists:users,id',
            'payment_method'=>'required|string',
            'currency'=>'required|string|exists:currencies,sign',
            'amount'=>'required|numeric',
            'plan_id'=>'required|numeric|exists:plans,id',
            'message'=>'sometimes|string',
            'proof'=>'sometimes|string',
            'promo_code'=>'sometimes|numeric',
            'net_amount'=>'sometimes|numeric',
        ]);
        $debosit = Deposit::create($data);
        $account = Account::where('user_id',$request->user_id)->first();
        if($account){
            $debosit->account_id = $account->id;
            $debosit->save();
        }
        $this->setMessage("success");
        return $this->sendApiResonse();
    }

}
