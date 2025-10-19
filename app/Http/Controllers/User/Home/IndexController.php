<?php

namespace App\Http\Controllers\User\Home;
use App\Http\Controllers\Controller;
use App\Models\CurrencyPair;
use App\Models\Trade;
use App\Models\Position;
use App\Models\Favourite;
use App\Http\Resources\PositionResource;
use App\Services\TradeService;
use App\Http\Resources\User\Assets\AssetsResource;
use Illuminate\Http\Request;

class indexController extends Controller
{
    public $user;
    protected $seviceTrade;
    public function __construct() {
        $this->seviceTrade = new TradeService();
        $this->user = AuthApi();
        if($this->user){
            $this->user->load('userInfo');
        }
        
    }
    
    
    public function indextwo(Request $request){
        $id = [AuthApi()->id];
         $trades = $this->seviceTrade->index([$id]);
        if($request->page == "close"){
            $trades = $this->seviceTrade->indexClose([$id]);
        }
       
        $this->setData(PositionResource::collection($trades));
        $this->setMessage("success");
        return $this->sendApiResonse();
    }
    
    


    public function index(){
         $user_id= auth()->user()->id;
        $trades = Position::where('close_at',null)->whereUserId($user_id)->get();
        $pnl = $this->calcPnl($trades);
        $total_trades = Position::where('close_at',null)->whereUserId($user_id)->sum('trade_amount');
        $com = Trade::whereStatus(0)->whereUserId($user_id)->sum('paid_com');
        $profit = $pnl;
        $bal = $this->user->userInfo->balance;
        $equity = ($profit) + $bal;
        $total_deposit = $this->user->userInfo->awaiting_deposit;
        $data = [
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
        ];

        $this->setData($data);
        $this->setMessage("success");
        return $this->sendApiResonse();
    }


    public function favourite(){
        $currency_pair_id = Favourite::where('user_id',$this->user->id)->pluck('asset_id');
        $currency_pairs = CurrencyPair::whereIn('id',$currency_pair_id)->limit(4)->get();
        $this->setData(AssetsResource::make($currency_pairs));
        $this->setMessage("success");
        return $this->sendApiResonse();
    }

    public function pending(){
        $trades = Trade::whereUserId($this->user->id)->where('is_pending_order','<>',null)->whereStatus(0)->latest()->limit(8)->paginate(15);
         $data = [
            'data'         => $this->resposeData($trades->items()),
            'current_page' => $trades->currentPage(),
            'from'         => $trades->firstItem(),
            'last_page'    => $trades->lastPage(),
            'links'        => $trades->links(),
            'per_page'     => $trades->perPage(),
            'to'           => $trades->lastItem(),
            'total'        => $trades->total(),
        ];
        $this->setData($data);
        $this->setMessage("success");
        return $this->sendApiResonse();
    }


    public function close(){
        $trades = Trade::whereUserId($this->user->id)->whereStatus(1)->latest()->limit(8)->paginate(15);
        $data = [
            'data'         => $this->resposeDataclose($trades->items()),
            'current_page' => $trades->currentPage(),
            'from'         => $trades->firstItem(),
            'last_page'    => $trades->lastPage(),
            'links'        => $trades->links(),
            'per_page'     => $trades->perPage(),
            'to'           => $trades->lastItem(),
            'total'        => $trades->total(),
        ];
        $this->setData($data);
        $this->setMessage("success");
        return $this->sendApiResonse();
    }

    public function open(){

       $trades = Trade::whereUserId($this->user->id)->where('is_pending_order','=',NULL)->whereStatus(0)->latest()->limit(8)->paginate(15);
       $data = [
            'data'         => $this->resposeData($trades->items()),
            'current_page' => $trades->currentPage(),
            'from'         => $trades->firstItem(),
            'last_page'    => $trades->lastPage(),
            'links'        => $trades->links(),
            'per_page'     => $trades->perPage(),
            'to'           => $trades->lastItem(),
            'total'        => $trades->total(),
        ];
        $this->setData($data);
        $this->setMessage("success");
        return $this->sendApiResonse();
    }


