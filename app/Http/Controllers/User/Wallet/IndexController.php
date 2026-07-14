<?php

namespace App\Http\Controllers\User\Wallet;

use App\Http\Controllers\Controller;
use App\Http\Requests\User\Wallet\WalletRequest;
use App\Models\Admin;
use App\Models\Wallet;
use Illuminate\Http\Request;
use App\Models\IBUser;
use App\Models\IBClient;
use App\Models\IBRequest;
use App\Models\User;
use App\Models\InfoTradeUser;
use App\Services\Users\UserWalletService;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;
use App\Rules\ReverseValue;

class IndexController extends Controller
{
    public $user;
    public function __construct() {
        $this->user = AuthApi();
       if($this->user){
            $this->user->load('userInfo');
        }
        // $user->hasPermission('Dashboard-data-manger')->only('store');
    }


    public function index(){
            UserWalletService::ensureSynced($this->user->userInfo);
            $tradingBalance = UserWalletService::mainBalance($this->user->userInfo);

            // جلب رصيد امبارح من العمود balance_yesterday (الحل الرئيسي)
            $yesterdayBalance = $this->user->userInfo->balance_yesterday ?? 0;
            
            // لو رصيد امبارح = 0 أو مش موجود، نحسبه بالطريقة البديلة
            if ($yesterdayBalance == 0) {
                $today = \Carbon\Carbon::today();
                
                // الرصيد الحالي (balance + money)
                $currentBalance = $tradingBalance + ($this->user->userInfo->money ?? 0);
                
                // حساب التغييرات اللي حصلت النهاردة من جدول transactions
                $todayDeposits = \App\Models\Transaction::where('user_id', $this->user->id)
                    ->whereIn('type', ['Deposit', 'deposit'])
                    ->where('created_at', '>=', $today)
                    ->sum('amount');
                
                $todayWithdrawals = \App\Models\Transaction::where('user_id', $this->user->id)
                    ->whereIn('type', ['withdrawl', 'withdrawal', 'Withdrawal'])
                    ->where('created_at', '>=', $today)
                    ->sum('amount');
                
                // رصيد امبارح = الرصيد الحالي - (الإيداعات النهاردة - السحوبات النهاردة)
                $yesterdayBalance = $currentBalance - ($todayDeposits - $todayWithdrawals);
            }

            $data=[[
                "name"=>"Actual Wallet",
                "amount"=>$this->user->userInfo->money,
                "cur"=>$this->user->userInfo->cur ??"eg",
                ],[
                    "name"=>"Trading Wallet",
                    "amount"=>$tradingBalance,
                    "cur"=>$this->user->userInfo->cur??"eg",
                ],[
                    "name"=>"Yesterday's Balance",
                    "amount"=>$yesterdayBalance,
                    "cur"=>$this->user->userInfo->cur??"eg",
                ]];
          $this->setData($data);
        $this->setMessage("success");
        return $this->sendApiResonse();
    }

    public function transfers(Request $request){
        $wallets = Wallet::where('user_id',$this->user->id)->with(['user'])->get();

        $data = [];
        // $data = [
        //     'data'         => [],
        //     'current_page' => $wallets->currentPage(),
        //     'from'         => $wallets->firstItem(),
        //     'last_page'    => $wallets->lastPage(),
        //     'links'        => $wallets->links(),
        //     'per_page'     => $wallets->perPage(),
        //     'to'           => $wallets->lastItem(),
        //     'total'        => $wallets->total(),
        // ];

        foreach($wallets as $value){
            $data[] = [
                'id'=>$value->id,
                "from"=>$value->from=="1"?"Actual Wallet":"Trading Wallet",
                "to"=> $value->to=="1"?"Actual Wallet":"Trading Wallet",
                "amount"=> $value->amount,
                "cur"=>$this->user->userInfo->cur ??"eg",
                'date'=>date('Y M d',strtotime($value->created_at)),
                "status"=> $this->statusWallet($value->status),

            ];
        }
        $this->setData($data);
        $this->setMessage("success");
        return $this->sendApiResonse();
    }


    public function statusWallet($status){
        switch($status){
            case null:
                return "Pending";
            break;
            case 0:
                return "Failed";
            break;
            default:
            return "Success";
        }
    }

    public function store(WalletRequest $request){




        $data = $request->all();
        if($data['from'] == 1){
            if($data['amount'] > $this->user->userInfo->money ){
                $this->setStatus(200);
                 $this->setData([
                    'status'=>422,
                ]);
                $this->setMessage("must amount less then Actual " . $this->user->userInfo->money);
                return $this->sendApiResonse();
            }
        }
        if($data['from'] == 2){
            $tradingBal = UserWalletService::mainBalance($this->user->userInfo);
            if($data['amount'] > $tradingBal ){
                $this->setStatus(422);
                $this->setMessage("must amount less then Trading  " . $tradingBal);
                return $this->sendApiResonse();
            }
        }
        Wallet::create([
                'user_id' => $this->user->id,
                'from' => $data['from'],
                'to' => $data['to'],
                'amount' => $data['amount'],
                'status' => '1',
        ]);
        if($data['from'] == 2){
            $userInfo = InfoTradeUser::where('user_id',$this->user->id)->first();
            $amount = (float) $data['amount'];
            if (!\App\Services\Users\UserWalletService::applyDebit($userInfo, $amount)) {
                $this->setStatus(422);
                $this->setMessage('must amount less then Trading ' . \App\Services\Users\UserWalletService::mainBalance($userInfo));
                return $this->sendApiResonse();
            }
            $userInfo->money = (int) $userInfo->money + (int) $amount;
            $userInfo->save();
        }else{
            $userInfo = InfoTradeUser::where('user_id',$this->user->id)->first();
            $amount = (float) $data['amount'];
            \App\Services\Users\UserWalletService::applyCreditToMain($userInfo, $amount);
            $userInfo->money = (int) $userInfo->money - (int) $amount;
            $userInfo->save();
            // $userInfo->balance += (int)$data['amount'];
            // $userInfo->money -= (int)$data['amount'];
            // $userInfo->save();
        }
        $this->setData([
                    'status'=>200,
                ]);
        $this->setMessage("success,Wait for the transfer to be accepted.");
        return $this->sendApiResonse();
    }


}
