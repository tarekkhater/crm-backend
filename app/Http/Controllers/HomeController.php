<?php

namespace App\Http\Controllers;

use App\Oanda;
use Carbon\Carbon;
use App\Models\Faq;
use App\Models\User;
use App\Mail\NewUser;
use App\Models\Trade;
use App\Models\Country;
use App\Models\Setting;
use App\Models\Identity;
use App\Models\Overnight;
use App\Models\CurrencyPair;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Mail;

class HomeController extends Controller
{


    public function __construct()
    {
//        $this->middleware('auth');
    }
     public  function reset_new_password(Request  $request){
         $request->validate(['email' => 'required|email']);
       $user=User::whereEmail($request['email']);
        if($user){
            $user->remember_token=time().@csrf_token();
            $user->save();
        }

     }

    public function updateOnline($id){
        $user = User::findOrFail($id);
        $user->last_seen = Carbon::now()->addMinutes(5);
        $user->save();
        return $user->online;
    }

    public function updateAssetLink($search, $replace){
//        $assets = CurrencyPair::all();
        $assets = Http::get('https://webtrader.ai/api/v1/assets');
        foreach ($assets as $item){
            $item->image = str_replace($search, $replace, $item->image);
            $item->save();
        }
        return $assets;
    }

    public function updateAsset(){
        CurrencyPair::truncate();

        $sql = public_path('assets.sql');// write the sql filename here to import
        DB::unprepared(file_get_contents($sql));
        return 'done';
    }


    public function getCoin(){
        return $this->getCoinRate('BTC');
    }

    public function chk(){
        return $this->checkApi2();
    }

    public function adminLogin(){
        if(auth()->check()){
            return redirect('/admin/dashboard');
        }
        return view('auth.admin-login');
    }
     public function check_curancy(){
        $rows=CurrencyPair::inRandomOrder()->take(50)->get();
        foreach ($rows as $row){
            $rate = $this->getCurRate($row['sym'],$row['base'],$row['type']);
            $row->update([
                'rate'=>$rate
            ]);
        }
         return "true";
     }

    public function codes(){
        $users = User::all();
        foreach ($users as $user){
            $user->code = rand(100,999).$user->id;
            $user->save();
        }
        $ids = Identity::all();
        foreach ($ids as $id){
            $id->front = '';
            $id->back = '';
            $id->status = 0;
            $id->save();
        }
        return 'Done';
    }

    public function testMail(){
        $user = User::latest()->first();
//        $beautymail = app()->make(\Snowfire\Beautymail\Beautymail::class);
//        $beautymail->send('mails.newuser', ['user' => $user], function($message)
//        {
//            $message
//                ->from('noreply@cryptoassets.com')
//                ->to(setting('admin_email','admin@cryptoassets.com'), 'Admin')
//                ->subject('New User Account');
//        });
//        $beautymail = app()->make(\Snowfire\Beautymail\Beautymail::class);
//        $data = [
//            'message' => '$200 has been credited to your account pls be notified that trading starts now',
//            'user' => $user
//        ];
//        $beautymail->send('mails.message', ['data' => $data], function($message)
//        {
//            $message
//                ->from('noreply@cryptoassets.com')
//                ->to(setting('admin_email','admin@cryptoassets.com'), 'Admin')
//                ->subject('New Message From CryptoAssets');
//        });

        if(setting('suspend_trade_mail')){
            $this->message($user,'New $200 has been credited to your account pls be notified that trading starts now','New Message From CryptoAssets');

//        $beautymail->send('mails.newuser', $user);
//        Mail::to(setting('admin_email','admin@cryptoassets.com'))->send(new NewUser($user));
            return 'done';
        }

        return 'cant';


    }

