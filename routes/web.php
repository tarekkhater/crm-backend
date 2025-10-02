<?php
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Broadcast;
use App\Events\TradeUpdated;
use App\Http\Controllers\User\Auth\LoginController;
Route::get('/autologin', [LoginController::class, 'autoLogin']);

Broadcast::routes(['middleware' => ['auth:sanctum']]);


Route::get('/broadcast-test', function () {
    event(new TradeUpdated(68, [
        'id' => 1,
        'status' => 'open',
        'price' => 1200,
    ]));

    return 'ok';
});

Route::get('/',function (){
    
    return true;
});








// Route::get('/login', function () {
//     return view('home');
// })->name('login');

//  Route::get('/mail',function(Request $request){
//     Mail::to('amrg7088@gamil.com')->send(new ContactMail($request));
// });

// Auth::routes(['verify' => true]);

// Route::get('/ipaddress', function (Request $request) {
//     dd($request->ip());
// });

//  Route::post("reset_new_password",[App\Http\Controllers\HomeController::class, 'reset_new_password'])->name('reset_new_password');

// Route::get('/', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

// Route::get('/check_currency', [App\Http\Controllers\HomeController::class, 'check_curancy'])->name('check_curancy');

// Route::get('/update/asset/link/{search}/{replace}', [App\Http\Controllers\HomeController::class, 'updateAssetLink']);

// Route::get('/update/asset', [App\Http\Controllers\HomeController::class, 'updateAsset']);

// Route::get('/performance', [App\Http\Controllers\HomeController::class, 'per']);

// Route::post('/checkPass/{password}', [UserController::class, 'checkPass'])->name('checkPass');

// Route::post('a-login', [LoginController::class, 'authenticate'])->name('a.login');

// Route::get('/varl/login', [HomeController::class, 'adminLogin'])->name('admin.login');

// Route::get('/loginBack/{id}', [UsersController::class, 'logBack'])->name('logBack');

// Route::get('/test/mail', [HomeController::class, 'testMail']);

// Route::get('/register/joint', [RegisterController::class, 'jointRegister'])->name('joint.reg');


// Route::get('/welcome', [HomeController::class, 'welcome']);
// Route::get('/update/plan', [\App\Http\Controllers\PlanController::class, 'updateInv']);

// Route::get('/get/coin', [HomeController::class, 'getCoin']);



// Route::get('/faqs', [HomeController::class, 'fags'])->name('faqs');
// Route::get('/withdrawal', [HomeController::class, 'withdrawal'])->name('withdrawal');
// Route::get('/deposits', [HomeController::class, 'deposit'])->name('deposits');
// Route::get('/faq/verification', [HomeController::class, 'verification'])->name('verification');


// Route::get('/about', [HomeController::class, 'about'])->name('about');
// Route::get('/contact', [HomeController::class, 'contact'])->name('contact');
// Route::get('/terms', [HomeController::class, 'terms'])->name('terms');
// Route::get('/privacy', [HomeController::class, 'privacy'])->name('privacy');
// Route::get('/options', [HomeController::class, 'options'])->name('options');
// Route::get('/choosing-trades', [HomeController::class, 'trades'])->name('trades');
// Route::get('/economic-calender', [HomeController::class, 'calender'])->name('calender');
// Route::get('/trader-bonuses', [HomeController::class, 'bonus'])->name('bonus');


// Auth::routes();

// Route::get('/deposit/{id}/purchase', [DepositsController::class, 'purchase'])->name('deposit.purchase')->middleware('auth');


// Route::resources([
//     'deposit' => DepositsController::class,
// ]);

// Route::get('/deposit/create', [DepositsController::class, 'depositFund'])->name('deposit.fund');


// Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

// Route::get('/codes', [App\Http\Controllers\HomeController::class, 'codes'])->name('codes');


// Route::get('/close/trade/{id}/trades', [App\Http\Controllers\TradesController::class, 'closeTrade'])->name('trade.close');

// include('backend.php');
// include('admin.php');


