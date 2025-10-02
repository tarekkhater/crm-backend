<?php

namespace App\Http\Controllers;

use App\Http\Controllers\admin\CurrencyPairController;
use App\Models\AutoTrader;
use App\Models\CurrencyPair;
use App\Models\Message;
use App\Models\Watchlist;
use App\Models\Deposit;
use App\Models\Identity;
use App\Models\Setting;
use App\Models\Trade;
use App\Models\Transaction;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Session;
use phpDocumentor\Reflection\Types\Null_;
use App\traits\UploadTrait;
use Illuminate\Support\Str;

use Illuminate\Validation\Rule;

class DashboardController extends Controller
{
    use UploadTrait;
    public function market(){
        return view('backend.markets');
    }
    public function news(){
        $theme = $this->theme();
        return view('backend.news',compact('theme'));
    }

    public function gateway($gateway)
    {
        return redirect()->back()->with('failure', "$gateway is not active for your account, contact admin to use this payment method");
    }

    public function dashboard(){


        if (!auth()->user()->hasRole('dev')){
            return redirect()->route('backend.tradestation');
        }
        //
           if(!setting('invest')){
               return  redirect()->route('backend.tradestation');
           }
        //
        //        if(auth()->user()->identity){
        //            if(!auth()->user()->identity->front){
        //                return redirect()->route('backend.profile.upload.id')->with('failure','Upload means of identification');
        //            }
        //        }else{
        //            Identity::create(['user_id' => auth()->id()]);
        //        }

        $trades = Trade::whereUserId(auth()->id())->get();

        $deposits = Deposit::whereUserId(auth()->id())->get();

       $theme = $this->theme();

        return view('backend.dashboard',compact('trades','deposits','theme'));
    }
    public function tradeStation(Request $request, $admin_id = ""){

        $this->checkApi();


        if(!auth()->user()->identity){
            Identity::create(['user_id' => auth()->id()]);
            return redirect()->back()->with('success','Upload means of identification');
//            return redirect()->route('backend.profile.upload.id')->with('failure','Upload means of identification');
        }

        if(setting('kyc_verify')){
            if(auth()->user()->identity){
            if(!auth()->user()->identity->front){
                return redirect()->route('backend.profile.upload.id')->with('failure','Upload means of identification');
            }
        }else{
            Identity::create(['user_id' => auth()->id()]);
            return redirect()->route('backend.profile.upload.id')->with('failure','Upload means of identification');
        }
        }
//
//        if(setting('autotrader') && !auth()->user()->manager_id){
//            return  redirect()->route('backend.autotraders')->with('error','Connect your account with Auto Trader');
//        }


        if ($request->get('cur')) {
            $coin = (int) $request->get('cur');


             $currency = CurrencyPair::where('id',$coin)->first();

            // dd($currency);

             if($currency->disabled == 1) {

               // return redirect()->back()->with('failure','Can not Trade');
                return redirect()->back()->with('failure', 'Can not trad');
              //  return  redirect()->route('backend.dashboard')->with('success', 'Account successfully connected to Auto Trader');
             }

            if(!$currency){
                //dd("hello");
                $currency = CurrencyPair::whereSym('BTC')->first();
            }
        }
        else{
            $currency = CurrencyPair::whereSym('BTC')->first();
        }
        if(Session::get('currency')!=null){
            $currency=Session::get('currency');
        }
        $currencies = CurrencyPair::inRandomOrder()->where('rate','!=',0.0000)->get();




//        $trades = Trade::whereUserId(auth()->id())->get();

        $trades = Trade::where('user_id', auth()->id())->whereStatus(0)->limit(4);

        $deposits = Deposit::whereUserId(auth()->id())->get();

       $theme = $this->theme();

       $types = ['crypto','stocks','forex','indices','commodities'];

       $watchlist = Watchlist::where('user_id', auth()->id())->orderByDesc('created_at')->limit(5)->get();

       //dd($currencies);
        return view('backend.tradestation',compact('trades','types','deposits','theme','currencies','currency', 'watchlist'));

    }

