<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\CurrencyPair;
use App\Models\Overnight;
use App\Models\Trade;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use \Illuminate\Database\Eloquent\ModelNotFoundException;

class TradesController extends Controller
{
    public function store(Request $request){
        $request->validate([
            'amount' => 'required|numeric|gt:0',
            'coinId' => 'required',
            'type' => 'required',
            // 'is_take_profit' => 'required',
            // 'is_stop_loss' => 'required',
            // 'stop_loss' => 'required',
            // 'take_profit' => 'required',
            'duration' => 'required|numeric|gt:0',
        ]);
        $data = [];
        $coin = CurrencyPair::whereId($request['coinId'])->first();
        $com = ((float)$request['amount'] * (float)$coin->com) / 100;
        $amt = $request['amount'] + $com;
        if(Auth('api')->user()->balance < $amt){
            $data['status'] = 0;
            $data['msg'] = 'Insufficient balance, pls fund your account';
        }
        $rate = $this->getCurRate($coin->sym, $coin->base, $coin->type) ?: $coin->rate;
        if($coin->disabled){
            $data['status'] = 0;
            $data['msg'] = 'You cannot trade '.$coin->name .' at the moment';
        }else{
            if($rate != 0.000 ){
                $data = $request->all();
                $data['open_rate'] = $rate;
                $res = $this->cryptoTrade($data, Auth('api')->id(), $coin, $rate);

                $this->tradeMinusBalance(Auth('api')->user(), $amt, 'Traded amount');

                $data['status'] = 1;
                $data['msg'] = 'trade successfully submitted';
                $data['data'] = $res;
            }else {
                $data['status'] = 0;
                $data['msg'] = 'You cannot trade '.$coin->name .' at the moment';
            }
        }

        return response()->json($data);
    }

    public function cryptoTrade($data, $user_id, $coin, $rate){
        $trade = new Trade();
        if(auth('api')->id() != $user_id){
            $trade->by_admin = 1;
        }
        $trade->user_id = $user_id;
        $trade->trade_type = $data['type'];
        $trade->traded_amount = $data['amount'];
        $trade->is_take_profit = $data['is_take_profit'];
        $trade->take_profit = $data['take_profit'];
        $trade->is_stop_loss = $data['is_stop_loss'];
        $trade->stop_loss = $data['stop_loss'];
        $trade->currency_pair = $coin->ex_sym;
        $trade->leverage = $coin->leverage;
        $trade->duration = $data['duration'];
        $trade->close_at = Carbon::now()->addMinutes($data['duration']);
        $trade->opening_price = $data['open_rate'];
        $trade->closing_price = $rate;
        $trade->overnight = Carbon::createFromFormat('Y-m-d H:i:s',now())->format('Y-m-d');;
        $trade->save();
        return $trade;
    }

    public function userTrades(){

        $trades = Trade::whereUserId(Auth('api')->id())->get();
        return response()->json($trades);
    }

    public function crmUserTrades(Request $request){
        $request->validate([
            'email' => 'required',
        ]);
        $email = $request['email'];
        $user = User::whereEmail($email)->first();
        if(!$user) {
            $dt['status'] = 'failed';
            $dt['msg'] = 'user not found';
            return response()->json($dt, 422);
        }
        $trades = Trade::whereUserId($user->id)->get();
        return response()->json($trades);
    }

    public function crmUserOpenTrades(Request $request){
        $request->validate([
            'email' => 'required',
        ]);
        $email = $request['email'];
        $user = User::whereEmail($email)->first();
        if(!$user) {
            $dt['status'] = 'failed';
            $dt['msg'] = 'user not found';
            return response()->json($dt, 422);
        }
        $trades = Trade::whereUserId($user->id)->whereStatus(0)->get();
        return response()->json($trades);
    }
    public function crmUserClosedTrades(Request $request){
        $request->validate([
            'email' => 'required',
        ]);
        $email = $request['email'];
        $user = User::whereEmail($email)->first();
        if(!$user) {
            $dt['status'] = 'failed';
            $dt['msg'] = 'user not found';
            return response()->json($dt, 422);
        }
        $trades = Trade::whereUserId($user->id)->whereStatus(1)->get();
        return response()->json($trades);
    }


