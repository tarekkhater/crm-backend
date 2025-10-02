<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\CurrencyPair;
use App\Models\Trade;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use PDF;

class TradesController extends Controller
{
    public function __construct()
    {
        $this->middleware('RoleMiddleware:view-recent-trade')->only('trades');
    }

    public function index(Request $request)
    {
        $user = [];
        if ($request->has('user')) {
            $id = $request->get('user');
            $user = User::findOrFail($id);
            $trades = Trade::whereUserId($id)->whereStatus(1)->get();
            $p_trade = Trade::whereUserId($id)->whereStatus(0)->get();
            $title = 'Trade For ' . ucfirst($user->name);
        } else {
            $title = 'All Trades';
            $trades = Trade::all();
            return redirect()->back();
        }
        $all_trades = $trades->toArray();

        if ($request->has('asset')) {
            $c_type = $request->get('asset');
        } else {
            $c_type = 'crypto';
        }

//        return $trades;
        return view('admin.trades.trades-list', compact('trades', 'title', 'user', 'all_trades', 'p_trade', 'c_type'));
    }

    public function trades(Request $request)
    {
        $user = [];
        if (check_permission('view-withdrawals')) {
            $users = User::whereRoleIs('user')->where('manager_id', Auth::user()->id)->get();
            foreach($users as $user) {
                $title = 'All Trades';
                $trades[] = Trade::whereStatus(0)->where('user_id',$user->id)->get();
                $p_trade[] = Trade::whereStatus(0)->where('user_id',$user->id)->get();

            }
                 $all_trades = $trades[0];
                 $p_trade = $p_trade[0];



        } else {
            $title = 'All Trades';
            $trades = Trade::whereStatus(0)->get();
            $p_trade = Trade::whereStatus(0)->get();

        $all_trades = $trades->toArray();
        }




        if ($request->has('asset')) {
            $c_type = $request->get('asset');
        } else {
            $c_type = 'crypto';
        }
        return view('admin.trades.all-trids', compact('trades', 'title', 'user', 'all_trades', 'p_trade', 'c_type'));
    }


    public function getAllTrades($id)
    {
        $trades = Trade::whereUserId($id)->whereStatus(0)->get();

        return response()->json($trades);

    }
    public function getAllTradesList()
    {
       // $trades = Trade::with('user')->whereStatus(0)->get();
       // $trades = $trades->toArray();

       if (check_permission('Dashboard-data-manger')) {
        $users = User::whereRoleIs('user')->where('manager_id', Auth::user()->id)->get();
        foreach($users as $user) {
            $trades[] = Trade::whereStatus(0)->where('user_id',$user->id)->get();
        }
             $trades = $trades[0];

    } else {
        $trades = Trade::with('user')->whereStatus(0)->get();

    }


        return response()->json($trades);

    }

    public function updateProfit($user_id)
    {
        $user = User::findOrFail($user_id);
        $tradeCon = new \App\Http\Controllers\TradesController();

        $trades = Trade::where('user_id', $user->id)->whereStatus(0)->get();
        if (count($trades) > 0) {
            if ($tradeCon->checkFreeMargin($user->id)) {
                foreach ($trades as $item) {
                    if (!$item->currency) {
                        $item->delete();
                    }
                    $tradeCon->updateTradeProfit($item->id);
                }
            }
        }
        return response()->json($trades);
    }

    public function create()
    {
        return view('admin.trades.trades-list');
    }