    public function addToWatchlist(Request $request) {
        $request->validate([
            'currency_pair_id' => ['required' , 'numeric', 'exists:currency_pairs,id']
        ]);

        try {
            if (Watchlist::where('user_id', auth()->id())->where('currency_pair_id', $request->currency_pair_id)->exists()) {
                return response()->json(['errors' => ['currency_pair_id' => ['This currency pair is already on your watchlist.']]], 422);
            }

            $request->merge(['user_id' => auth()->user()->id]);
            Watchlist::create($request->all());
            // get the last five watchlist
            $watchlist = Watchlist::with('currencyPair')->where('user_id', auth()->id())->orderByDesc('created_at')->limit(5)->get();
            return $this->successResponse('Successfully Added!', $watchlist, 201);
        } catch (\Throwable $th) {
            return $this->errorResponse($th->getMessage(), 500);
        }
    }

    public function removeFromWatchlist(Request $request) {
        $request->validate([
            'currency_pair_id' => ['required' , 'numeric', 'exists:currency_pairs,id']
        ]);

        try {
            if (!Watchlist::where('user_id', auth()->id())->where('currency_pair_id', $request->currency_pair_id)->exists()) {
                return response()->json(['errors' => ['currency_pair_id' => ['This currency pair does not exist for this user.']]], 422);
            }

            Watchlist::where('user_id', auth()->id())->where('currency_pair_id', $request->currency_pair_id)->delete();
            return $this->successResponse('Successfully removed!');
        } catch (\Throwable $th) {
            return $this->errorResponse($th->getMessage(), 500);
        }
    }
    public function add_new_wach(Request $request) {
        $request->validate([
            'currency_pair_id' => ['required' , 'numeric', 'exists:currency_pairs,id']
        ]);

        try {
            if (Watchlist::where('user_id', auth()->id())->where('currency_pair_id', $request->currency_pair_id)->exists()) {
                Watchlist::where('user_id', auth()->id())->where('currency_pair_id', $request->currency_pair_id)->delete();
           }else {

                $request->merge(['user_id' => auth()->user()->id]);
                Watchlist::create($request->all());
                // get the last five watchlist
                $watchlist = Watchlist::with('currencyPair')->where('user_id', auth()->id())->orderByDesc('created_at')->limit(5)->get();

            }
            $assets_id = auth()->user()->watchlists()->get()->pluck('currency_pair_id')->toarray();
            return $this->successResponse('Successfully Added!',$assets_id , 201);
        } catch (\Throwable $th) {
            return $this->errorResponse($th->getMessage(), 500);
        }
    }


    private function theme(){
        setcookie('themecolor','dark-alt');
        if (isset($_COOKIE['themecolor'])) {
            $newTheme = $_COOKIE['themecolor'];
            if($newTheme == 'dark-alt'){
                return 'dark';
            }else if($newTheme == 'dark'){
                return 'dark';
            }else if($newTheme == 'semi-dark'){
                return 'light';
            }else if($newTheme == 'semi-dark-alt'){
                return 'dark';
            }
        }else {
            return 'dark';
        }
    }
    public function overview(){
//        if(!auth()->user()->btc){
//            return redirect()->route('backend.profile.edit')->with('error','Complete your profile to continue');
//        }
        $deposits = Deposit::whereUserId(auth()->id())->get();
        $trades = Trade::whereUserId(auth()->id())->get();
        $open_trades = Trade::whereUserId(auth()->id())->whereStatus(0)->get();
        $messages = Message::where('user_id',\auth()->user()['id'])->get();
        foreach ($messages as $message){
            $message['date']=date('Y-m-d H:i:a',strtotime($message['created_at']));
        }
        $transactions = Trade::whereUserId(auth()->id())->get();
        $accounts = Trade::whereUserId(auth()->id())->get();
        return view('backend.overview', compact('trades','deposits','transactions','accounts','messages','open_trades'));
    }
    public function profile($id){
        $user = User::findOrFail($id);
        $trades = Trade::whereUserId($id)->whereStatus(1)->get();
//        $open_trades = Trade::whereUserId($id)->whereStatus(0)->get();
        return view('backend.profile', compact('trades','user'));
    }
    public function autotraders(){
        $traders = User::whereRoleIs('autotrader')->withCount('trades')->get();
        return view('backend.autotraders', compact('traders'));
    }

