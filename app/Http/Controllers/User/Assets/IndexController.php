<?php

namespace App\Http\Controllers\User\Assets;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\Controller;
use App\Models\CurrencyPair;
use Illuminate\Http\Request;
use App\Models\Favourite;
use App\Http\Resources\User\Assets\AssetsResource;
use App\Http\Resources\User\Assets\showAssetResource;
use App\Models\AssetType;
use App\Models\Setting;
class IndexController extends Controller
{
    public $user;
    public function __construct()
    {
        $this->user = AuthApi();
    }




    public function curPrice($sym, $base, $type)
    {
        $rate = $this->getCurRate($sym, $base, $type);
        $this->setData(["price"=>$rate]);
        $this->setMessage("success");
        return $this->sendApiResonse();

    }

    public function types(){
        // $types =  [
        //     ['title'=>'favourite','icon'=>"https://demo.fx-hub.net/src/images/market/favourite.svg"],
        //     ['title'=>'crypto','icon'=>'https://demo.fx-hub.net/src/images/market/crypto.svg'],
        //     ['title'=>'stocks','icon'=>'https://demo.fx-hub.net/src/images/market/stocks.svg'],
        //     ['title'=>'forex','icon'=>'https://demo.fx-hub.net/src/images/market/forex.svg'],
        //     ['title'=>'indices','icon'=>'https://demo.fx-hub.net/src/images/market/indices.svg'],
        //     ['title'=>'commodities','icon'=>'https://demo.fx-hub.net/src/images/market/commodities.svg']
        // ];
        
        $types = AssetType::get();
        $data = [
           ['title'=>'favourite','icon'=>"https://demo.fx-hub.net/src/images/market/favourite.svg"]
        ];
        foreach ($types as $type){
            $data[] = [
                'title'=>$type->title,
                'icon'=>asset($type->icon),
            ];
        }
        $this->setData($data);
        $this->setMessage("success");
        return $this->sendApiResonse();
    }

    public function markets()
    {
        $assets = CurrencyPair::select()->limit(3)->get();
        $results = [];

        foreach($assets as $value){
            $base_url = baseUrl();
            $results[] = [
                'id' => $value->id,
                'name' => $value->name,
                'sym' => $value->sym,
                'image' => $value->image,
                'base' => $value->base,
                "sy" => $value->sy,
                "current_price" => $this->truncate_number($this->getCurRate($value->sym, $value->base, $value->type), 4),
                "buy_spread"=> $value->buy_spread.'%',
                "sell_spread"=> $value->sell_spread.'%',
                // "buy_p" => $this->calcsellspreed($value->sell_spread, $value->sym, $value->base, $value->type),
                // "sell_p" => $this->calcbuyspreed($value->buy_spread, $value->sym, $value->base, $value->type),
            ];
        }

        $this->setData($results);  // Now $results is an array of objects
        $this->setMessage("success");
        return $this->sendApiResonse();
    }



    public function index()
    {
        $types = CurrencyPair::select('type')->groupBy('type')->get();
        $this->setData($types);
        $this->setMessage("success");
        return $this->sendApiResonse();
    }
    public function searchByName(Request $request)
{
    
    $query = CurrencyPair::where('name', 'like', '%' . $request->name . '%')
        ->where('type', "$request->type")
        ->orderedForDisplay()
        ->get();

    // Prepare and set response data
    $this->setData(AssetsResource::make($query));
    $this->setMessage("success");

    return $this->sendApiResonse();
}


    public function show($id)
    {
       
         $types = CurrencyPair::find($id);
        $this->setData($this->resposedata($types));
        $this->setMessage("success");
        return $this->sendApiResonse();
    }
    
