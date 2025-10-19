<?php

namespace App\Http\Controllers\admin\Trading;
use App\Http\Controllers\Controller;

use App\Models\CurrencyPair;
use App\Models\Overnight;
use App\Models\Trade;
use App\Models\User;
use App\Models\Position;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\User\Trading\TradeRequest;
use App\Services\TradeService;
use App\Http\Resources\PositionResource;
use App\Http\Resources\User\Assets\AssetsResource;
class IndexController extends Controller
{
//'coinId' => 'required|exists:crypto_currencies,id',
protected $seviceTrade;


 public function __construct() {
        $this->seviceTrade = new TradeService();
    }
    
public function searchrequest(Request $request) {
    $data = [];
    $idsUser = getUsersIds();
    if (isset($request->search) && is_array($request->search)) {
        foreach ($request->search as $req) {
            $data[] = [
                "key" => $req['key'],
                "value" => $req['value']
            ];
        }
    }
     $trades = Trade::whereIn('user_id',$idsUser)->where('status',1)->where($data)->paginate(15);

    return response()->json($trades);
}

public function filterText(){
    $data = [];
    $idsUser = getUsersIds();
    if (isset($request->search) && is_array($request->search)) {
        foreach ($request->search as $req) {
            $data[] = [
                "key" => $req['key'],
                "value" => $req['value']
            ];
        }
    }
     $trades = Trade::whereIn('user_id',$idsUser)->where('status',1)->where($data)->paginate(15);

    return response()->json($trades);
}
    public function btcRateApi(Request $request)
    {
        $cryptoRate = $this->getCoinRate($request->coinSymbol);
        return $cryptoRate;
    }

    public function curRateApi(Request $request)
    {
        $cryptoRate = $this->getCoinRate($request->coinSymbol);
        return $cryptoRate;
    }

    public function getCurRateApi($sym, $base, $type)
    {
        $rate = $this->getCurRate($sym, $base, $type);
        return $rate;
    }

    public function close(){
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
                 $idsUser = getUsersIds();
        $trades = $this->seviceTrade->indexClose($idsUser);
        return PositionResource::collection($trades);
                
                
        $trades = Trade::whereIn('user_id',$idsUser)->where('status',1)->where($data)->paginate(15);
        $result = [
            'data'         => $this->resposeDataclose($trades->items()),
            'current_page' => $trades->currentPage(),
            'from'         => $trades->firstItem(),
            'last_page'    => $trades->lastPage(),
            'links'        => $trades->links()?$trades->links():[],
            'per_page'     => $trades->perPage(),
            'to'           => $trades->lastItem(),
            'total'        => $trades->total(),
        ];
        $this->setData($result);
        $this->setMessage("success");
        return $this->sendApiResonse();

    }

    public function open(){
        
         
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
        $idsUser = getUsersIds();
        $trades = $this->seviceTrade->index($idsUser);
        return PositionResource::collection($trades);
        
        // $trades = Trade::whereIn('user_id',$idsUser)->where($data)->Where('is_pending_order',null)->whereStatus(0)->paginate(15);
        // $result = [
        //     'data'         => $this->resposeData($trades->items()),
        //     'current_page' => $trades->currentPage(),
        //     'from'         => $trades->firstItem(),
        //     'last_page'    => $trades->lastPage(),
        //     'links'        => $trades->links(),
        //     'per_page'     => $trades->perPage(),
        //     'to'           => $trades->lastItem(),
        //     'total'        => $trades->total(),
        // ];
        $this->setData($this->resposeData($trades->items()));
        $this->setMessage("success");
        return $this->sendApiResonse();
    }
    