    public function storeOld(Request $request)
    {
        $data = $this->getData($request);
        $user = User::findOrFail($data['user_id']);

        if ($user->userInfo->balance < $data['amount']) {
            return redirect()->back()->with('failure', "You cant trade more than $user->userInfo->balance");
        }

        $coin = CurrencyPair::whereId($request['coin_id'])->first();
        $data['currency_pair'] = $coin->ex_sym;
        $data['leverage'] = $coin->leverage;
        $data['status'] = 1;

//        if(setting('real_trade',0) > 0){
        $data['opening_price'] = $coin->rate;
        $data['closing_price'] = $coin->rate;
//        }else{
//            $data['opening_price'] = $data['traded_amount'];
//        }

        $data['close_at'] = Carbon::now()->addMinutes($data['duration']);

        $trade = Trade::create($data);

        if ($data['record'] > 0) {
            if ($trade->result === 1) {
                $amount = $trade->profit;
                $msg = "Trade profit or lost topup";
                $this->tradeAddBalance($user, $amount, $msg);
            } else {
                $amount = $trade->profit;
                $msg = "Trade profit or lost debit";
                $this->tradeMinusBalance($user, $amount, $msg);
                $user->save();
            }
        } else {
            if ($trade->result === 1) {
                $user->userInfo->balance = $user->userInfo->balance + $trade->profit;
                $user->save();
            } else {
                $user->userInfo->balance = $user->userInfo->balance - $trade->profit;
                $user->save();
            }
        }

        return redirect()->back()->with('success', 'Trade was successfully added.');
    }

    public function getCoin($id)
    {
        $coin = CurrencyPair::whereId($id)->first();
        return response()->json($coin);
    }

    public function store(Request $request)
    {
        $tradeCon = new \App\Http\Controllers\TradesController();
        $data = $this->getData($request);
        $user = User::findOrFail($data['user_id']);

        $coin = CurrencyPair::whereId($request['coin_id'])->first();

        $com = ((float) $request['amount'] * (float) $coin->com) / 100;
        $amt = $request['amount'] + $com;

        $bal = $user->userInfo->balance;

        //        $bal = $user->userInfo->balance - Trade::whereStatus(0)->whereUserId($user->id)->sum('traded_amount');

        if ($bal < $amt) {
            return redirect()->back()->with('failure', "You cant trade more than $bal, trade amount with commission => $amt");
        }

        $data['currency_pair'] = $coin->ex_sym;
        $data['leverage'] = $coin->leverage;
        $data['status'] = 1;
        $data['open_rate'] = $data['value'];

        $rate = $this->getCurRate($coin->sym, $coin->base, $coin->type) ?: $coin->rate;

        if ($coin->disabled) {
            $data['status'] = 0;
            $data['msg'] = 'You cannot trade ' . $coin->name . ' at the moment';
            return redirect()->back()->with('failure', $data['msg']);
        } else {
            if ($rate != 0.000) {
                $res = $tradeCon->cryptoTrade($data, $data['user_id'], $coin, $rate);
                //                $this->tradeMinusBalance($user, $amt, 'Traded amount');
                $data['status'] = 1;
                $data['msg'] = 'trade successfully submited';
                $data['data'] = $res;
            } else {
                $data['status'] = 0;

                $data['msg'] = 'You cannot trade ' . $coin->name . ' at the moment';
                return redirect()->back()->with('failure', $data['msg']);

            }
        }

        return redirect()->back()->with('success', 'Trade was successfully added.');
    }

    public function edit($id)
    {
        $trade = Trade::findOrFail($id);
        return view('admin.trades.trades-edit', compact('trade'));
    }

    public function update(Request $request, $id)
    {
        $trades = Trade::findOrFail($id);
        $trades->update($this->getData($request));
        return redirect()->route('admin.trades.index');
    }

    public function updateTrade(Request $request)
    {
        $trades = Trade::findOrFail($request['id']);
        $trades->opening_price = $request['opening_price'];
        // $trades->create_at = $request['create_at'];
        // $trades->close_at = $request['close_at'];


        if ($request->has('api')) {
            $newTrade = $this->TradeProfit($trades);
            return response()->json($newTrade);
        }
        $trades->save();
        return redirect()->back()->with('success', 'Trade updated');
    }

