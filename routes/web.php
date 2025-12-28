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

// Hot Affiliates Routes - Only for Super Admin (must be before catch-all)
Route::group(['prefix' => 'admin/hot-affiliates', 'as' => 'admin.hot-affiliates.'], function() {
    Route::get('/login', [\App\Http\Controllers\admin\HotAffiliateController::class, 'login'])->name('login');
    Route::post('/login', [\App\Http\Controllers\admin\HotAffiliateController::class, 'doLogin'])->name('doLogin');
    Route::get('/', [\App\Http\Controllers\admin\HotAffiliateController::class, 'index'])->name('index');
    Route::get('/{id}/edit', [\App\Http\Controllers\admin\HotAffiliateController::class, 'edit'])->name('edit');
    Route::put('/{id}', [\App\Http\Controllers\admin\HotAffiliateController::class, 'update'])->name('update');
    Route::delete('/{id}', [\App\Http\Controllers\admin\HotAffiliateController::class, 'destroy'])->name('destroy');
    Route::post('/logout', [\App\Http\Controllers\admin\HotAffiliateController::class, 'logout'])->name('logout');
});

Route::get('/{any}', function () {
    return view('errors.404');
})->where('any', '.*');