<?php

namespace App\Http\Controllers\User\finance\transactions;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Transaction;
use Auth;
use App\Models\Deposit;
use App\Models\Withdrawal;

class IndexController extends Controller
{
    
    public function index(Request $request){

         $data = [];
                if(isset($request->search) && is_array($request->search)){
                    foreach($request->search as $index=>$value){
                    if($value['key'] == 'open_at' && $value['value'] != null){
                         $data[] = ['open_at',$value['value']];
                    }else{
                         $data[] = [$value['key'],$value['value']];
                    }

                    }
                }


        $transactions = [
            "data"=>[],    
        ];
        // Transaction::select('id','note','type','amount','account_type','source as direction','status','created_at')->whereUserId(AuthApi()->id)->where($data)->latest()->paginate(15);
        $withdrawals = [];
        $deposites = [];
        if(isset($request->type) && $request->type == 'withdrawal'){
            $withdrawals = Withdrawal::where('user_id',AuthApi()->id)->get();
        }else{
            $deposites = Deposit::where('user_id',AuthApi()->id)->get();    
        }
        if(count($withdrawals) > 0){
            foreach($withdrawals as $withdrawal){
                $transactions['data'][]=[
                    'id'=>$withdrawal->id,
                    'note'=>"withdrawl",
                    'type'=>"withdrawl",
                    'amount'=>$withdrawal->amount,
                    'account_type'=>"withdrawl",
                    'direction'=>$withdrawal->type,
                    'status'=>$withdrawal->status,
                    'created_at'=>$withdrawal->created_at
                ];
            }    
        }
        
        if(count($deposites) > 0){
        foreach($deposites as $deposite){
            $transactions['data'][]=[
                'id'=>$deposite->id,
                'note'=>$deposite->type,
                'type'=>$deposite->type,
                'amount'=>$deposite->amount,
                'account_type'=>"deposit",
                'direction'=>$deposite->type,
                'status'=>$deposite->status,
                'created_at'=>$deposite->created_at
            ];
        }
        }
        $this->setData($transactions);
        $this->setMessage("success");
        return $this->sendApiResonse();
    }

    public function show($id){
        $deposites = Transaction::where('id',$id)->first();
        $this->setMessage("success");
        $this->setData($deposites);
        return $this->sendApiResonse();
    }



    

}