    public function connectAutoTraders($id){
        $trader = User::findOrFail($id);
//        if($trader->fee > auth()->user()->balance){
//            return redirect()->back()->with('fund','Insufficient balance, pls fund account wallet to continue');
//        }

            $user = auth()->user();
//            $user->userInfo->balance = $user->userInfo->balance - (int)$trader->fee;
            $user->manager_id = $id;

            $mani = "Manager : ".ucfirst($trader->first_name);
            $user->account_officer = $mani;
            $user->trader_request = 'accepted';
            $user->msg = '';
            $user->code = Null;
            $user->save();
//            Transaction::create(['user_id' => $user->id, 'amount' => (int)$trader->fee, 'type' => 'debit', 'account_type' => 'balance','note' => 'Account manager payment']);
            return  redirect()->route('backend.dashboard')->with('success', 'Account successfully connected to Auto Trader');

    }

    public function loginLogs(){
        $details = Auth::user()->authentications;
        return view('backend.logins', compact('details'));
    }

    public function pendingDeposits(){
        $deposits = Deposit::whereUserId(auth()->id())->where('status',0)->get();

        return view('backend.deposit.pending', compact('deposits'));
    }

    public function viewDeposit($id){
        $deposit = Deposit::findOrFail($id);
        $deposits = Deposit::whereUserId(auth()->id())->get();
        return view('backend.deposit.view', compact('deposit','deposits'));
    }

    public function editProfile(){
        return view('backend.profile.edit');
    }

    public function myTrades()
    {

        $open_trades = Trade::whereUserId(auth()->id())->whereStatus(0)->get();
        $trades = Trade::whereUserId(auth()->id())->get();
        return view('backend.trades.index', compact('trades','open_trades'));
    }

    public function myFinances()
    {
        return view('backend.finances.index');
    }
    public function security()
    {
        if(auth()->user()->identity){
            if(!auth()->user()->identity->front){
                return redirect()->route('backend.profile.upload.id')->with('failure','Upload means of identification');
            }
        }else{
            Identity::create(['user_id' => auth()->id()]);
        }

        return view('backend.security');
    }

    public function uploadId(){
        return view('backend.uploadId');
    }

    public function storeId(Request $request){
        $request->validate([
            'front' => 'required',
            'back' => 'required',
            'type' => 'required',
            'credit_card_front' => 'required',
            'credit_card_back' => 'required',
            'proof_of_address' => 'required',
            'proof_of_address_type' => 'required',
        ]);

        $data = $request->all();
        $data['user_id'] = auth()->id();
        $data['status'] = 0;

        $id = Identity::whereUserId(auth()->id())->first();

        $front = $request->file('front');
        $back = $request->file('back');
        $credit_card_front = $request->file('credit_card_front');
        $credit_card_back = $request->file('credit_card_back');
        $proof_of_address = $request->file('proof_of_address');

        // Make a image name based on user name and current timestamp
        $f_name = Str::slug(auth()->user()->first_name.'_f_'.time());
        $b_name = Str::slug(auth()->user()->last_name.'_b_'.time());
        $cc_f_name = Str::slug(auth()->user()->last_name.'_cc_f_'.time());
        $cc_b_name = Str::slug(auth()->user()->last_name.'_cc_b_'.time());
        $poa_name = Str::slug(auth()->user()->last_name.'_poa_'.time());

        // Define folder path
        $folder = '/uploads/ids/';
        // Make a file path where image will be stored [ folder path + file name + file extension]
        $f_filePath = $folder . $f_name. '.' . $front->getClientOriginalExtension();
        $b_filePath = $folder . $b_name. '.' . $back->getClientOriginalExtension();
        $cc_f_filePath = $folder . $cc_f_name. '.' . $credit_card_front->getClientOriginalExtension();
        $cc_b_filePath = $folder . $cc_b_name. '.' . $credit_card_back->getClientOriginalExtension();
        $poa_filePath = $folder . $poa_name. '.' . $proof_of_address->getClientOriginalExtension();

        // Upload image
        $this->uploadOne($front, $folder, 'public', $f_name);
        $this->uploadOne($back, $folder, 'public', $b_name);
        $this->uploadOne($credit_card_front, $folder, 'public', $cc_f_name);
        $this->uploadOne($credit_card_back, $folder, 'public', $cc_b_name);
        $this->uploadOne($proof_of_address, $folder, 'public', $poa_name);

        // Set user profile image path in database to filePath
        $data['front'] = $f_filePath;
        $data['back'] = $b_filePath;
        $data['credit_card_front'] = $cc_f_filePath;
        $data['credit_card_back'] = $cc_b_filePath;
        $data['proof_of_address'] = $poa_filePath;

        if(!$id){
            Identity::create($data);
        }else {
            $id->update($data);
        }
        return redirect()->route('backend.id.processing');
    }
}