    public function showfinance($id)
    {
        $value = CurrencyPair::find($id);
         $coinprice = $this->getCurRate($value->sym,$value->base,$value->type);
            if($coinprice > 0){
                // $asset = CurrencyPair::find($value->id);
                $value->rate =$coinprice;
                $value->save();
            }
            
        $types = AssetType::where('title',$value->type)->first();
        $this->setData([
            'name'=>$value->ex_sym,
            'leverage'=>$value->leverage,
            'amount'=>$types->amount,
            "buy_spread"=> $value->buy_spread,
            "sell_spread"=> $value->sell_spread,
            "current_price"=> $this->truncate_number($coinprice > 0 ?$coinprice:$value->rate,4),
            "buy_p"=> (float)$this->calcbuyspreed($value->buy_spread,$coinprice > 0 ?$coinprice:$value->rate),
            "sell_p"=> (float)$this->calcsellspreed($value->sell_spread,$coinprice > 0 ?$coinprice:$value->rate),
        ]);
        $this->setMessage("success");
        return $this->sendApiResonse();
    }
    
    

    public function all(Request $request){
        $currency_pairs = CurrencyPair::select('id','name')->get();
        $this->setData($currency_pairs);
        $this->setMessage("success");
        return $this->sendApiResonse();
    }

    public function getAssets(Request $request){
        if($request->type == 'favourite'){
            return $this->getFavourite();
        }else{
            $dataar = [];
            // if($request->type == 'cfd'){
            //  $dataar = ['indices','commodities','stocks'];   
            // }else if($request->type == 'fxd'){
            //     $dataar = ['forex'];
            // }else{
            //     $dataar = ['crypto'];
            // }
            $currency_pairs = CurrencyPair::where('type', "$request->type")->orderedForDisplay()->get();
            $this->setData(AssetsResource::make($currency_pairs));
            $this->setMessage("success");
            return $this->sendApiResonse();
        }

    }

    public function getAssetsByType(Request $request){
            $currency_pairs = CurrencyPair::where('type', $request->type)->orderedForDisplay()->get();
            $this->setData(AssetsResource::make($currency_pairs));
            $this->setMessage("success");
            return $this->sendApiResonse();
    }

    public function favourite(Request $request) {
        $request->validate([
            'id' => ['required' , 'numeric', 'exists:currency_pairs,id']
        ]);

        try {
            if (Favourite::where('user_id', $this->user->id)->where('asset_id', $request->id)->exists()) {
                Favourite::where('user_id', $this->user->id)->where('asset_id', $request->id)->delete();
           }else {
                Favourite::create([
                    'user_id'=>$this->user->id,
                    'asset_id'=>$request->id
                    ]);
                $watchlist = Favourite::with('currencyPair')->where('user_id', $this->user->id)->orderByDesc('created_at')->limit(5)->get();
            }
            $assets_id = $this->user->favourite()->get()->pluck('asset_id')->toarray();
            return $this->successResponse('Successfully Added!',$assets_id , 201);
        } catch (\Throwable $th) {
            return $this->errorResponse($th->getMessage(), 500);
        }
    }

    public function removeFromfavourite(Request $request) {
        $request->validate([
            'currency_pair_id' => ['required' , 'numeric', 'exists:currency_pairs,id']
        ]);

        try {
            if (!Favourite::where('user_id', auth()->id())->where('asset_id', $request->currency_pair_id)->exists()) {
                return response()->json(['errors' => ['currency_pair_id' => ['This currency pair does not exist for this user.']]], 422);
            }
            Favourite::where('user_id', auth()->id())->where('asset_id', $request->currency_pair_id)->delete();
            $this->setMessage("success");
            return $this->sendApiResonse();
        } catch (\Throwable $th) {
            return $this->errorResponse($th->getMessage(), 500);
        }
    }




    public function getFavourite(){
        $currency_pair_id = Favourite::where('user_id',$this->user->id)->pluck('asset_id');
        $currency_pairs = CurrencyPair::whereIn('id', $currency_pair_id)->orderedForDisplay()->get();
        $this->setData(AssetsResource::make($currency_pairs));
        $this->setMessage("success");
        return $this->sendApiResonse();

    }