    public function deleteTrade($tradeId) {
        try {
            $trade = Trade::findOrFail($tradeId);
            $trade->delete();
            return response()->json('Trade deleted successfully');
        } catch (\Throwable $th) {
            if ($th instanceof ModelNotFoundException) {
                return response()->json(['Trade no found!'], 404);

            }
            return response()->json(['message' => $th->getMessage()], 500);
        }
    }
    /**
     * @bodyParam amount int required
     * @bodyParam
     */
    public function closeTrade(Request $request){
        $request->validate([
            'id' => 'required|numeric|gt:0',
        ]);
        try {
            $id = $request->id;
            $this->updateTrade($id);
            $data = Trade::findOrFail($id);
            if (!$data) {
                return 'not found';
            }

            return response()->json($data);
        } catch (\Throwable $th) {
            return response()->json(['message' => $th->getMessage()], 500);
        }
    }

//    disabled autoclose
    public function checkTrades(){
        $user = Auth('api')->user();
        $trades = Trade::where('user_id', $user->id)->whereStatus(0)->where('autoclose',1)->get();
        if (count($trades) > 0) {
            $data['status'] = 0;
            foreach ($trades as $item){
                $time = Carbon::now();
                $end = $item->close_at;
                if($time > $end){
                    $data['status'] = 1;
//                    $this->updateTrade($item->id);
                }
            }
        }else {
            $data['status'] = 0;
        }
        return response()->json($data);
    }


    public function myTrades(){
        $user = Auth::user();
        return $data['pnl'] = Trade::where('user_id', $user->id)->whereStatus(0)->sum('profit');
    }

    public function openedTrades(){
        $user = Auth('api')->user();
        $trades = Trade::where('user_id', $user->id)->whereStatus(0)->get();
        return response()->json($trades);
    }

    public function closedTrades(){
        $user = Auth('api')->user();
        $trades = Trade::where('user_id', $user->id)->whereStatus(1)->get();
        return response()->json($trades);
    }

    public function loopTrades(){
        $user = Auth('api')->user();
        $trades = Trade::where('user_id', $user->id)->whereStatus(0)->get();
        if (count($trades) > 0) {
            if($this->checkFreeMargin($user->id)){
                foreach ($trades as $item) {
                    $this->updateTradeProfit($item->id);
                }
            }
        }
        return response()->json($trades);
    }

    public function checkFreeMargin($user_id){
        $user = User::findOrFail($user_id);
        $pnl = Trade::whereStatus(0)->whereUserId($user_id)->sum('profit');
        $total_trades = Trade::whereStatus(0)->whereUserId($user_id)->sum('traded_amount');
        $com = Trade::whereStatus(0)->whereUserId($user_id)->sum('paid_com');
        $profit = $pnl - ($com);
        $bal = $user->userInfo->balance;
        $equity = ($profit) + $bal + $total_trades;
        if($equity < 2){
            $this->closeAllUserTrades($user->id);
        }
        return true;
    }

    public function closeAllTrades()
    {
        $user = Auth('api')->user();
        $trades = Trade::where('user_id', $user->id)->whereStatus(0)->get();
        if (count($trades) > 0) {
            foreach ($trades as $item) {
                $this->updateTrade($item->id);
            }
        }
    }

    public function closeAllUserTrades($user_id){
        $trades = Trade::where('user_id', $user_id)->whereStatus(0)->get();
        if (count($trades) > 0) {
            foreach ($trades as $item) {
                $this->updateTrade($item->id);
            }
        }
        return response()->json($trades);
    }

    public function getBalance(){
        return Auth('api')->user()->balance;
    }

    public function updateTradeProfit($id){
        $trade = Trade::findOrFail($id);
        $rate = optional($trade->currency)->rate;

        $coinPrice = $this->getCurRate($trade->currency->sym, $trade->currency->base,$trade->currency->type);
        if($coinPrice === 0){
            $coinPrice = $rate;
        }
        $old_coin_value = $trade->amount / $trade->opening_price;
        $trade->closing_price =  $coinPrice;
        $cryptoRate = $coinPrice;
        $trade->close_at = null;
        $pl = abs(($old_coin_value * $cryptoRate) - $trade->amount);

        $trade->profit = $pl;

        if($trade->trade_type == 'buy')
        {
            if($trade->opening_price > $cryptoRate)
            {
                $trade->profit = $pl * (-1);
                $trade->save();
            }
            else if ($trade->opening_price < $cryptoRate) {
                $trade->profit = $pl;
                $trade->save();
            }
        } else if($trade->trade_type == 'sell')
        {
            if($trade->opening_price < $cryptoRate)
            {
                $trade->profit = $pl * (-1);
                $trade->save();
            }
            else if($trade->opening_price > $cryptoRate)
            {
                $trade->profit = $pl;
                $trade->save();
            }
        }

        //check profit / loss
        if($trade->is_take_profit){
            if($trade->profit >= $trade->take_profit){
                $this->updateTrade($trade->id);
            }
        }
        if($trade->is_stop_loss){
            if(((-1)*$trade->stop_loss) >= $trade->profit){
                $this->updateTrade($trade->id);
            }
        }
    }

