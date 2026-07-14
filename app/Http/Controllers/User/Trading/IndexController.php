<?php

namespace App\Http\Controllers\User\Trading;
use App\Http\Controllers\Controller;
use App\Http\Requests\User\Trading\TradeRequest;
use App\Models\CurrencyPair;
use App\Models\Overnight;
use App\Models\Trade;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\InfoTradeUser;
use App\Services\TradeService;
use App\Models\Position;
use App\Http\Resources\PositionResource;
use App\Models\Deposit;
use App\Events\TradeUpdated;

class indexController extends Controller
{
//'coinId' => 'required|exists:crypto_currencies,id',
    public $user;
     protected $seviceTrade;
    public function __construct() {
        $this->seviceTrade = new TradeService();
        if(AuthApi()){
            $this->user = AuthApi();
        $this->user->load('userInfo');
        }

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
    
    
    public function create(Request $request){
         $data = $validated = $request->validate([
            'symbol' => 'required|string',
            'direction' => 'required|in:buy,sell',
            'opening_price' => 'required|numeric',
            'lot' => 'required|numeric',
            'amount' => 'required|numeric',
            'spread' => 'required|numeric',
            'leverage' => 'required|integer',
            'stop_loss' => 'nullable|numeric|min:0',
            'take_profit' => 'nullable|numeric|min:0',
        ]);
        
         $data['user_id'] = AuthApi()->id;
         
        $data = $this->seviceTrade->createTrade($data);
        return response()->json($data);
    }
    
    public function Methodclosetrade(Request $request)
    {
        if($request->id == 'all'){
            $trades = Position::where('user_id',$this->user->id)->where('close_at',null)->get();
            foreach ($trades as $trade){
                 if($trade){
                   $this->seviceTrade->closeTrade($trade->id);
                }
            }
        }else{
            $request->validate([
            'id' => 'required|numeric|exists:positions,id',
            ]);
            $this->seviceTrade->closeTrade($request->id,$request->profit,true);
        }
        $this->setMessage("success,to close trade");
        return $this->sendApiResonse();
    }

    public function getAllTrades(Request $request)
{
    $data = [];

    if (isset($request->search) && is_array($request->search)) {
        foreach ($request->search as $index => $value) {
            if ($value['key'] == 'open_at' && $value['value'] != null) {
                $data[] = ['open_at', $value['value']];
            } else {
                $data[] = [$value['key'], $value['value']];
            }
        }
    }

    $query = Trade::whereUserId($this->user->id)->where($data);

    if ($request->type === 'open') {
        $query->where('is_pending_order', '0')
              ->orWhereNull('is_pending_order')
              ->where('status', 0);
    } elseif ($request->type === 'close') {
        $query->where('status', 1);
    }

    $trades = $query->paginate(15);

    $result = [
        'data'         => $this->resposeData($trades->items()),
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

    public function close(Request $request){
     $trades = $this->seviceTrade->indexClose([auth()->user()->id]);
     return PositionResource::collection($trades);
    }



    public function storeTrade(TradeRequest $request)
    {
        // $total = $request->total;

        // try{
                // if(strtolower($request['type']) == 'buy'){
            //         if($total > $this->user->userInfo->balance){
            //             $this->setStatus(422);
            //             $this->setMessage("You cannot trade,not have belance");
            //             return $this->sendApiResonse();
            //         }
            //     // }
            //     if($request->time){
            //         $coin = CurrencyPair::whereId($request['coinId'])->first();
            //         $com = ((float) $request['amount'] * (float) $coin->com) / 100;
            //     }
            // $data = [];
            // $coin = CurrencyPair::whereId($request['coinId'])->first();
            // $com = ((float) $request['amount'] * (float) $coin->com) / 100;
            // $amt = $request['amount'];

            // $comm = Trade::whereStatus(0)->whereUserId(Auth::id())->sum('traded_amount');

            // $bal = Auth::user()->userInfo->balance - Trade::whereStatus(0)->whereUserId(Auth::id())->sum('traded_amount');

            // if ($bal < $amt) {
            //      $this->setStatus(422);
            //     $this->setMessage('Insufficient balance, pls fund your account');
            // } else {

            //     // $rate = $this->getCurRate($coin->sym, $coin->base, $coin->type) ?: $coin->rate;
            //     $rate = $total/$amt;
               

            //     if ($coin->disabled) {
            //          $this->setStatus(422);
            //         $this->setMessage('You cannot trade ' . $coin->sym . ' at the moment');
            //     } else {
            //         // if ($rate != 0.000) {
            //             $data = $request->all();
            //             // $data['open_rate'] = $rate;
            //             $res = $this->cryptoTrade($data, Auth::id(), $coin, $rate);
            //             $this->tradeMinusBalance(Auth::user(), $amt, 'Traded amount');

            //             $data['status'] = 1;
            //             $data['msg'] = 'trade successfully submitted';
            //             $data['data'] = $res;
            //             $this->setMessage("success,to create new trade");
            //         // } else {
            //         //     $data['status'] = 0;
            //         //     $data['msg'] = 'You cannot trade ' . $coin->name . ' at the moment';
            //         // }
            //     }

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

            // }

 $data = [];
        $coin = CurrencyPair::whereId($request['coinId'])->first();
        $com = ((float)$request['amount'] * (float)$coin->com) / 100;
        $amt = $request['amount'] + $com;
        if(auth()->user()->balance < $amt){
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
                $res = $this->cryptoTrade($data, Auth::user()->id, $coin, $rate);

                $this->tradeMinusBalance(Auth::user(), $amt, 'Traded amount');

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

        return response()->json($data);
 $this->setData($data);
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
        $trade->traded_amount = $data['price'];
        $trade->is_take_profit = isset($data['take_profit'] )?$data['take_profit']:0;
        $trade->take_profit =  isset($data['take_profit'] ) ? $data['take_profit'] : 0.00;
        $trade->is_stop_loss = isset($data['is_stop_loss']) ? $data['is_stop_loss'] : 0;
        $trade->stop_loss = isset($data['stop_loss'] ) ? $data['stop_loss'] : 0.00;
        $trade->is_pending_order = $data['is_pending_order'] > 0 ? "1" : null;
        $trade->pending_order = $data['is_pending_order'] > 0 ? $data['pending_order'] : '0.00';
        $trade->currency_pair = $coin->ex_sym;
        $trade->leverage = $data['amount'];
        $trade->opening_price = $data['price'];
        $trade->lavarag = $coin->leverage;
        $trade->closing_price = 0;
        $trade->rate = $data['total'];
        $trade->overnight = Carbon::createFromFormat('Y-m-d H:i:s', now())->format('Y-m-d');
        $trade->duration = $data['duration'];
        $trade->save();
        
        
          
        // $trade->close_at = Carbon::now()->addMinutes($data['duration']);
        //  $trade->opening_at = Carbon::now();
        return $trade;
    }

    public function allTrades( Request $request)
    {
        // $trades = Trade::whereUserId(Auth()->id())->get();
        // $this->setMessage("success,to get all trades");
        // $this->setData($trades);
        // return $this->sendApiResonse();

            $data = [];

            if (isset($request->search) && is_array($request->search)) {
                foreach ($request->search as $index => $value) {
                    if ($value['key'] == 'open_at' && $value['value'] != null) {
                        $data[] = ['open_at', $value['value']];
                    } else {
                        $data[] = [$value['key'], $value['value']];
                    }
                }
            }

            $query = Trade::whereUserId(auth()->id())->where($data);

            if ($request->type === 'open') {
                $query->where('is_pending_order', '0')
                      ->orWhereNull('is_pending_order')
                      ->where('status', 0);
            } elseif ($request->type === 'close') {
                $query->where('status', 1);
            }

            $trades = $query->get();

            $this->setData($this->resposeData($trades));
            $this->setMessage("success");
            return $this->sendApiResonse();
        }


    public function apiCloseTrade(Request $request)
    {
        if($request->id == 'all'){
            $trades = Trade::where('user_id',$this->user->id)->where('status','0')->get();
            foreach ($trades as $trade){
                 if($trade){
                   $this->updateTrade($trade->id);
                }
            }
        }else{
            $request->validate([
            'id' => 'required|numeric|exists:trades,id',
        ]);

        $id = $request->id;
        $trade = Trade::where('id',$id)->where('user_id',$this->user->id)->first();
        if(!$trade){
            $this->setStatus(422);
            $this->setMessage("Not Allowed to close trade");
            return $this->sendApiResonse();
        }
        $this->updateTrade($id);
        }
        

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

         $this->setMessage("success,to close trade");
        return $this->sendApiResonse();
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
                                        $this->updateTrade($item->id);
                }
            }
        } else {
            $data['status'] = 0;
        }
        return response()->json($data);
    }

    public function closeTrade($id)
    {
        $trade = Trade::where('id',$id)->where('user_id',$this->user->id)->first();
        if(!$trade){
            $this->setStatus(422);
            $this->setMessage("Not Allowed to close trade");
            return $this->sendApiResonse();
        }
        
        $this->updateTrade($id);

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
        $this->setMessage("success,to close trade");
        return $this->sendApiResonse();
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
        $bal = \App\Services\Users\UserWalletService::mainBalance($this->user->userInfo);
        //margin=  total trades;
        //equity == 0
        // $equity = bal+mr+pnl
        $freeMargin = $profit + $bal;
        $equity = ($profit) + $bal + $total_trades;
        $balPercentage = ($bal * 10) / 100;
        if ($equity < 2) {
            $this->closeAllUserTrades($user->id);
        }
        return true;
    }

    

    
    public function getBalance()
    {
        return \App\Services\Users\UserWalletService::mainBalance($this->user->userInfo);
    }


     public function updateTradeProfit($id){
        $trade = Trade::findOrFail($id);
        // $rate = optional($trade->currency)->rate;
        $coinPrice = $this->getCurRate($trade->currency->sym, $trade->currency->base,$trade->currency->type);
if($coinPrice === 0){
                    $coinPrice = $trade->currency->rate;
                }
         
        // if($coinPrice === 0){
        //     $coinPrice = $rate;
        // }
        // $old_coin_value = $trade->amount / $trade->opening_price;
        // $trade->closing_price =  $coinPrice;
        $cryptoRate = $coinPrice;
        // $trade->close_at = null;
        $pl = abs($coinPrice - $trade->opening_price)*$trade->leverage;
        
        $trade->profit = $pl;
        
        if($trade->trade_type == 'Buy')
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
        } else if($trade->trade_type == 'Sell')
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

    

    public function updateTrade($id)
    {

        $trade = Trade::where('id',$id)->where('user_id',$this->user->id)->first();
        if($trade){
        $user = $this->user;
        $infouser = InfoTradeUser::where('user_id',$user->id)->first();
        $coinPrice = $this->getCurRate($trade->currency->sym, $trade->currency->sym, $trade->currency->type);

                if($coinPrice === 0){
                    $coinPrice = $trade->currency->rate;
                }

                $trade->closing_price =  $coinPrice;
                $old_coin_value = $trade->opening_price/$trade->rate;
                // $cryptoRate = $coinPrice;
                $trade->close_at = Carbon::now();
                $pl = $trade->profit;
                // $trade->profit = $pl;

        if ($trade->status === 0) {
            if ($trade->trade_type == 'Buy') {

                if ($trade->opening_price > $coinPrice) {
                    
                    $msg = 'Traded  ' . optional($trade->currency)->name . ' lost';
                    $amt = $pl;
                    // $amt = ($pl);
                    $this->tradeAddBalance($user, $amt, $msg);
                    $this->updateTradeProfit($id);
                    $trade->result = 2;
                    $trade->status = 1;
                    $trade->save();
                    return 2;
                } else if ($trade->opening_price < $coinPrice) {
                   
                    $msg = 'Traded  ' . optional($trade->currency)->name . ' won';
                                        // $amt = $trade->rate + ($pl);
                    $amt = $pl;
                    $this->tradeAddBalance($user, $amt, $msg);
                    $this->updateTradeProfit($id);
                    $user->save();
                    $trade->result = 1;
                    $trade->status = 1;
                    $trade->save();
                    return 1;
                } else {
                    
                    $msg = 'Traded  ' . optional($trade->currency)->name . ' draw';
                    // $amt = $trade->rate;
                    // $this->tradeAddBalance($user, $amt, $msg);
                    $trade->result = 3;
                    $trade->status = 1;
                    $trade->save();
                    return 3;
                }
            }

            if ($trade->trade_type == 'Sell') {
                if ($trade->opening_price < $coinPrice) {
                    $msg = 'Traded  ' . optional($trade->currency)->name . ' lost';
                    $amt = $pl;
                    $this->tradeAddBalance($user, $amt, $msg);
                    $this->updateTradeProfit($id);
                    $trade->result = 2;
                    $trade->status = 1;
                    $trade->save();
                    return 2;
                } else if ($trade->opening_price > $coinPrice) {
                    $msg = 'Traded  ' . optional($trade->currency)->name . ' won';
                    $amt = $pl;
                    $this->tradeAddBalance($user, $amt, $msg);
                    $this->updateTradeProfit($id);
                    $trade->result = 1;
                    $trade->status = 1;
                    $trade->save();
                    return 1;
                } else{
                    $msg = 'Traded  ' . optional($trade->currency)->name . ' Draw';
                    // $amt = $pl;
                    // $this->tradeAddBalance($user, $amt, $msg);
                    // $this->updateTradeProfit($id);
                    $trade->result = 3;
                    $trade->status = 1;
                    $trade->save();
                    return 1;
                }

                if($trade->trade_type != 'Sell' && $trade->trade_type != 'Buy') {
return "4";
                                        // $infouser->balance += (int)$trade->rate;
                                        // $infouser->save();

                    $msg = 'Traded  ' . optional($trade->currency)->name . ' draw';
                                        $amt = $trade->profit;
                    // $amt = 0;
                    // $this->tradeAddBalance($user, $amt, $msg);
                    $this->updateTradeProfit($id);
                    $trade->result = 3;
                    $trade->status = 1;
                    $trade->save();
                    return 3;
                }
            }
        }
        }
    }

  

   


     public function resposeDataclose($datas){
        $result= [];
        foreach($datas as $data){

            $result[]= [
                 "id"=> $data->id,
                 "trade_type"=> $data->trade_type,
                 "pnl"=> $data->profit,
                 "currency_pair"=> $data->currency_pair,
                 "duration"=> $data->trade_type == "Buy"?0:1,
                 "traded_amount"=> $data->traded_amount,
                 "close_at"=> date('Y M d H:i:s',strtotime($data->close_at)),
                 "com"=> $data->com,
                 "opening_price"=> $data->opening_price,
                 "closing_price"=> $data->closing_price,

                 "result"=> $data->result,
                 "status"=> $data->status,
                 "open_at"=> date('Y M d H:i:s',strtotime($data->created_at)),
                 "current_price"=> $this->getCurRate($data->currency->sym,$data->currency->base,$data->currency->type),
                  "currency"=> $data->currency,
                  "asset"=> $data->currency,
                 "amount"=>$data->amount,
                 "qty"=>$data->qty,
            ];
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

    public function topTraders(){
            $topTraders = Trade::select()
                ->groupBy('user_id')
                ->orderByDesc('profit')
                ->limit(10)
                ->get();
                $topTradersWithDetails = $topTraders->map(function($trade) {
                    $trade->user = User::find($trade->user_id);
                    return $trade;
                });

                // return $topTradersWithDetails;
                foreach($topTraders as $trade){
                    $data[] = [
                        'name'=>$trade->user->surname,
                        'image'=>asset($trade->user->avatar),
                        'price'=>Trade::where('user_id',$trade->user->id)->sum('profit'),
                        'diffrent'=>'3.4%',
                    ];
                }
                return $data;
                return $topTraders;
            // Optionally, you can also load user details


            return $topTradersWithDetails;

    }
    
   
    public function open(Request $request){
        $trades = Position::where('close_at',null)->whereUserId($this->user->id)->get();
        $pnl = $this->calcPnl($trades);
        $total_trades = Position::where('close_at',null)->whereUserId($this->user->id)->sum('trade_amount');
        $profit = $pnl;
        \App\Services\Users\UserWalletService::ensureSynced($this->user->userInfo);
        $bal = \App\Services\Users\UserWalletService::mainBalance($this->user->userInfo);
        $equity = ($profit) + $bal;
        $total_deposit = $this->user->userInfo->awaiting_deposit;
         $trades = $this->seviceTrade->index([auth()->user()->id]);
        $response = PositionResource::collection($trades)->additional([
            "header"=>[
                "balance"=>$this->truncate_numbert($bal,2),
                "equity"=>$this->truncate_numbert($equity,2),
                "pnl"=>$pnl,
                "bonus"=>$this->user->userInfo->bonus,
                "margin"=>$this->truncate_numbert($total_trades,2),
                "free_margin"=>$this->truncate_numbert($bal - $total_trades,2),
                "awaiting_deposit"=>$total_deposit,
                "free_margin_perc"=>$equity != 0 
                        ? $this->truncate_numbert((($bal - $total_trades) / $equity) * 100, 2) 
                        : 0,
            ]
        ]);

        $data = $response->response()->getData(true);
        // broadcast(new TradeUpdated($this->user->id, $data));

        return $response;
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


     
        $trades = Trade::whereUserId($this->user->id)->where($data)->Where('is_pending_order',null)->whereStatus(0)->paginate(15);
        $result = [
            'data'         => $this->resposeData($trades->items()),
            'current_page' => $trades->currentPage(),
            'from'         => $trades->firstItem(),
            'last_page'    => $trades->lastPage(),
            'links'        => $trades->links(),
            'per_page'     => $trades->perPage(),
            'to'           => $trades->lastItem(),
            'total'        => $trades->total(),
        ];
        broadcast(new TradeUpdated($this->user->id, $result));
        $this->setData($result);
        $this->setMessage("success");
        return $this->sendApiResonse();
    }
    
        public function resposeData($datas){
        $result= [];
         $resultpnl= 0;
        foreach($datas as $data){
          $pnl = $this->getCurRate($data->currency->sym,$data->currency->base,$data->currency->type) - ($data->opening_price * $data->traded_amount) - ((($data->opening_price * $data->traded_amount))-$data->rate);
          $data->profit = $pnl;
          $resultpnl +=$pnl;
          $data->save();
            $result[]= [
                 "id"=> $data->id,
                 "trade_type"=> $data->trade_type,
                 "currency_pair"=> $data->currency_pair,
                 "duration"=> $data->trade_type == "Buy"?0:1,
                 "traded_amount"=> $data->traded_amount,
                  "leverage"=> $data->leverage,
                 "close_at"=> date('Y M d H:i:s',strtotime($data->close_at)),
                 "com"=> $data->com,
                 "opening_price"=> $data->opening_price * $data->traded_amount,
                 "closing_price"=> $data->closing_price,
                 "result"=> $data->result,
                 "status"=> $data->status,
                 "open_at"=> date('Y M d H:i:s',strtotime($data->created_at)),
                 "pnl"=> number_format($data->profit,5, '.', ''),
                 "current_price"=> $this->getCurRate($data->currency->sym,$data->currency->base,$data->currency->type) ,
                 "currency"=> $data->currency,
                 "asset"=> $data->currency,
                 "amount"=>$data->trade_amount,
                 "qty"=>$data->qty,
            ];
        }
        $userinfo = InfoTradeUser::where('user_id',$this->user->id)->first();
        $userinfo->pnl = $resultpnl;
        // $userinfo->save();
        return $result;
    }
 
 
 public function calcPnl($trades){
    $profit = 0;
    foreach($trades as $trade){
        $priceDiff = 0;
       if($trade->direction === 'buy'){

                    $priceDiff = (float)$trade->currency->rate - $trade->opening_price;
            }else{
                    $priceDiff = $trade->opening_price - (float)(float)$trade->currency->rate;
            }
    
        $rawProfit = $priceDiff * $trade->lot * $trade->amount;
        
        $spreadCost = $priceDiff > 0 ? ($trade->spread / 100) * $trade->lot * $trade->amount : 0;
        // صافي الربح
        $profit += $rawProfit - $spreadCost + $trade->com;
    }
    return round($profit,2);
}

function truncate_numbert($number, $decimals = 2) {
      $factor = pow(10, $decimals);
    $truncated = ($number >= 0)
        ? floor($number * $factor) / $factor
        : ceil($number * $factor) / $factor;

    return number_format($truncated, $decimals, '.', '');
}




}