    public function getData($assets){
        $data = [];
        foreach($assets as $asset){
            $data[]=[
                'id'=>$asset->id,
                'name'=>$asset->name,
                'image'=>$asset->image,
                'sym'=>$asset->sym,
                'buy_spread'=>$asset->buy_spread,
                'sell_spread'=>$asset->sell_spread,
                "current_price" => number_format($this->getCurRate($asset->sym, $asset->base, $asset->type), 5, '.', ''),
                'buy_p'=>$asset->buy_p,
                'sell_p'=>$asset->sell_p,
            ];
        }
    }




    public function resposedata($request)
    {
        if(!$request){
            $request = CurrencyPair::find(1);
        }
       $value = $request;
       $base_url=baseUrl();
       $statusfavourite = 0;
    //   $this->updatePriceAssets();
       if(AuthApi()){
               if(Favourite::where('user_id', AuthApi()->id)->where('asset_id',$value->id)->exists()){
                   $statusfavourite =1;
               }
           }
           
           
            $coinprice = $this->getCurRate($value->sym,$value->base,$value->type);
            if($coinprice > 0){
                $asset = CurrencyPair::find($value->id);
                $asset->rate =$coinprice;
                $asset->save();
            }
            
        $types = AssetType::where('title',$value->type)->first();
        $results = [
            'id'=>$value->id,
            'value'=>$value->id,
            'name'=>$value->ex_sym,
            'label'=>$value->name,
            'leverage'=>$value->leverage,
            'amount'=>$types->amount,
            'lots'=>1,
            'sym'=>$value->sym,
            'image'=>asset($value->image),
            'base'=>$value->base,
            "cur"=> 'USD',
            'type'=>$value->type,
            "ex_sym"=> $value->sym,
            "com"=> $value->com,
            "rate"=> $value->rate,
            "buy_spread"=> (float)$value->buy_spread,
            "sell_spread"=> (float)$value->sell_spread,
            "open_at"=> $value->open_at,
            "close_at"=> $value->close_at,
            "sy"=> $value->sy,
            "current_price"=>$this->truncate_number($coinprice > 0 ?$coinprice:$value->rate, 4),
            "buy_p"=> $this->calcbuyspreed($value->buy_spread,$coinprice > 0 ?$coinprice:$value->rate),
            "sell_p"=> $this->calcsellspreed($value->sell_spread,$coinprice > 0 ?$coinprice:$value->rate),
            'is_favourite'=>$statusfavourite,
        ];

        return $results;
    }
    
     public function updatePriceAssets()
    {
       
        // if (Setting::where('key', 'disable_api')->first()->value == '0') {
        //     return false;
        // }
        $assets = CurrencyPair::get();
        foreach ($assets as $asset) {
            // $asset->rate = $this->getCurRate($asset->sym, $asset->base, $asset->type);
            $updatedUrl = preg_replace("~https?://[^/]+~", "", $asset->image);
            $asset->image = $updatedUrl;
            $asset->save();
        }
        return true;
    }

    public function calcsellspreed($sell_spreads,$currentPrice){
       
    if((float) $sell_spreads > 0){
        $sell_spread = floatval(($sell_spreads * $currentPrice) / 100);
        $s_price = floatval($currentPrice) - floatval($sell_spread);
        return $this->truncate_number($s_price, 4);
    }
     return $this->truncate_number($currentPrice, 4);
    }

     public function calcbuyspreed($buy_spreads,$currentPrice){
        if((float) $buy_spreads > 0){
            $buy_spread = floatval(($buy_spreads * $currentPrice) / 100);
            $b_price = floatval($currentPrice) + floatval($buy_spread);
            return $this->truncate_number($b_price, 4);
        }
        return $this->truncate_number($currentPrice, 4);
        
    }
    
    
     function truncate_number($number, $decimals = 2) {
    $factor = pow(10, $decimals);
    return floor($number * $factor) / $factor;
}


}