    public function updateTrade($id){
//        $user = Auth::user();
        $trade = Trade::findOrFail($id);
        $user = User::findOrFail($trade->user_id);

        $trade->close_at = Carbon::now();

        $pl =  $trade->profit;

        if($trade->status === 0) {
            if($trade->trade_type == 'buy')
            {
                if($trade->opening_price > $trade->closing_price)
                {

                    $msg = 'Traded  '.optional($trade->currency)->name. ' lost';
                    $amt = $trade->traded_amount + ($pl);
                    $this->tradeAddBalance($user, $amt, $msg);

                    $trade->result = 2;
                    $trade->status = 1;
                    $trade->save();
                    return 2;
                }
                else if ($trade->opening_price < $trade->closing_price) {
//                    $user->userInfo->balance += ($trade->traded_amount + $pl);
                    $msg = 'Traded  '.optional($trade->currency)->name. ' won';
                    $amt = $trade->traded_amount + ($pl);
                    $this->tradeAddBalance($user, $amt, $msg);
                    $user->save();
                    $trade->result = 1;
                    $trade->status = 1;
                    $trade->save();
                    return 1;
                }else{
//                    $user->userInfo->balance += $trade->traded_amount;
//                    $user->save();
                    $msg = 'Traded  '.optional($trade->currency)->name. ' draw';
                    $amt = $trade->traded_amount;
                    $this->tradeAddBalance($user, $amt, $msg);

                    $trade->result = 3;
                    $trade->status = 1;
                    $trade->save();
                    return 3;
                }
            }
            else if($trade->trade_type == 'sell')
            {
                if($trade->opening_price < $trade->closing_price)
                {
//                    $user->userInfo->balance += ($trade->traded_amount - $pl);
//                    $user->save();

                    $msg = 'Traded  '.optional($trade->currency)->name. ' lost';
                    $amt = $trade->traded_amount + ($pl);
                    $this->tradeAddBalance($user, $amt, $msg);

                    $trade->result = 2;
                    $trade->status = 1;
                    $trade->save();
                    return 2;
                }
                else if($trade->opening_price > $trade->closing_price)
                {
//                    $user->userInfo->balance += ($trade->traded_amount + $pl);
//                    $user->save();
                    $msg = 'Traded  '.optional($trade->currency)->name. ' won';
                    $amt = $trade->traded_amount + ($pl);
                    $this->tradeAddBalance($user, $amt, $msg);
                    $trade->result = 1;
                    $trade->status = 1;
                    $trade->save();
                    return 1;
                }
                else{
//                    $user->userInfo->balance += $trade->traded_amount;
//                    $user->save();

                    $msg = 'Traded  '.optional($trade->currency)->name. ' draw';
                    $amt = $trade->traded_amount;
                    $this->tradeAddBalance($user, $amt, $msg);

                    $trade->result = 3;
                    $trade->status = 1;
                    $trade->save();
                    return 3;
                }
            }
        }
    }



    public function updateActiveTrades(){
        $trades = Trade::whereStatus(0)->get();
        foreach ($trades as $item){
            $date =  Carbon::createFromFormat('Y-m-d H:i:s', $item->created_at)->format('Y-m-d');
            $item->overnight = $date;
            $item->save();
        }

        return count($trades);

    }

    public function takeCom(){
//        if(setting('overnight_com'))
        $overnight = setting('overnight', 4);
        $trades = Trade::whereStatus(0)->where('overnight', '<=', Carbon::now()->subDay()->toDateTimeString())->get();
        foreach ($trades as $item){
            $com = ((float)$item->traded_amount * (int)$overnight) / 100;
            $new_date = Carbon::createFromFormat('Y-m-d H:s:i', $item->overnight)->addDay();
            $item->overnight = $new_date;
            $item->paid_com = $item->paid_com + $com;
            $item->save();
            $this->storeCom($item, $com,$overnight, $new_date);
        }
        return count($trades);
    }

    public function storeCom($trade, $com, $fee, $charged_at){
        Overnight::create([
            'com' => $com,
            'trade_id' => $trade->id,
            'user_id' => $trade->user_id,
            'fee' => $fee,
            'charged_at' => $charged_at,
            'amount' => $trade->traded_amount,
        ]);
    }

}