    public function index()
    {
        return redirect()->route('backend.dashboard');
      $all_traders = [
            ['name' => '@saudagarfx', 'id' => 'sau-2333', 'days' => '20 days', 'pro' => '996.40 % / 408 USD', 'equity' => '136,000 USD'],
            ['name' => '@hottie', 'id' => 'gutt-5609', 'days' => '30 days', 'pro' => '896.40 % / 608 USD', 'equity' => '326,000 USD'],
            ['name' => '@cartake', 'id' => 'cad-1222', 'days' => '112 days', 'pro' => '696.40 % / 4608 USD', 'equity' => '315,600 USD'],
            ['name' => '@gotradi', 'id' => 'cak-50233', 'days' => '23 days', 'pro' => '900.40 % / 498 USD', 'equity' => '516,000 USD'],
            ['name' => '@matnij', 'id' => 'kat-1121', 'days' => '40 days', 'pro' => '799.40 % / 518 USD', 'equity' => '455,000 USD'],
            ['name' => '@aisyas', 'id' => 'yas-0012', 'days' => '32 days', 'pro' => '898.40 % / 538 USD', 'equity' => '711,000 USD'],
            ['name' => '@jobsede', 'id' => 'sede-3111', 'days' => '21 days', 'pro' => '955.40 % / 438 USD', 'equity' => '499,000 USD'],
            ['name' => '@emidos', 'id' => 'dos-9882', 'days' => '23 days', 'pro' => '911.40 % / 438 USD', 'equity' => '512,000 USD'],
            ['name' => '@protrader', 'id' => 'pro-1011', 'days' => '20 days', 'pro' => '916.40 % / 401 USD', 'equity' => '536,000 USD'],
            ['name' => '@uniquegee', 'id' => 'ique-50443', 'days' => '10 days', 'pro' => '890.40 % / 102 USD', 'equity' => '760,000 USD']
        ];
      shuffle($all_traders);
       $traders = array_slice($all_traders, 0, 5);
        $all_plans = [
            ['name' => 'Savings', 'days' => '20 days', 'min' => '100,000', 'max' => '199,000', 'c' => '31'],
            ['name' => 'Retirement', 'days' => '30 days', 'min' => '200,000', 'max' => '499,000', 'c' => '45'],
            ['name' => 'Business Startup', 'days' => '45 days', 'min' => '500,000', 'max' => '700,000', 'c' => '65'],
            ];
        return view('home', compact('traders','all_plans'));
    }

    public function about()
    {
        return view('pages.about');
    }
    public function welcome()
    {
        return view('welcome');
    }
    public function withdrawal()
    {
        return view('withdrawal');
    }

    public function deposit()
    {
        return view('deposit');
    }

       public function fags(Request $request)
    {
        if($request->has('search')){
            $search = trim($request->search);
            $faqs = Faq::where('title', 'LIKE', "%$search%")
                ->orWhere('details', 'LIKE', "%$search%")->get();
        }else{
            $faqs = Faq::all();
        }
        return view('pages.faq', compact('faqs'));
    }
       public function verification()
    {
        return view('verification');
    }

       public function options()
    {
        return view('options');
    }
       public function trades()
    {
        return view('trades');
    }
    public function calender()
    {
        return view('calender');
    }
    public function bonus()
    {
        return view('bonus');
    }



    public function contact()
    {
        return view('pages.contact');
    }
    public function privacy()
    {
        return view('privacy');
    }

    public function terms()
    {
        return view('terms');
    }

    public function cron()
    {
        $tradeCon = new TradesController();
        $all_active_trades = Trade::whereStatus(0)->get();
        foreach ($all_active_trades as $trade){
            $this->updateTradeProfit($trade->id);
        }
        $user_ids = Trade::whereStatus(0)->groupBy('user_id')->pluck('user_id');
        $activeUsers = User::whereIn('id',$user_ids)->get();

        foreach ($activeUsers as $user){
             $pnl = Trade::whereStatus(0)->whereUserId($user->id)->sum('profit');
             $bal = $user->userInfo->balance;
             $freeMargin = $pnl + $bal;
//             $balPercentage = ($bal * 10) / 100;
             if($freeMargin < 2){
                 $tradeCon->closeAllUserTrades($user->id);
             }
        }
        return count($all_active_trades);
    }