    public function resposeData($datas){
        $result= [];
        foreach($datas as $data){
            if($data->currency){
                
            
            $coinprice = $this->getCurRate($data->currency->sym,$data->currency->base,$data->currency->type);
            if($coinprice > 0){
                $asset = CurrencyPair::find($data->currency->id);
                $asset->rate =$coinprice;
                $asset->save();
            }
            $this->updateTradeProfit($data->id,( $coinprice > 0 ?$coinprice:$data->currency->rate),$data->opening_price,$data->leverage);

             $result[]= [
                 "id"=> $data->id,
                 "trade_type"=> $data->trade_type,
                 "currency_pair"=> $data->currency_pair,
                 "duration"=> $data->duration,
                 "traded_amount"=>$data->amount / ($data->lavarag>0?$data->lavarag:1),
                 "close_at"=> date('Y M d H:i:s',strtotime($data->close_at)),
                 "com"=> $data->com,
                 "leverage"=>$data->leverage,
                 "opening_price"=> $data->opening_price,
                 "closing_price"=> $data->closing_price,
                 "result"=> $data->result,
                 "status"=> $data->status,
                 "open_at"=> date('Y M d H:i:s',strtotime($data->created_at)),
                 "pnl"=> $data->profit,
                  "current_price"=> $this->truncate_number($coinprice > 0 ?$coinprice:$data->currency->rate,5),
                  "currency"=> $data->currency,
                   "asset"=> $data->currency,
                 "amount"=>$data->amount,
                 "qty"=>$data->leverage,
                 "total"=>$data->rate,
            ];
            }
          
        }
        return $result;
    }
    
   
    
    public function updateTradeProfit($id,$coinPrice,$opening_price,$qty){
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
        // $pl = abs(($old_coin_value * $cryptoRate) - $trade->amount);
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
            if($opening_price < $cryptoRate)
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
    }

    public function updateTrade($id){
//        $user = Auth::user();
       $trade = Trade::findOrFail($id);
        $user = User::findOrFail($trade->user_id);

        $trade->close_at = Carbon::now();

        $pl =  $trade->profit;

        if($trade->status === 0) {
            if($trade->trade_type == 'Buy')
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
//                    $user->balance += ($trade->traded_amount + $pl);
                    $msg = 'Traded  '.optional($trade->currency)->name. ' won';
                    $amt = $trade->traded_amount + ($pl);
                    $this->tradeAddBalance($user, $amt, $msg);
                    $user->save();
                    $trade->result = 1;
                    $trade->status = 1;
                    $trade->save();
                    return 1;
                }else{
//                    $user->balance += $trade->traded_amount;
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
            else if($trade->trade_type == 'Sell')
            {
                if($trade->opening_price < $trade->closing_price)
                {
//                    $user->balance += ($trade->traded_amount - $pl);
//                    $user->save();

                    $msg = 'Traded  '.optional($trade->currency)->name. ' lost';
                    $amt =  ($pl);
                    $this->tradeAddBalance($user, $amt, $msg);

                    $trade->result = 2;
                    $trade->status = 1;
                    $trade->save();
                    return 2;
                }
                else if($trade->opening_price > $trade->closing_price)
                {
//                    $user->balance += ($trade->traded_amount + $pl);
//                    $user->save();
                    $msg = 'Traded  '.optional($trade->currency)->name. ' won';
                    $amt =  ($pl);
                    $this->tradeAddBalance($user, $amt, $msg);
                    $trade->result = 1;
                    $trade->status = 1;
                    $trade->save();
                    return 1;
                }
                else{
//                    $user->balance += $trade->traded_amount;
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
    
     public function resposeDataclose($datas){
        $result= [];
        foreach($datas as $data){

            $result[]= [
                 "id"=> $data->id,
                 "trade_type"=> $data->trade_type,
                 "pnl"=> $data->profit,
                 "currency_pair"=> $data->currency_pair,
                 "duration"=> $data->duration,
                 "traded_amount"=> $data->traded_amount,
                 "close_at"=> date('Y M d H:i:s',strtotime($data->close_at)),
                 "com"=> $data->com,
                 "opening_price"=> $data->opening_price,
                 "closing_price"=> $data->closing_price,
                 "traded_amount"=> $data->traded_amount,
                 "current_price"=> $this->getCurRate($data->currency->sym,$data->currency->base,$data->currency->type),
                 "result"=> $data->result,
                 "status"=> $data->status,
                 "open_at"=> date('Y M d H:i:s',strtotime($data->created_at)),
                  "currency"=> $data->currency,
                  "asset"=> $data->currency,
                 "amount"=>$data->amount,
                 "qty"=>$data->qty,
            ];
        }
        return $result;
    }


 function truncate_number($number, $decimals = 5) {
    return $number;
    $factor = pow(10, $decimals);
    return floor($number * $factor) / $factor;
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
    return $this->truncate_numbert($profit,2);
}

function truncate_numbert($number, $decimals = 2) {
      $factor = pow(10, $decimals);
    $truncated = ($number >= 0)
        ? floor($number * $factor) / $factor
        : ceil($number * $factor) / $factor;

    return number_format($truncated, $decimals, '.', '');
}
}