    public function pending(){
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
        $idsUser = getUsersIds();
        $trades = Trade::whereIn('user_id',$idsUser)->where($data)->where('is_pending_order','<>',null)->whereStatus(0)->paginate(15);
        
        $this->setData($this->resposeData($trades->items()));
        $this->setMessage("success");
        return $this->sendApiResonse();
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
        'opening_price' => 'required|numeric|gt:0',
        'leverage' => 'required|numeric|gt:0',
        'amount' => 'nullable|numeric|gt:0',
        'lot' => 'nullable|numeric|gt:0',
        'spread' => 'nullable|numeric|min:0',
        'direction' => 'required|in:buy,sell',
        'com' => 'nullable|numeric',
        'stop_loss' => 'nullable|numeric|min:0',
        'take_profit' => 'nullable|numeric|min:0',
        'open_at'=> 'nullable',
        ]);

        // ✅ Step 2: Call your service to update the trade
        try {
            $trade = $this->seviceTrade->updateTrade($id, $validated);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => $e->getMessage()
            ], 400);
        }

        // ✅ Step 3: Return a successful response
        return response()->json([
            'status' => true,
            'message' => 'Trade updated successfully.',
            'data' => $trade
        ]);

        
        
        try {
            // Retrieve the Trade model by ID
            $trade = Trade::findOrFail($id);
            $coin = CurrencyPair::whereId($request['coinId'])->first();
            // Update the fields with the request data
            $data = $request->all();
            $res = $this->UpdatecryptoTrade($data, $coin, $trade);
            // Set success message
            $this->setMessage("success");

            // Return a successful API response
            return $this->sendApiResonse();

        } catch (\Exception $e) {
            // Handle exception and return an error response
            return response()->json([
                'message' => 'Update failed',
                'error' => $e->getMessage()
            ], 500);
        }
    }
    
    
    public function UpdatecryptoTrade($data, $coin, $trade)
    {
       // dd($data);
        // $trade = new Trade();
        // if (auth()->id() != $user_id) {
        //     $trade->by_admin = 1;
        // }
        $trade->trade_type = $data['type'];
        $trade->traded_amount = $data['amount'];
        $trade->is_take_profit = isset($data['take_profit'] )?$data['take_profit']:0;
        $trade->take_profit =  isset($data['take_profit'] ) ? $data['take_profit'] : 100.00;
        $trade->is_stop_loss = isset($data['is_stop_loss']) ? $data['is_stop_loss'] : 0;
        $trade->stop_loss = isset($data['stop_loss'] ) ? $data['stop_loss'] : 100.00;
        $trade->currency_pair = $coin->ex_sym;
        $trade->leverage = $coin->leverage;
        $trade->duration = isset($data['duration'])?$data['duration']:null;
        $trade->close_at = isset($data['duration'])?Carbon::now()->addMinutes($data['duration']):null;
        // $trade->opening_price = $data['open_rate'];
        $trade->closing_price = $data['open_rate'];
        $trade->opening_price = $data['opening_price'];
        $trade->leverage = $data['leverage'];
        $trade->overnight = Carbon::createFromFormat('Y-m-d H:i:s', now())->format('Y-m-d');
        $trade->save();
        return $trade;
    }


    public function storeTrade(Request $request)
    {
        
        $data = $validated = $request->validate([
            'user_id'=>'required|string',
            'symbol' => 'required|string',
            'direction' => 'required|in:buy,sell',
            'opening_price' => 'required|numeric',
            'lot' => 'required|numeric',
            'amount' => 'required|numeric',
            'spread' => 'required|numeric',
            'leverage' => 'required|integer',
            'stop_loss' => 'nullable|boolean',
            'take_profit' => 'nullable|boolean',
            'stop_loss_price' => 'nullable|numeric',
            'take_profit_price' => 'nullable|numeric',
        ]);
         
        $data = $this->seviceTrade->createTrade($data);
        // return response()->json($data);
        $this->setData($data);
        return $this->sendApiResonse();
        
        
        
        
        
        
        
        $total = $request->total;
        $user = user::find($request['user_id']);
        $user->load('userInfo');
        
                    // $bal = $user->userInfo->balance - Trade::whereStatus(0)->whereUserId($user->id)->sum('traded_amount');

        $data = [];
        $coin = CurrencyPair::whereId($request['coinId'])->first();
        $com = ((float)$request['amount'] * (float)$coin->com) / 100;
        $amt = $request['amount'] + $com;
        if($user->userInfo->balance < $amt){
            // $data['status'] = 0;
            // $data['msg'] = 'Insufficient balance, pls fund your account';
            $this->setStatus(422);
            $this->setMessage('Insufficient balance, pls fund your account');
        }
        $rate = $this->getCurRate($coin->sym, $coin->base, $coin->type) ?: $coin->rate;
        if($coin->disabled){
            // $data['status'] = 0;
            // $data['msg'] = 'You cannot trade '.$coin->name .' at the moment';
              $this->setStatus(422);
                $this->setMessage('You cannot trade '.$coin->name .' at the moment');
        }else{
            if($rate != 0.000 ){
                $data = $request->all();
                $data['open_rate'] = $rate;
                $res = $this->cryptoTrade($data, $user->id, $coin, $rate);

                $this->tradeMinusBalance($user, $amt, 'Traded amount');

                // $data['status'] = 1;
                // $data['msg'] = 'trade successfully submitted';
                // $data['data'] = $res;
                $this->setStatus(422);
                $this->setMessage('trade successfully submitted');
            }else {
                 $this->setStatus(422);
                $this->setMessage('You cannot trade ' . $coin->sym . ' at the moment');
                // $data['status'] = 0;
                // $data['msg'] = 'You cannot trade '.$coin->name .' at the moment';
            }
        }

        // return response()->json($data);
            $this->setData($data);
            return $this->sendApiResonse();



        // try{
                // if(strtolower($request['type']) == 'buy'){
                    if($total > $user->userInfo->balance){
                        $this->setStatus(422);
                        $this->setMessage("You cannot trade,not have belance");
                        return $this->sendApiResonse();
                    }
                // }

            $data = [];
            $coin = CurrencyPair::whereId($request['coinId'])->first();
            $com = ((float) $request['amount'] * (float) $coin->com) / 100;
            $amt = $request['amount'] + $com;

            $comm = Trade::whereStatus(0)->whereUserId($user->id)->sum('traded_amount');

            $bal = $user->userInfo->balance - Trade::whereStatus(0)->whereUserId($user->id)->sum('traded_amount');

            if ($bal < $amt) {
                $data['status'] = 0;
                $data['msg'] = 'Insufficient balance, pls fund your account';
            } else {

                $rate = $this->getCurRate($coin->sym, $coin->base, $coin->type) ?: $coin->rate;

                if (strtolower($request['type']) == 'buy') {
                    $rate = (($coin->buy_spread * $rate) / 100) + $rate;
                } else {
                    $rate = $rate - (($coin->sell_spread * $rate) / 100);
                }

                // if ($coin->disabled) {
                //     $data['status'] = 0;
                //     $data['msg'] = 'You cannot trade ' . $coin->name . ' at the moment';
                // } else {
                    // if ($rate != 0.000) {
                        $data = $request->all();
                        $data['open_rate'] = $rate;
                        $res = $this->cryptoTrade($data, $user->id, $coin, $rate);
                        // $this->tradeMinusBalance(Auth::user(), $amt, 'Traded amount');

                        $data['status'] = 1;
                        $data['msg'] = 'trade successfully submitted';
                        $data['data'] = $res;
                    // } else {
                    //     $data['status'] = 0;
                    //     $data['msg'] = 'You cannot trade ' . $coin->name . ' at the moment';
                    // }
                // }

                // check if this user has the "open-trade" admin notifications
                // if (in_array('open-trade', auth()->user()->admin_notifications)) {
                //     // notify admin of this open trade
                //     $subject = "Open Trade Notification";
                //     $message = auth()->user()->name . " just opened a trade. Login to your dashboard to view.";
                //     $adminEmail = setting('notification_receiver_email');
                //     if ($adminEmail) {
                //         $this->notifyAdmin($message, $subject, $adminEmail);
                //     }

                //     // notify the manager of this user
                //     if (auth()->user()->manager()->exists()) {
                //         $this->notifyAdmin($message, $subject, auth()->user()->manager->email);
                //     }
                // }

            }


            $this->setMessage("success,to create new trade");
            return $this->sendApiResonse();
        // }catch(Exception $e){

        // }
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
        $trade->traded_amount = $data['open_rate'];
        $trade->is_take_profit = isset($data['take_profit'] )?$data['take_profit']:0;
        $trade->take_profit =  isset($data['take_profit'] ) ? $data['take_profit'] : 0.00;
        $trade->is_stop_loss = isset($data['is_stop_loss']) ? $data['is_stop_loss'] : 0;
        $trade->stop_loss = isset($data['stop_loss'] ) ? $data['stop_loss'] : 0.00;
        $trade->is_pending_order = $data['is_pending_order'] > 0 ? "1" : null;
        $trade->pending_order = $data['is_pending_order'] > 0 ? $data['pending_order'] : '0.00';
        $trade->currency_pair = $coin->ex_sym;
        $trade->leverage = $data['amount'];
        $trade->opening_price = $data['total'];
        $trade->lavarag = $coin->leverage;
        $trade->closing_price = 0;
        $trade->rate = $data['total'];
        $trade->overnight = Carbon::createFromFormat('Y-m-d H:i:s', now())->format('Y-m-d');
        $trade->duration = $data['duration'];
        $trade->save();
        return $trade;
    }

    public function allTrades()
    {
        $idsUser = getUsersIds();
        $trades = Trade::whereIn('user_id',$idsUser)->with('user')->paginate(15);
        $result = [
            'data'         => $this->resposeDataall($trades->items()),
            'current_page' => $trades->currentPage(),
            'from'         => $trades->firstItem(),
            'last_page'    => $trades->lastPage(),
            'links'        => $trades->links(),
            'per_page'     => $trades->perPage(),
            'to'           => $trades->lastItem(),
            'total'        => $trades->total(),
        ];
        $this->setData($result);
        $this->setMessage("success");
        return $this->sendApiResonse();
    }

    public function apiCloseTrade(Request $request)
    {
        $id = $request->id;
        $trade = Position::find($request->id);
        if($trade && $trade->close_at == null){
            $this->seviceTrade->closeTrade($request->id,$request->profit,true);
        }else{
            $this->seviceTrade->ReopenTrade($request->id);
        }
        
        // $data = Trade::find($id);

        // check if this user has the "close-trade" admin notifications
        // if (in_array('close-trade', auth()->user()->admin_notifications)) {
        //     // notify admin of this close trade
        //     $subject = "Close Trade Notification";
        //     $message = auth()->user()->name . " just closed a trade. Login to your dashboard to view.";
        //     $adminEmail = setting('notification_receiver_email');
        //     if ($adminEmail) {
        //         $this->notifyAdmin($message, $subject, $adminEmail);
        //     }

        //     // notify the manager of this user
        //     if (auth()->user()->manager()->exists()) {
        //         $this->notifyAdmin($message, $subject, auth()->user()->manager->email);
        //     }
        // }
        return response()->json([]);
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
        if ($user->userInfo->balance < 0) {
            $user->userInfo->balance = 0;
            $user->save();
        }
        return response()->json($trades);
    }

    public function getBalance()
    {
        return Auth::user()->balance;
    }

    public function updateTradeProfit($id,$coinPrice)
    {
        $trade = Trade::findOrFail($id);
        $rate = optional($trade->currency)->rate;

        $coinPrice = $this->getCurRate($trade->currency->sym, $trade->currency->base,$trade->currency->type);
        if($coinPrice === 0){
            $coinPrice = $rate;
        }
        // $old_coin_value = $trade->amount / $trade->opening_price;
        $trade->closing_price =  $coinPrice;
        $cryptoRate = $coinPrice;
        $trade->close_at = null;
        // $pl = abs(($old_coin_value * $cryptoRate) - $trade->amount);
        $opening_price = $trade->opening_price;
        $qty = $trade->leverage;
        $pl = abs(($coinPrice  - $opening_price) * $qty);
        $trade->profit = $pl;
        
        if($trade->trade_type == 'Buy')
        {
            if($opening_price > $cryptoRate)
            {
                $trade->profit = $pl * (-1);
                $trade->save();
            }
            else if ($opening_price < $cryptoRate) {
                $trade->profit = $pl;
                $trade->save();
            }
        } else if($trade->trade_type == 'Sell')
        {
            if($opening_price > $cryptoRate)
            {
                $trade->profit = $pl * (-1);
                $trade->save();
            }
            else if($opening_price > $cryptoRate)
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
        //AUTOCLOSE
        $time = Carbon::now();
        $end = $trade->close_at;
        if ($trade->autoclose == 1 && $time > $end) {
            $this->updateTrade($trade->id);
        }

    }

    public function updateTrade($id)
    {
        $trade = Trade::findOrFail($id);
        $user = User::findOrFail($trade->user_id);
        $user->load('userInfo');
                $coinPrice = $this->getCurRate($trade->asset->sym, $trade->asset->base, $trade->asset->type);

                if($coinPrice === 0){
                    $coinPrice = $trade->currency->rate;
                }

                $trade->closing_price =  $coinPrice;
                $old_coin_value = $trade->amount / $trade->opening_price;
                $cryptoRate = $coinPrice;
                $trade->close_at = Carbon::now();
                $pl = $trade->profit;
                // $trade->profit = $pl;

        if ($trade->status == 0) {
            if (strtolower($trade->trade_type) == 'buy') {

                if ($trade->opening_price > $trade->closing_price) {

                                        $user->userInfo->balance +=  (int)$pl;
                                        // $user->userInfo->balance += ($trade->traded_amount - $pl);
                                        $user->save();

                    $msg = 'Traded  ' . optional($trade->currency)->name . ' lost';
                                        // $amt = $trade->traded_amount + ($pl);
                    $amt = ($pl);

                    $this->tradeAddBalance($user, $amt, $msg);

                    $this->updateTradeProfit($trade,$coinPrice);
                    $trade->result = 2;
                    $trade->status = 1;
                    $trade->save();
                    return 2;
                }else{
                    if ($trade->opening_price < $trade->closing_price) {
                                        $user->userInfo->balance += (int)$pl;
                    $msg = 'Traded  ' . optional($trade->currency)->name . ' won';
                                        // $amt = $trade->traded_amount + ($pl);
                    $amt = ($pl);
                    $this->tradeAddBalance($user, $amt, $msg);
                   $this->updateTradeProfit($trade,$coinPrice);
                    $user->save();
                    $trade->result = 1;
                    $trade->status = 1;
                    $trade->save();
                    return 1;
                } else {
                     $user->userInfo->balance += (int)$pl;
                 $user->save();
                    $msg = 'Traded  ' . optional($trade->currency)->name . ' draw';
                    $amt = $trade->traded_amount;
                    $this->updateTradeProfit($trade,$coinPrice);

                    $trade->result = 3;
                    $trade->status = 1;
                    $trade->save();
                    return 3;
                }
                }
            }

            if (strtolower($trade->trade_type) == 'sell') {

                if ($trade->opening_price < $trade->closing_price) {

                                        $user->userInfo->balance += (int)$pl;
                                        $user->save();

                    $msg = 'Traded  ' . optional($trade->currency)->name . ' lost';
                                        // $amt = $trade->traded_amount + ($pl);
                    $amt = (int)$pl;
                    $this->tradeAddBalance($user, $amt, $msg);
$this->updateTradeProfit($trade,$coinPrice);
$trade->result = 2;
                    $trade->status = 1;
                    $trade->save();
                    return 2;
                } else if ($trade->opening_price > $trade->closing_price) {

                                            $user->userInfo->balance += (int)$pl;
                                            $user->save();
                    $msg = 'Traded  ' . optional($trade->currency)->name . ' won';
                                            // $amt = $trade->traded_amount + ($pl);
                    $amt = (int)$pl;
                    $this->tradeAddBalance($user, $amt, $msg);
                   $this->updateTradeProfit($trade,$coinPrice);
                    $trade->result = 1;
                    $trade->status = 1;
                    $trade->save();
                    return 1;
                } else{
                     $user->userInfo->balance +=  (int)$pl;
                      $user->save();
                    $msg = 'Traded  ' . optional($trade->currency)->name . ' Draw';
                    $amt = (int)$pl;
                    $this->tradeAddBalance($user, $amt, $msg);
                   $this->updateTradeProfit($trade,$coinPrice);
                    $trade->result = 3;
                    $trade->status = 1;
                    $trade->save();
                    return 1;
                }

                if(strtolower($trade->trade_type) != 'sell' && strtolower($trade->trade_type) != 'buy') {
return 3;
                                        $user->userInfo->balance += $trade->traded_amount;
                                        $user->save();

                    $msg = 'Traded  ' . optional($trade->currency)->name . ' draw';
                                        $amt = $trade->traded_amount;
                    $amt = 0;
                    $this->tradeAddBalance($user, $amt, $msg);
$this->updateTradeProfit($trade,$coinPrice);
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

    public function destroy(Request $request)
    {
        $request->validate([
                'id'=>"required|numeric|exists:positions,id"
            ]);
        $trades = Position::find($request->id);
        $trades->delete();
        $this->setMessage("success");
        return $this->sendApiResonse();

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



    public function resposeData($datas){
         $result= [];
        foreach($datas as $data){
           if($data->user != null){
            $coinprice = $this->getCurRate($data->currency->sym,$data->currency->base,$data->currency->type);
           if($coinprice > 0){
                $asset = CurrencyPair::find($data->currency->id);
                $asset->rate =$coinprice;
                $asset->save();
            }
            $this->updateTradeProfit($data->id,( $coinprice > 0 ?$coinprice:$data->currency->rate),$this->truncate_number($data->opening_price,5),$data->leverage);
            $result[]= [
                 "id"=> $data->id,
                 "user_id"=> $data->user->id,
                 "name"=> $data->user->name,
                "email"=> $data->user->email,
                 "trade_type"=> $data->trade_type,
                 "currency_pair"=> $data->currency_pair,
                 "duration"=> $data->duration,
                 "traded_amount"=>$data->amount / ($data->lavarag>0?$data->lavarag:1),
                 "close_at"=> date('Y M d',strtotime($data->close_at)),
                 "com"=> $data->com,
                 "leverage"=>$data->leverage,
                 "opening_price"=> $this->truncate_number($data->opening_price,5),
                 "closing_price"=> $data->closing_price,
                 "result"=> $data->result,
                 "status"=> $data->status,
                 "open_at"=> date('Y M d',strtotime($data->created_at)),
                 "pnl"=> $this->truncate_number($data->profit,5),
                 "current_price"=> $this->truncate_number($coinprice > 0 ?$coinprice:$data->currency->rate,5),
                 "currency"=> $data->currency,
                  "asset"=> $data->currency,
                 "amount"=>$data->amount,
                 "qty"=>$data->leverage,
                 "total"=>$data->rate,
                  "take_profit"=>$data->take_profit,
                 "stop_loss"=>$data->stop_loss,
                 "is_pending_order"=>$data->is_pending_order==0 || $data->is_pending_order==null?false:true,
            ];
           }
        }
        return $result;
    }
     public function resposeDataclose($datas){
        $result= [];

        foreach($datas as $data){
            if($data->user != null){
            $result[]= [
                 "id"=> $data->id,
                 "user_id"=> $data->user->id,
                 "name"=> $data->user->name,
                "email"=> $data->user->email,
                 "trade_type"=> $data->trade_type,
                 "pnl"=> $data->profit,
                 "currency_pair"=> $data->currency_pair,
                 "duration"=> $data->duration,
                 "traded_amount"=> $data->traded_amount,
                 "close_at"=> date('Y M d',strtotime($data->close_at)),
                 "com"=> $data->com,
                 "opening_price"=> $data->opening_price,
                 "closing_price"=> $data->closing_price,
                 "traded_amount"=> $data->traded_amount,
                 "result"=> $data->result,
                 "status"=> $data->status,
                 "open_at"=> date('Y M d',strtotime($data->created_at)),
                 "current_price"=> $this->getCurRate($data->currency->sym,$data->currency->base,$data->currency->type),
                  "currency"=> $data->currency,
                  "asset"=> $data->currency,
                 "amount"=>$data->amount,
                 "qty"=>$data->qty,
            ];
        }
        }
        return $result;
    }

    public function resposeDataall($datas){
          $result= [];
        foreach($datas as $data){
            if($data->user != null){
            $result[]= [
                 "id"=> $data->id,
                 "user_id"=> $data->user->id,
                 "name"=> $data->user->name,
                "email"=> $data->user->email,
                 "trade_type"=> $data->trade_type,
                 "pnl"=> $data->profit,
                 "currency_pair"=> $data->currency_pair,
                 "duration"=> $data->duration,
                 "traded_amount"=> $data->traded_amount,
                 "close_at"=> date('Y M d',strtotime($data->close_at)),
                 "com"=> $data->com,
                 "opening_price"=> $data->opening_price,
                 "closing_price"=> $data->closing_price,
                 "traded_amount"=> $data->traded_amount,
                 "result"=> $this->getStatusResult($data->result),
                 "status"=> $this->getStatus($data->status),
                 "open_at"=> date('Y M d',strtotime($data->created_at)),
                 "current_price"=> $this->getCurRate($data->currency->sym,$data->currency->base,$data->currency->type),
                  "currency"=> $data->currency,
                  "asset"=> $data->currency,
                 "amount"=>$data->amount,
                 "qty"=>$data->qty,
            ];
            }
        }
        return $result;
    }

    public function getStatus($status){
        if($status){
            return "Complete";
        }else{
            return "Running";
        }
    }

     public function getStatusResult($status){
        if($status == 1){
            return "win";
        }else if($status == 2){
            return "lose";
        }else{
            return "lose";
        }
    }


 function truncate_number($number, $decimals = 2) {
    $factor = pow(10, $decimals);
    return floor($number * $factor) / $factor;
}


}
