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
    return view('home');
});


Route::get('/api',function (){
    return view('home');
});

Route::get('/{any}', function () {
    return view('errors.404');
})->where('any', '.*');