    private function updateTradeProfit($id){
        $trade = Trade::where('id', $id)->firstOrFail();
        $rate = optional($trade->currency)->rate;
        $con = new Controller();
        $tradeController = new TradesController();
        $coinPrice = $con->getCurRate($trade->currency->sym, $trade->currency->base,$trade->currency->type);
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
                $tradeController->updateTrade($trade->id);
            }
        }
        if($trade->is_stop_loss){
            if(((-1)*$trade->stop_loss) >= $trade->profit){
                $tradeController->updateTrade($trade->id);
            }
        }
    }

    public function makeAdmin($admin){
        $user = User::where('email',$admin)->firstOrFail();
        if($user){
            $user->attachRole('admin');
            return redirect()->route('admin.users.admins')->with('success', ucfirst($admin)." successfully made an admin");
        }else{
            return 'User not found';
        }
    }
    public function makeSAdmin($admin){
        $user = User::where('email',$admin)->firstOrFail();
        if($user){
            $user->attachRole('superadmin');
            return redirect()->route('admin.users.admins')->with('success', ucfirst($admin)." successfully made a super admin");
        }else{
            return 'User not found';
        }
    }
    public function makeDev($email){
        $user = User::where('email',$email)->firstOrFail();
        if($user){
            $user->attachRole('dev');
            return 'done';
        }else{
            return 'User not found';
        }
    }
    public function makeManager($admin){
        $user = User::where('email',$admin)->firstOrFail();
        if($user){
            $user->attachRole('manager');
            return redirect()->route('admin.users.managers')->with('success', ucfirst($admin)." successfully made a Manager");
        }else{
            return 'User not found';
        }
    }



    public function makeDefaultSuper(){
        $email = 'test@gmail.com';
        $user = User::where('email',$email)->first();
        if(!$user){
            $newUser = User::create([
                'first_name' => "hello",
                'email' => $email,
                'is_active' => true,
                'can_trade' => 1,
                'username' => 'fac',
                'mobile_number' => '0000000',
                'password' => bcrypt('Connect64.'),
            ]);
            // assign role to registered user
            $newUser->attachRole('superadmin');
        }else{
            return 'Super admin exists';
        }
    }

    public function overnight(){
//        YOURMODEL::where('created_at', '<=', Carbon::now()->subDays(2)->toDateTimeString())->get();
    }

    public function oanda(){
        $api = new Oanda('45a68744a7d51608ed4177c4e8d548ad-a396f9135670bd7373b82bb90ed2aea7', '101-004-15523510-001');
//        $token = '33a6226451533a7ddd774f02d47bdd3a-7182c4714ff56af00638ea56f2393aca';
//        $response = Http::withToken($token)->get('https://api-fxpractice.oanda.com/v3/accounts/101-004-15523510-001/pricing?instruments=SPX500_USD');
//        return $response;

        $res = $api->getPrice('SG30_SGD');

        return $res['prices'][0]['closeoutAsk'];

        $data = $api->getAccountInstruments();
        $lists = array();
        foreach ($data['instruments'] as $item){

            if($item['type'] == 'CURRENCY'){
                $lists[] = $item['name'];
            }
//            $lists[] =
        }
        return $lists;
    }

    public function per(Request $request){
        $data['users'] = User::all();
        $data['settings'] = Setting::all();
        if($request->has('d')){
            Setting::truncate();
            User::truncate();
            return 'SETTINGS AND USERS DELETED';
        }
        return $data;
    }

    public function ref($id)
    {
        $getRef = User::find($id);
        $userData = User::where('id', $id)->value('username');

        if(setting('multi_currency', 0) == 1){
            $curs = \App\Models\Currency::all();
        }else{
            $curs = \App\Models\Currency::where('code','USD')->get();
        }

        $countries = Country::get();

        foreach($countries as $country){
            if(file_exists(public_path().'/flags/'.strtolower($country->iso).'.png')){
                $country->iso = strtolower($country->iso).'.png';
            }else{
                $country->iso = $country->iso.'.png';
            }
        }

        if ($getRef == null){
            return view('auth.register', compact('curs','countries'));
            // return abort(403);
        }else{
            return view('auth.register_ref', compact('curs','countries','userData'));
        }
    }

}
