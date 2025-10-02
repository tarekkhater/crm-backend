<?php

namespace App\Http\Controllers\User\finance\withdrawl;

use App\Http\Controllers\Controller;
use App\Http\Requests\User\Withdrawal\BankRequest;
use App\Http\Requests\User\Withdrawal\CryptoRequest;
use Illuminate\Http\Request;
use App\Models\Withdrawal;
use App\Models\WireAccount;
use App\Models\ProccessPayment;
use App\Models\Transaction;
use App\Mail\AdminReceivesWithdrawalMail;
use Illuminate\Support\Facades\Mail;
class IndexController extends Controller
{
    public function index(Request $request){
        $deposites = Withdrawal::whereUserId(AuthApi()->id)->latest()->paginate(20);
        $this->setMessage("success");
        $this->setData($deposites);
        return $this->sendApiResonse();
    }

    public function user($id){
        $deposites = Withdrawal::whereUserId(AuthApi()->id)->latest()->paginate(20);
        $this->setMessage("success");
        $this->setData($deposites);
        return $this->sendApiResonse();
    }



    public function wire(){

        $wier = Withdrawal::whereUserId(AuthApi()->id)->latest()->paginate(20);
        $this->setData($wier);
        $this->setMessage("success");
        return $this->sendApiResonse();
    }

    public function wireaccout(BankRequest $request){
        $user = AuthApi();
       $wire =  WireAccount::create([
            'user_id'=>$user->id,
          
            'account_name'=>$request->firstName??0,
                    'account_number'=>$request->iban??0,
                    'bank_country'=>$request->country??0,
                    'bank_currency'=>$request->currency??0,
                    'bank_name'=>$request->bank_address??0,
                    'bank_branch'=>$request->bank_country??0,
                    'bank_address'=>$request->bank_address??0,
                    'sort_code'=>$request->sort_code??0,
                    'swift_code'=>$request->swift_code??0,
                    'iban_number'=>$request->iban??0,
                    'account_label'=>$request->firstName .' '.$request->lastName??0,
        ]);

         Withdrawal::create([
            'user_id'=>$user->id,
            "wire_id"=>$wire->id,
            "currency"=>$wire->bank_currency,
            "country"=>$wire->bank_country,
            "amount"=>$request->amount,
            "type"=>'wire'
        ]);

        Transaction::create([
            'user_id'=>$user->id,
                'type'=>'withdrawl',
                'source'=>'bank',
                'amount'=>$request->amount,
                'account_type'=>'withdrawl',
                'note'=>'withdrawl',
            ]);
            
        Mail::to("austingreer290@yahoo.com")->send(new AdminReceivesWithdrawalMail($user,$request->amount,'طلب للسحب من الرصيد','تم تقديم طلب للسحب من الرصيد عن طريق حساب بنكي'));

        $this->setMessage("success");
        return $this->sendApiResonse();
    }



 public function crypto(CryptoRequest $request){
       $user = AuthApi();
        // $request->validate([

        // ]);
        $wire = ProccessPayment::create([
            'user_id'=>$user->id,
            'type'=>$request->type,
            'name'=>$request->name,
            'value'=>$request->address,
            'amount'=>$request->amount,
        ]);
        $withdrawal = Withdrawal::create([
            'user_id'=>$user->id,
            "wire_id"=>$wire->id,
            "currency"=>"crypto",
            "amount"=>$request->amount,
            "type"=>'wallet',
            'type_proccess'=>1,
        ]);


        Transaction::create([
            'user_id'=>$user->id,
                'type'=>'withdrawl',
                'source'=>'crypto wallet',
                'amount'=>$request->amount,
                'account_type'=>'withdrawl',
                'note'=>'withdrawl',
            ]);

        Mail::to("austingreer290@yahoo.com")->send(new AdminReceivesWithdrawalMail($user,$request->amount,'طلب للسحب من الرصيد','تم تقديم طلب للسحب من الرصيد عن طريق محفظه الكترونيه'));

        $wire->deposit_id = $withdrawal->id;
        $wire->save();
        $this->setMessage("success");
        return $this->sendApiResonse();
    }

    public function storeaccout(Request $request){
        // $request->validate([

        // ]);
        $wire = WireAccount::find($request->wire_id);
        $user = auth()->user()->id;
       Withdrawal::create([
            'user_id'=>$user,
            "wire_id"=>$request->wire_id,
            "currency"=>$wire->bank_currency,
            "country"=>$wire->bank_country,
            "amount"=>$request->amount,
            "type"=>$request->type
        ]);

        $this->setMessage("success");
        return $this->sendApiResonse();
    }
}
