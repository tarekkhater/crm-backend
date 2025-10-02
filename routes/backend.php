<?php

use App\Http\Controllers\AccountController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DashboardPagesController;
use App\Http\Controllers\DepositsController;
use App\Http\Controllers\PlanController;
use App\Http\Controllers\TradesController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\WithdrawalController;
use Illuminate\Support\Facades\Route;
// setting('welcome_mail',false) ? 'verified' : 'web'
Route::group(['middleware' => ['auth','UserActivityweb'], 'prefix' => 'dashboard', 'as' => 'backend.'], function() {
//Route::group(['prefix' => 'dashboard', 'as' => 'backend.'], function() {
    Route::get('/', [DashboardController::class, 'dashboard'])->name('dashboard');
    Route::get('/tradestation', [DashboardController::class, 'tradeStation'])->name('tradestation');
    Route::get('/tradestation/GainAccess/{id}', [DashboardController::class, 'tradeStation'])->name('gainaccess.tradestation');
    Route::post('/addToWatchlist', [DashboardController::class, 'addToWatchlist'])->name('watchlist.store');
    Route::post('/add_new_wach', [DashboardController::class, 'add_new_wach'])->name('add_new_wach.store');
    Route::post('/removeFromWatchlist', [DashboardController::class, 'removeFromWatchlist'])->name('watchlist.destroy');
    Route::get('/markets', [DashboardController::class, 'market'])->name('markets');
    Route::get('/news', [DashboardController::class, 'news'])->name('news');
    Route::get('/notTrade/{id?}',[UserController::class, 'notTrade'])->name('notTrade');

    Route::get('investments', [DashboardPagesController::class, 'investments'])->name('investments');

    Route::get('update/password',  [UserController::class, 'updatePassword'])->name('update_password');
    Route::post('update/password',  [UserController::class, 'updatePass'])->name('update_pass');

    Route::get('/account/overview', [DashboardController::class, 'overview'])->name('account.overview');

    Route::get('/deposit/{id}/proof/upload', [DepositsController::class, 'proof'])->name('deposits.proof');

    Route::get('/deposit/fund', [DepositsController::class, 'depositFund'])->name('deposit.fund');
    Route::get('/deposit/fund/upload', [DepositsController::class, 'depositFundUpload'])->name('deposit.fund.upload');
    Route::get('/deposit/fund/proof', [DepositsController::class, 'depositProof'])->name('deposit.proof');

    Route::get('/deposit/view/{id}', [DashboardController::class, 'viewDeposit'])->name('deposit.view');

    Route::get('/withdrawals', [WithdrawalController::class, 'myWithdrawals'])->name('withdrawals.index');

    Route::get('/withdraw', [WithdrawalController::class, 'withdraw'])->name('withdraw.index');

    Route::get('/withdraw/bonus', [WithdrawalController::class, 'withdrawBonus'])->name('withdraw.bonus');
    Route::get('/bonus/withdrawals', [WithdrawalController::class, 'myBonusWithdrawals'])->name('bonus.withdrawals.index');
    Route::get('/withdrawal/processing/{id}', [WithdrawalController::class, 'processing'])->name('withdrawal.processing');
    Route::post('/withdrawal/processed/{id}', [WithdrawalController::class, 'processed'])->name('withdrawal.processed');
    Route::post('/withdrawal/store', [WithdrawalController::class, 'store'])->name('withdrawals.store');
    Route::post('/bonus/withdrawal/store', [WithdrawalController::class, 'bonusWithdraw'])->name('bonus.withdrawal.store');

    Route::post('/withdrawal/update/{id}', [WithdrawalController::class, 'update'])->name('withdrawal.update');

    Route::get('/btc/withdrawal', [WithdrawalController::class, 'btcWithdrawal'])->name('btc.withdrawal');
    Route::get('/pending/withdrawals', [WithdrawalController::class, 'pendingWithdrawal'])->name('pending.withdrawal');
    Route::get('/verify/withdrawal', [WithdrawalController::class, 'verify'])->name('verify.withdrawal');


    Route::get('/trades', [DashboardController::class, 'myTrades'])->name('trades.index');
    Route::get('/finances', [DashboardController::class, 'myFinances'])->name('finances.index');
    Route::get('/account/security', [DashboardController::class, 'security'])->name('account.security');

    Route::get('/account/upload/id', [DashboardController::class, 'uploadId'])->name('account.upload.id');
    Route::post('/account/upload/id', [DashboardController::class, 'storeId'])->name('id.store');

    Route::get('/profile/edit', [DashboardController::class, 'editProfile'])->name('profile.edit');
    Route::get('/profile/{id}}', [DashboardController::class, 'profile'])->name('profile.view');
    Route::post('/profile/update', [UserController::class, 'updateProfile'])->name('profile.update');
    Route::post('/user/token/generate', [UserController::class, 'generateAccessToken'])->name('user.token.generate');
    Route::get('/id/processing', [UserController::class, 'idProcessing'])->name('id.processing');
    Route::get('/id/proceed', [UserController::class, 'idProceed'])->name('id.proceed');

    Route::get('/profile/upload/id', [UserController::class, 'uploadId'])->name('profile.upload.id');

    Route::get('/user/login/logs', [DashboardController::class, 'loginLogs'])->name('user.login.logins');

    Route::get('/transactions', [DashboardPagesController::class, 'transactions'])->name('transactions');
    Route::get('/pending/deposits', [DashboardController::class, 'pendingDeposits'])->name('pending.deposit');

    Route::get('/{id}/gateway', [DashboardController::class, 'gateway'])->name('gateway');


    Route::post('/deposits/store', [DepositsController::class, 'depositStore'])->name('deposit.store');

    Route::post('/deposit/save', [DepositsController::class, 'dStore'])->name('deposit.save');



    Route::get('/upgrade', [DepositsController::class, 'upgrade'])->name('upgrade');

    //AUTOTRADERS
    Route::get('/autotraders', [DashboardController::class, 'autoTraders'])->name('autotraders');
    Route::get('/connect/{id}', [DashboardController::class, 'connectAutoTraders'])->name('account.connect');



    // DEPOSIT
    Route::resources([
        'deposits' => DepositsController::class,
    ]);

    //PLANS
    Route::get('/plans', [PlanController::class, 'index'])->name('trade.plans');
    Route::get('/investment/plans', [PlanController::class, 'investmentPlan'])->name('plans');
    Route::post('/plan/post', [PlanController::class, 'store'])->name('plan.store');
    Route::get('/plan/setup', [PlanController::class, 'setup'])->name('plans.setup');
    Route::get('/activate/{id}', [PlanController::class, 'activate'])->name('plans.activate');
    Route::post('/plans/{id}/upgrade', [PlanController::class, 'upgrade'])->name('plans.upgrade');

    //WITHDRAWAL
    Route::group(['prefix' => 'withdraw', 'as' => 'withdraw.'], function() {
        Route::get('/select', [WithdrawalController::class, 'select'])->name('select');
        Route::get('/wallet/{type}', [WithdrawalController::class, 'wallet'])->name('wallet');
    });

    //ACCOUNT
    Route::group(['prefix' => 'account', 'as' => 'account.'], function() {
        Route::post('store', [AccountController::class, 'store'])->name('store');
        Route::post('/wire/account/store', [AccountController::class, 'wireAccountStore'])->name('wire.store');
        Route::get('/wallet/{type}', [WithdrawalController::class, 'wallet'])->name('wallet');
    });

    //UPLOAD
    Route::post('post/image', [DashboardPagesController::class, 'postImage'])->name('post.image');


    Route::post('trade/store', [TradesController::class, 'storeTrade'])->name('trade.store');
    Route::post('trade/close', [TradesController::class, 'apiCloseTrade'])->name('api.trade.close');
    Route::post('close/all/trades', [TradesController::class, 'closeAllTrades'])->name('api.close.alltrades');
    Route::get('/api/trades', [TradesController::class, 'allTrades'])->name('api.trades');
    Route::get('/api/trades/check', [TradesController::class, 'checkTrades'])->name('api.trade.check');


    Route::get('/api/update/profit', [TradesController::class, 'loopTrades'])->name('api.trade.update');

    Route::get('/api/mytrades', [TradesController::class, 'myTrades'])->name('api.my.trades');

     Route::get('/api/getMessages', [UserController::class, 'getMessages']);

    Route::get('update/user/status', [UserController::class, 'updateOnline'])->name('update.user.status');

    Route::get('refer', [UserController::class, 'refer'])->name('refer');
    Route::get('referrals', [UserController::class, 'referrals'])->name('referrals');




});