    public function TradeProfit($trade)
    {
        $rate = optional($trade->currency)->rate;
        $coinPrice = $this->getCurRate($trade->currency->sym, $trade->currency->base, $trade->currency->type);
        if ($coinPrice === 0) {
            $coinPrice = $rate;
        }
        $old_coin_value = $trade->amount / $trade->opening_price;
        $trade->closing_price = $coinPrice;
        $cryptoRate = $coinPrice;
        $trade->close_at = null;
        $pl = abs(($old_coin_value * $cryptoRate) - $trade->amount);

        $trade->profit = $pl;

        if ($trade->trade_type == 'buy') {
            if ($trade->opening_price > $cryptoRate) {
                $trade->profit = $pl * (-1);
//                $trade->save();
            } else if ($trade->opening_price < $cryptoRate) {
                $trade->profit = $pl;
//                $trade->save();
            }
        } else if ($trade->trade_type == 'sell') {
            if ($trade->opening_price < $cryptoRate) {
                $trade->profit = $pl * (-1);
//                $trade->save();
            } else if ($trade->opening_price > $cryptoRate) {
                $trade->profit = $pl;
//                $trade->save();
            }
        }
        return $trade;
    }

    public function destroy($id)
    {
        $trades = Trade::findOrFail($id);
        $trades->delete();
        return redirect()->back()->with('success', 'Trade deleted');
    }

    public function storeTradeBAD(Request $request)
    {
        $request->validate([
            'amount' => 'required|numeric|gt:0',
            'coinId' => 'required',
            'type' => 'required',
            'is_take_profit' => 'required',
            'is_stop_loss' => 'required',
            'stop_loss' => 'required',
            'take_profit' => 'required',
            'duration' => 'required|numeric|gt:0',
        ]);
        $data = [];
        $coin = CurrencyPair::whereId($request['coinId'])->first();
        $com = ((float) $request['amount'] * (float) $coin->com) / 100;
        $amt = $request['amount'] + $com;
        if (Auth::user()->balance < $amt) {
            $data['status'] = 0;
            $data['msg'] = 'Insufficient balance, pls fund your account';
        }
        $rate = $this->getCurRate($coin->sym, $coin->base, $coin->type) ?: $coin->rate;
        if ($coin->disabled) {
            $data['status'] = 0;
            $data['msg'] = 'You cannot trade ' . $coin->name . ' at the moment';
        } else {
            if ($rate != 0.000) {
                $res = $this->cryptoTrade($request->all(), $coin, $rate);
                $this->tradeMinusBalance(Auth::user(), $amt, 'Traded amount');

                $data['status'] = 1;
                $data['msg'] = 'trade successfully submited';
                $data['data'] = $res;
            } else {
                $data['status'] = 0;
                $data['msg'] = 'You cannot trade ' . $coin->name . ' at the moment';
            }
        }

        return response()->json($data);
    }

    protected function getDataOld(Request $request)
    {
        $rules = [
            'user_id' => 'required',
            'coin_id' => 'required',
            'trade_type' => 'required|string',
            'duration' => 'required',
            'status' => 'nullable',
            'record' => 'required',
            'result' => 'nullable',
            'amount' => 'required',
            'profit' => 'nullable',
        ];

        return $request->validate($rules);
    }
    protected function getData(Request $request)
    {
        $rules = [
            'user_id' => 'required',
            'amount' => 'required|numeric|gt:0',
            'coin_id' => 'required',
            'type' => 'required',
            'is_take_profit' => 'required',
            'value' => 'required',
            'is_stop_loss' => 'required',
            'stop_loss' => 'nullable',
            'take_profit' => 'nullable',
            'duration' => 'nullable',
        ];

        $data = $request->validate($rules);

        if (!isset($data['take_profit'])) {
            $data['take_profit'] = 100;
            $data['stop_loss'] = 100;
            $data['duration'] = 120;
        }

        return $data;
    }

    public function deleteInvalidTrades()
    {
        $trades = Trade::all();
        $i = 1;
        foreach ($trades as $item) {
            if (!$item->currency) {
                $item->delete();
                $i++;
            }
        }
        return 'deleted ' . $i;
    }

}
