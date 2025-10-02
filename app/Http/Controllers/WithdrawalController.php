<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use App\Models\Withdrawal;
use http\Client\Curl\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User as Admin;

class WithdrawalController extends Controller
{
    public function select()
    {
        $accounts = [];
        return view('backend.withdraw.select', compact('accounts'));
    }
    public function wallet($wallet)
    {
        $accounts = auth()->user()->accounts()->where('type',$wallet)->get();
        if(count($accounts) < 1){
            return redirect()->route('backend.withdraw.select')->with('failure', 'please add '.$wallet .' account to proceed');
        }
        $title = $wallet ." Withdrawal";
        return view('backend.withdraw.wallet', compact('accounts','title','wallet'));
    }

    public function index()
    {
        $deposits = Withdrawal::whereUserId();

        return view('deposits.index', compact('deposits'));
    }
    public function pendingWithdrawal()
    {
        $withdrawals = Withdrawal::whereUserId(auth()->id())->get();

        return view('backend.withdrawals.pending', compact('withdrawals'));
    }


    public function processing($id)
    {
        $withdrawal = Withdrawal::findOrFail($id);

        if (!$withdrawal->processed) {
            return view('backend.withdrawals.processing', compact('withdrawal'));
        }

        if($withdrawal->type == 'account_balance'){
                    if(!$withdrawal->commission_proof){
                        return view('backend.withdrawals.commission', compact('withdrawal'));
                    }
        if(!$withdrawal->tax_proof){
            if(!$withdrawal->commission){
                $title = 'Commission Fee';
                return view('backend.withdrawals.verify', compact('withdrawal','title'));
            }
            return view('backend.withdrawals.tax', compact('withdrawal'));
        }
        if(!$withdrawal->cot_proof){
            if(!$withdrawal->tax){
                $title = 'Tax Fee';
                return view('backend.withdrawals.verify', compact('withdrawal','title'));
            }
            return view('backend.withdrawals.cot', compact('withdrawal'));
        }
        if(!$withdrawal->cost_of_transfer){
           $title = 'Cost of Transfer';
            return view('backend.withdrawals.verify', compact('withdrawal','title'));
        }
        if($withdrawal->cost_of_transfer){
            if($withdrawal->approved){
                return redirect()->route('backend.pending.withdrawal');
            }
            $title = 'Withdrawal ';
            return view('backend.withdrawals.verify', compact('withdrawal','title'));
        }

        }else {
            if ($withdrawal->approved) {
                return redirect()->route('backend.pending.withdrawal');
            }
            $title = 'Withdrawal ';
            return view('backend.withdrawals.verify', compact('withdrawal', 'title'));
        }
    }
    public function processed(Request $request, $id)
    {
        $withdrawal = Withdrawal::findOrFail($id);

        $withdrawal->processed = true;

        $withdrawal->save();

        return redirect()->route('backend.withdrawal.processing', $withdrawal->id)->with('success', 'Transfer pending');

    }

    public function verify(Request $request, $id)
    {
        $withdrawal = Withdrawal::findOrFail($id);

        return view('backend.withdrawals.verify', compact('withdrawal'));

//        return redirect()->route('backend.withdrawal.processing', $withdrawal->id)->with('success', 'Transfer pending');

    }



    public function store(Request $request)
    {

      $data = $this->getData($request);

        if (auth()->user()->balance < $data['amount']) {
            return redirect()->back()->with('failure', 'You cant withdraw more than your account balance');
        }

            $withdrawal = Withdrawal::create($data);

            if($data['type'] == 'available_balance'){
                Transaction::create(['user_id' => auth()->id(), 'amount' => $data['amount'], 'source' => $data['type'], 'type' => 'Withdrawal', 'account_type' => 'available balance','note' => 'Available balance withdrawal']);
                Auth::user()->withdrawable = Auth::user()->withdrawable - $data['amount'];
            }else{
                Transaction::create(['user_id' => auth()->id(), 'amount' => $data['amount'], 'source' => $data['type'], 'type' => 'Withdrawal', 'account_type' => 'account balance','note' => 'Account balance withdrawal']);
                Auth::user()->balance = Auth::user()->aBalance() - $data['amount'];
            }
            $msg = Auth::user()->name. ' placed a withdrawal request of'. $data['amount']. ' login to dashboard and approve request';

            if (setting('enable_withdraw_mail') > 0 ) {
                 $users = Admin::whereRoleIs('superadmin')->take(2);
               
                foreach ($users as $user) {
                    $this->notifyAdmin($msg, 'Withdrawal Request', $user->email);
                }
                
            }

//        Auth::user()->save();
        return redirect()->route('backend.withdrawal.processing', $withdrawal->id);
    }

    public function bonusWithdraw(Request $request)
    {

            $data = $this->getData($request);

            if (auth()->user()->bonus < $data['amount']) {
                return redirect()->back()->with('failure', 'You cant withdraw more than your bonus balance');
            }

            $withdrawal = Withdrawal::create($data);

                Transaction::create(['user_id' => auth()->id(), 'amount' => $data['amount'], 'source' => $data['type'], 'type' => 'Withdrawal', 'account_type' => 'bonus balance','note' => 'Bonus balance withdrawal']);

                Auth::user()->bonus = Auth::user()->bonus - $data['amount'];



                Auth::user()->save();

                return redirect()->route('backend.withdrawal.processing', $withdrawal->id);
    }


    public function update(Request $request, $id)
    {

        $data = $this->getUData($request);

        $withdrawal = Withdrawal::findOrFail($id);
        $withdrawal->update($data);

            return redirect()->route('backend.withdrawal.processing', $withdrawal->id);
    }


    public function myWithdrawals()
    {
        $withdrawals = Withdrawal::whereUserId(auth()->id())->get();

        return view('backend.withdrawals.index', compact('withdrawals'));
    }

    public function withdraw()
    {
        $withdrawals = Withdrawal::whereUserId(auth()->id())->get();

        return view('backend.withdrawals.withdraw', compact('withdrawals'));
    }

    public function myBonusWithdrawals()
    {
        $withdrawals = Withdrawal::whereUserId(auth()->id())->get();

        return view('backend.withdrawals.bonus.index', compact('withdrawals'));
    }
    public function btcWithdrawal()
    {
        return view('backend.withdrawals.btc');
    }
    public function withdrawBonus()
    {
        return view('backend.withdrawals.bonus');
    }

    protected function getData(Request $request)
    {
        $rules = [
            'amount' => 'required',
            'account_id' => 'required',
            'proof' => 'nullable',
            'type' => 'nullable',
            'promo_code' => 'string|nullable',
        ];

        $data = $request->validate($rules);
        $data['user_id'] = auth()->id();
        return $data;
    }
    protected function getUData(Request $request)
    {
        $rules = [
            'commission_proof' => 'nullable',
            'tax_proof' => 'nullable',
            'cot_proof' => 'nullable',
            'promo_code' => 'nullable',
//            'method' => 'string|required',
        ];

        return $request->validate($rules);
//        return $data;
    }
}
