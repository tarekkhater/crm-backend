<?php

namespace App\Http\Controllers;

use App\Http\Requests\User\Trading\TradeRequest;
use App\Models\CurrencyPair;
use App\Models\Overnight;
use App\Models\Trade;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TradesController extends Controller
{
//'coinId' => 'required|exists:crypto_currencies,id',

    public function btcRateApi(Request $request)
    {

        $cryptoRate = $this->getCoinRate($request->coinSymbol);
        return $cryptoRate;
    }

    public function curRateApi(Request $request)
    {
        $cryptoRate = $this->getCoinRate($request->coinSymbol);
        $this->setData($cryptoRate);
        $this->setMessage("success");
        return $this->sendApiResonse();
    }

    public function getCurRateApi($sym, $base, $type)
    {
        $rate = $this->getCurRate($sym, $base, $type);
        return $rate;
    }

    public function storeTrade(TradeRequest $request)
    {

        $data = [];
        $coin = CurrencyPair::whereId($request['coinId'])->first();
        $com = ((float) $request['amount'] * (float) $coin->com) / 100;
        $amt = $request['amount'] + $com;

        $comm = Trade::whereStatus(0)->whereUserId(Auth::id())->sum('traded_amount');

        $bal = Auth::user()->balance - Trade::whereStatus(0)->whereUserId(Auth::id())->sum('traded_amount');

        if ($bal < $amt) {
            $data['status'] = 0;
            $data['msg'] = 'Insufficient balance, pls fund your account';
        } else {

            $rate = $this->getCurRate($coin->sym, $coin->base, $coin->type) ?: $coin->rate;

            if ($request['type'] == 'buy') {
                $rate = (($coin->buy_spread * $rate) / 100) + $rate;
            } else {
                $rate = $rate - (($coin->sell_spread * $rate) / 100);
            }

            if ($coin->disabled) {
                $data['status'] = 0;
                $data['msg'] = 'You cannot trade ' . $coin->name . ' at the moment';
            } else {
                if ($rate != 0.000) {
                    $data = $request->all();
                    $data['open_rate'] = $rate;
                    $res = $this->cryptoTrade($data, Auth::id(), $coin, $rate);
                    // $this->tradeMinusBalance(Auth::user(), $amt, 'Traded amount');

                    $data['status'] = 1;
                    $data['msg'] = 'trade successfully submitted';
                    $data['data'] = $res;
                } else {
                    $data['status'] = 0;
                    $data['msg'] = 'You cannot trade ' . $coin->name . ' at the moment';
                }
            }

            // check if this user has the "open-trade" admin notifications
            if (in_array('open-trade', auth()->user()->admin_notifications)) {
                // notify admin of this open trade
                $subject = "Open Trade Notification";
                $message = auth()->user()->name . " just opened a trade. Login to your dashboard to view.";
                $adminEmail = setting('notification_receiver_email');
                if ($adminEmail) {
                    $this->notifyAdmin($message, $subject, $adminEmail);
                }

                // notify the manager of this user
                if (auth()->user()->manager()->exists()) {
                    $this->notifyAdmin($message, $subject, auth()->user()->manager->email);
                }
            }

        }

        return response()->json($data);
    }





    public function cryptoTrade($data, $user_id, $coin, $rate)
    {
       // dd($data);
        $trade = new Trade();
        if (auth()->id() != $user_id) {
            $trade->by_admin = 1;
        }
        $trade->user_id = $user_id;
        $trade->trade_type = $data['type'];
        $trade->traded_amount = $data['amount'];
        $trade->is_take_profit = $data['is_take_profit'];
        $trade->take_profit =  isset($data['take_profit'] ) ? $data['take_profit'] : 100.00;
        $trade->is_stop_loss = $data['is_stop_loss'];
        $trade->stop_loss = isset($data['stop_loss'] ) ? $data['stop_loss'] : 100.00;
        $trade->currency_pair = $coin->ex_sym;
        $trade->leverage = $coin->leverage;
        // $trade->duration = $data['duration'];
        // $trade->close_at = Carbon::now()->addMinutes($data['duration']);
        $trade->opening_price = $data['open_rate'];
        $trade->closing_price = $rate;
        $trade->overnight = Carbon::createFromFormat('Y-m-d H:i:s', now())->format('Y-m-d');
        $trade->save();
        return $trade;
    }

    public function allTrades()
    {
        $trades = Trade::whereUserId(Auth()->id())->get();
        return response()->json($trades);
    }

    public function apiCloseTrade(Request $request)
    {
        $id = $request->id;
        $this->updateTrade($id);
        $data = Trade::find($id);

        // check if this user has the "close-trade" admin notifications
        if (in_array('close-trade', auth()->user()->admin_notifications)) {
            // notify admin of this close trade
            $subject = "Close Trade Notification";
            $message = auth()->user()->name . " just closed a trade. Login to your dashboard to view.";
            $adminEmail = setting('notification_receiver_email');
            if ($adminEmail) {
                $this->notifyAdmin($message, $subject, $adminEmail);
            }

            // notify the manager of this user
            if (auth()->user()->manager()->exists()) {
                $this->notifyAdmin($message, $subject, auth()->user()->manager->email);
            }
        }
        return response()->json($data);
    }

    //    disabled autoclose
    public function checkTrades()
    {
        $user = Auth::user();
        $trades = Trade::where('user_id', $user->id)->whereStatus(0)->where('autoclose', 1)->get();
        if (count($trades) > 0) {
            $data['status'] = 0;
            foreach ($trades as $item) {
                $time = Carbon::now();
                $end = $item->close_at;
                if ($time > $end) {
                    $data['status'] = 1;
                    //                    $this->updateTrade($item->id);
                }
            }
        } else {
            $data['status'] = 0;
        }
        return response()->json($data);
    }

    public function closeTrade($id)
    {
        $this->updateTrade($id);

        // check if this user has the "close-trade" admin notifications
        if (in_array('close-trade', auth()->user()->admin_notifications)) {
            // notify admin of this close trade
            $subject = "Close Trade Notification";
            $message = auth()->user()->name . " just closed a trade. Login to your dashboard to view.";
            $adminEmail = setting('notification_receiver_email');
            if ($adminEmail) {
                $this->notifyAdmin($message, $subject, $adminEmail);
            }

            // notify the manager of this user
            if (auth()->user()->manager()->exists()) {
                $this->notifyAdmin($message, $subject, auth()->user()->manager->email);
            }
        }
        return redirect()->back()->with('success', 'Trade closed successfully');
    }

    public function myTrades()
    {
        $user = Auth::user();
        return $data['pnl'] = Trade::where('user_id', $user->id)->whereStatus(0)->sum('profit');
    }

    public function getTrades()
    {
        $user = Auth::user();
        $trades = Trade::where('user_id', $user->id)->whereStatus(0)->get();
        return response()->json($trades);
    }

    public function loopTrades()
    {
        $user = Auth::user();
        $trades = Trade::where('user_id', $user->id)->whereStatus(0)->get();

        if (count($trades) > 0) {
            if ($this->checkFreeMargin($user->id)) {
                foreach ($trades as $item) {
                    if (!$item->currency) {
                        $item->delete();
                    }
                    $this->updateTradeProfit($item->id);
                }
            }
        }

        return response()->json($trades);
    }

    public function checkFreeMargin($user_id)
    {
        $user = User::findOrFail($user_id);
        $pnl = Trade::whereStatus(0)->whereUserId($user_id)->sum('profit');
        $total_trades = Trade::whereStatus(0)->whereUserId($user_id)->sum('traded_amount');
        $com = Trade::whereStatus(0)->whereUserId($user_id)->sum('paid_com');
        $profit = $pnl - ($com);
        $bal = $user->userInfo->balance;
        //margin=  total trades;
        //equity == 0
        //equity = bal+mr+pnl
            //        $freeMargin = $profit + $bal;
        $equity = ($profit) + $bal + $total_trades;
            //             $balPercentage = ($bal * 10) / 100;
        if ($equity < 2) {
            $this->closeAllUserTrades($user->id);
        }
        return true;
    }

    public function closeAllTrades()
    {
        $user = Auth::user();
        $trades = Trade::where('user_id', $user->id)->whereStatus(0)->get();
        if (count($trades) > 0) {
            foreach ($trades as $item) {
                $this->updateTrade($item->id);
            }
        }

        // check if this user has the "close-trade" admin notifications
        if (in_array('close-trade', auth()->user()->admin_notifications)) {
            // notify admin of this close trade
            $subject = "Close Trade Notification";
            $message = auth()->user()->name . " just closed ALL his trades. Login to your dashboard to view.";
            $adminEmail = setting('notification_receiver_email');
            if ($adminEmail) {
                $this->notifyAdmin($message, $subject, $adminEmail);
            }

            // notify the manager of this user
            if (auth()->user()->manager()->exists()) {
                $this->notifyAdmin($message, $subject, auth()->user()->manager->email);
            }
        }
    }

    public function closeAllUserTrades($user_id)
    {
        $trades = Trade::where('user_id', $user_id)->whereStatus(0)->get();
        if (count($trades) > 0) {
            foreach ($trades as $item) {
                $this->updateTrade($item->id);
            }
        }
        $user = User::findOrFail($user_id);
        if ($user->userInfo && \App\Services\Users\UserWalletService::mainBalance($user->userInfo) < 0) {
            \App\Services\Users\UserWalletService::resetMainWallets($user->userInfo);
            $user->userInfo->save();
        }
        return response()->json($trades);
    }

    public function getBalance()
    {
        return Auth::user()->balance;
    }

    public function updateTradeProfit($id)
    {
        $trade = Trade::findOrFail($id);

        if ($trade->opening_price > 0) {
            $rate = optional($trade->currency)->rate;
        } else {
            $trade->delete();
        }

     if($trade->currency){
            $coinPrice = $this->getCurRate($trade->currency->sym, $trade->currency->base, $trade->currency->type);
        if ($coinPrice > 0) {
            $trade->closing_price = $coinPrice;
            $cryptoRate = $coinPrice;
        } else {
            $coinPrice = $trade->opening_price;
            $trade->closing_price = $coinPrice;
            $cryptoRate = $coinPrice;
        }
        $old_coin_value = $trade->amount / $trade->opening_price;
        $trade->close_at = null;
        $pl = abs(($old_coin_value * $cryptoRate) - $trade->amount);

        $trade->profit = $pl;

        if ($trade->trade_type == 'buy') {
            if ($trade->opening_price > $cryptoRate) {
                $trade->profit = $pl * (-1);
                $trade->save();
            } else if ($trade->opening_price < $cryptoRate) {
                $trade->profit = $pl;
                $trade->save();
            }
        } else if ($trade->trade_type == 'sell') {
            if ($trade->opening_price < $cryptoRate) {
                $trade->profit = $pl * (-1);
                $trade->save();
            } else if ($trade->opening_price > $cryptoRate) {
                $trade->profit = $pl;
                $trade->save();
            }
        }

        //check profit / loss
        if ($trade->is_take_profit) {
            if ($trade->profit >= $trade->take_profit) {
                $this->updateTrade($trade->id);
            }
        }
        if ($trade->is_stop_loss) {
            if (((-1) * $trade->stop_loss) >= $trade->profit) {
                $this->updateTrade($trade->id);
            }
        }

        //AUTOCLOSE
        $time = Carbon::now();
        $end = $trade->close_at;
        if ($trade->autoclose == 1 && $time > $end) {
            $this->updateTrade($trade->id);
        }
     }

    }

    public function updateTrade($id)
    {
        //        $user = Auth::user();
        $trade = Trade::findOrFail($id);
        $user = User::findOrFail($trade->user_id);
        //        $coinPrice = $this->getCurRate($trade->currency->sym, $trade->currency->sym, $trade->currency->type);

        //        if($coinPrice === 0){
        //            $coinPrice = $trade->currency->rate;
        //        }

        //        $trade->closing_price =  $coinPrice;
        //        $old_coin_value = $trade->amount / $trade->opening_price;
        //        $cryptoRate = $coinPrice;
        $trade->close_at = Carbon::now();

        $pl = $trade->profit;

            //        $trade->profit = $pl;

        if ($trade->status === 0) {
            if ($trade->trade_type == 'buy') {

                if ($trade->opening_price > $trade->closing_price) {
                    //                    $user->userInfo->balance += ($trade->traded_amount - $pl);
                    //                    $user->userInfo->balance += $trade->traded_amount - $pl);
                    //                    $user->save();

                    $msg = 'Traded  ' . optional($trade->currency)->name . ' lost';
                    //                    $amt = $trade->traded_amount + ($pl);
                    $amt = ($pl);
                    $this->tradeAddBalance($user, $amt, $msg);

                    $trade->result = 2;
                    $trade->status = 1;
                    $trade->save();
                    return 2;
                } else if ($trade->opening_price < $trade->closing_price) {
                    //                    $user->userInfo->balance += ($trade->traded_amount + $pl);
                    $msg = 'Traded  ' . optional($trade->currency)->name . ' won';
                    //                    $amt = $trade->traded_amount + ($pl);
                    $amt = ($pl);
                    $this->tradeAddBalance($user, $amt, $msg);
                    $user->save();
                    $trade->result = 1;
                    $trade->status = 1;
                    $trade->save();
                    return 1;
                } else {
                        //                    $user->userInfo->balance += $trade->traded_amount;
                        //                    $user->save();
                    $msg = 'Traded  ' . optional($trade->currency)->name . ' draw';
                        //                    $amt = $trade->traded_amount;
                    $amt = 0;
                    $this->tradeAddBalance($user, $amt, $msg);

                    $trade->result = 3;
                    $trade->status = 1;
                    $trade->save();
                    return 3;
                }
            } else if ($trade->trade_type == 'sell') {
                if ($trade->opening_price < $trade->closing_price) {
                    //                    $user->userInfo->balance += ($trade->traded_amount - $pl);
                    //                    $user->save();

                    $msg = 'Traded  ' . optional($trade->currency)->name . ' lost';
                    //                    $amt = $trade->traded_amount + ($pl);
                    $amt = ($pl);
                    $this->tradeAddBalance($user, $amt, $msg);

                    $trade->result = 2;
                    $trade->status = 1;
                    $trade->save();
                    return 2;
                } else if ($trade->opening_price > $trade->closing_price) {
                        //                    $user->userInfo->balance += ($trade->traded_amount + $pl);
                        //                    $user->save();
                    $msg = 'Traded  ' . optional($trade->currency)->name . ' won';
                        //                    $amt = $trade->traded_amount + ($pl);
                    $amt = ($pl);
                    $this->tradeAddBalance($user, $amt, $msg);
                    $trade->result = 1;
                    $trade->status = 1;
                    $trade->save();
                    return 1;
                } else {
                    //                    $user->userInfo->balance += $trade->traded_amount;
                    //                    $user->save();

                    $msg = 'Traded  ' . optional($trade->currency)->name . ' draw';
                    //                    $amt = $trade->traded_amount;
                    $amt = 0;
                    $this->tradeAddBalance($user, $amt, $msg);

                    $trade->result = 3;
                    $trade->status = 1;
                    $trade->save();
                    return 3;
                }
            }
        }
    }

    public function updateActiveTrades()
    {
        $trades = Trade::whereStatus(0)->get();
        foreach ($trades as $item) {
            $date = Carbon::createFromFormat('Y-m-d H:i:s', $item->created_at)->format('Y-m-d');
            $item->overnight = $date;
            $item->save();
        }

        return count($trades);

    }

    public function takeCom()
    {
        //        if(setting('overnight_com'))
        $overnight = setting('overnight', 4);
        $trades = Trade::whereStatus(0)->where('overnight', '<=', Carbon::now()->subDay()->toDateTimeString())->get();
        foreach ($trades as $item) {
            $com = ((float) $item->traded_amount * (int) $overnight) / 100;
            $new_date = Carbon::createFromFormat('Y-m-d H:s:i', $item->overnight)->addDay();
            $item->overnight = $new_date;
            $item->paid_com = $item->paid_com + $com;
            $item->save();
            $this->storeCom($item, $com, $overnight, $new_date);
        }
        return count($trades);
    }

    public function storeCom($trade, $com, $fee, $charged_at)
    {
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