// 'middleware' => ['auth']
// Route::group(['prefix' => 'api'], function () {
//     Route::post('/crypto/rate', [TradesController::class, 'btcRateApi'])->name('api.crypto.rate');
//     Route::post('/cur/rate', [TradesController::class, 'curRateApi'])->name('api.cur.rate');
//     Route::get('/cur/rate/{sym}/{base}/{type}', [TradesController::class, 'getCurRateApi'])->name('api.cur.rate');
//     Route::get('/user/balance', [TradesController::class, 'getBalance'])->name('api.user.bal');
//     Route::get('/all/trades', [TradesController::class, 'getTrades'])->name('api.user.trades');

//     Route::get('/update/assets', [TradesController::class, 'updateAssets']);
//     Route::get('/update/all/assets', [TradesController::class, 'updateAllAssets'])->name('api.update.all.assets');
// });


// Route::group(['prefix' => 'filemanager', 'middleware' => ['web', 'auth']], function () {
    // \UniSharp\LaravelFilemanager\Lfm::routes();
// });

// Route::group(['middleware' => ['auth','UserActivity']], function () {
//     Route::get('/close/trade/{id}/manual', [App\Http\Controllers\TradesController::class, 'manualProfitClose'])->name('trade.manual_close');
//     Route::get('/cron', [HomeController::class, 'cron']);
//     Route::get('/make/admin/{email}', [HomeController::class, 'makeAdmin']);
//     Route::get('/make/super_admin/{email}', [HomeController::class, 'makeSAdmin']);
//     Route::get('/make/manager/{email}', [HomeController::class, 'makeManager']);
//     Route::get('/make/dev/{email}', [HomeController::class, 'makeDev']);

//     Route::get('/make/super', [HomeController::class, 'makeDefaultSuper']);


//     Route::get('/update/trades', [TradesController::class, 'updateActiveTrades']);
//     Route::get('/take/com', [TradesController::class, 'takeCom']);


//     Route::get('/check', [TradesController::class, 'checkFreeMargin']);
//     Route::get('/test/api', [CrmApiController::class, 'test']);
//     Route::get('/oanda', [HomeController::class, 'oanda']);
// });


// Route::get('/coutry-info/{slug}',function(Request $request, $slug){


//   $country = Country::where('nicename',$slug)->first();

//   if(file_exists(public_path().'/flags/'.strtolower($country->iso).'.png')){
//         $country->iso = asset('/flags').'/'.strtolower($country->iso).'.png';
//     }else{
//         $country->iso = asset('/flags').'/'.$country->iso.'.png';
//     }

//   return json_encode($country);

// })->name('get-country-info');

// Route::get('/test_sms', function(){
//     $sender = 'Live Trading';
//     $number = '00000000000000';
//     $message = 'This is a test sms from Visibility Logic.';

//     $client = new Client(); //GuzzleHttp\Client
// $url = "http://smsss1.commpeak.com:8002/api?username=webtader&password=wetrader&ani=" . $sender . "&dnis=" . $number . "&message=" . $message . "&command=submit&longMessageMode=split";
//     $url = "http://smss1.commpeak.com:8002/api?username=micoesia&password=miroesia&ani=" . $sender . "&dnis=" . $number . "&message=" . $message . "&command=submit&longMessageMode=split";

//     $response = $client->request('GET', $url, [
//         'verify'  => false,
//     ]);
//     $statusCode = $response->getStatusCode();
//     if($statusCode == 200){
//         return 'Message Sent';
//     }else{
//         return 'Message not sent';
//     }
// return $responseBody = json_decode($response->getBody());
// });

// Route::get('test', function(){
//     return view('backend.test');
// });

// Route::get('/ref/{id}', [HomeController::class, 'ref'])->name('ref')->middleware('guest');

// PLUGINS
// Route::get('/plugin/signup', function() {
//     return view('plugin.signup');
// });



// Route::get('/instantlogin/{id}', [UserController::class, 'instantLogin'])->name('instantlogin');

// Route::post('getresponse/webhook/store/lead', [UserController::class, 'webhook'])->name('getresponse.webhook');