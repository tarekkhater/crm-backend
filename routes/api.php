<?php


use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Mail\ContactMail;
use App\Mail\testContactMail;
use App\Mail\ContactMailBastBroker;
use Illuminate\Support\Facades\Storage;
use App\Http\Controllers\Integrations\LeadController;
use App\Http\Controllers\Integrations\AffilatorController;
use App\Models\Trade;
use App\Models\Position;
use App\Models\CurrencyPair;
use App\Models\AssetType;
use Pusher\Pusher;
/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

// Route::middleware('auth:api')->get('/user', function (Request $request) {
//     return auth('api')->user();
// });

Route::middleware('auth:apiUser')->post('/broadcasting/auth', function (Request $request) {
    $user = $request->user();

    // if (!$user) {
    //     return response('Unauthorized.', 403);
    // }

    $socketId = $request->input('socket_id');
    $channelName = $request->input('channel_name');

    $pusher = new Pusher(
        config('broadcasting.connections.pusher.key'),
        config('broadcasting.connections.pusher.secret'),
        config('broadcasting.connections.pusher.app_id'),
        [
            'cluster' => config('broadcasting.connections.pusher.options.cluster'),
            'useTLS' => true,
        ]
    );
    $channelName = $request->input('channel_name');
    $socketId = $request->input('socket_id');

    $auth = $pusher->socket_auth($channelName, $socketId);
    return response()->json(json_decode($auth, true));
});

Route::get('/file',function (){
   $filePath = storage_path('/teamplate.xls'); // مسار الملف
    return response()->download($filePath, 'template.csv');
});
Route::post('/best-programing/mail', function (Request $request) {
    Mail::to('Hasanrizk@yahoo.com')->send(new ContactMail($request));
    return response()->json(['message' => 'success'], 200);
});
Route::post('/best-broker/mail', function (Request $request) {
    Mail::to('Hasanrizk@yahoo.com')->send(new ContactMail($request));
    return response()->json(['message' => 'success'], 200);
});


Route::get('/test/mail', function (Request $request) {
    Mail::to('amrg7088@gmail.com')->send(new testContactMail($request));
    Mail::to('amrgamal9949@yahoo.com')->send(new testContactMail($request));
    return response()->json(['message' => 'success'], 200);
});



// Route::group(['middleware' => ['cors'], 'namespace' => 'App\Http\Controllers\admin'], function () {
Route::group(['namespace' => 'App\Http\Controllers\admin'], function () {
    Route::group(['prefix' =>'send/data', 'namespace' => 'landing'],function(){
         Route::post('/', "IndexController@index");
    });
    Route::group(['prefix' => 'Auth', 'namespace' => 'Auth'], function () {
        Route::post('/login', "LoginController@login");
        Route::post('/verification', "VerificationController@index")->middleware('CheckOtpEmailVerifiedAdmin');
        Route::post('/resend', "VerificationController@resend")->middleware('CheckOtpEmailVerifiedAdmin');

        Route::group(['prefix' => 'forget/password'], function () {
            Route::post('/', "ForgetPasswordController@ReciveEmail");
            Route::post('check/code', "ForgetPasswordController@CheckCodeForget")->middleware(['CheckOtpEmailForgetAdmin', 'throttle:6,1']);
            Route::post('change', "ForgetPasswordController@changePassword")->middleware('CheckOtpEmailForgetAdmin');
        });
        Route::get('/check/token', "LoginController@CheckToken");
    });
    //
    Route::group(['prefix' => '/', 'middleware' => ['auth:api','AdminVerified', 'blockedAdmin']], function () {

        Route::group(['prefix' => 'Dashboard', 'namespace' => 'Dashboard'], function () {
            Route::get('/', "IndexController@index");
            Route::get('/show', "IndexController@show");
            Route::get('/role', "IndexController@showrole");

        });
        Route::group(['prefix' => 'integrations', 'namespace' => 'Integrations'], function () {
            Route::get('/affiliators', "IndexController@affiliators"); // GET /api/integrations/affiliators
        });

        Route::group(['prefix' => 'home', 'namespace' => 'Home'], function () {
            Route::get('/', "IndexController@index");
            Route::get('/first-section', "IndexController@firstSecation");
            Route::get('/kyc', "IndexController@kyc");
            Route::get('/FTD', "IndexController@FTD");
            Route::get('/lead/chart', "IndexController@LeadsChart");
            Route::get('/trades/chart', "IndexController@tradesChart");
            Route::get('/withdrawal', "IndexController@withdrawal");
            Route::get('/deposit', "IndexController@deposite");
            Route::get('/active-customers', "IndexController@ActiveCustomer");
            Route::get('/ftd-customers', "IndexController@FTDCustomer");
        });


        Route::group(['prefix' => 'roles', 'namespace' => 'Roles'], function () {
            // Route::resource('/',"RolesController");
            Route::post('/', "RolesController@index");
            Route::get('/all', "RolesController@all");
            Route::get('/show/{id}', "RolesController@show");
            Route::post('/update/{id}', "RolesController@update");
            Route::post('/store', "RolesController@store");
            Route::post('/destroy', "RolesController@destroy");
        });


        Route::group(['prefix' => 'Auth', 'namespace' => 'Auth'], function () {
            Route::post('/login-as', "LoginController@loginAs");
            Route::post('/logout', "LogoutController@logout");
            Route::group(['prefix' => '/forget/password'], function () {
                Route::post('/change', "ForgetPasswordController@changePassword");
            });
        });

        Route::group(['namespace' => 'finance'], function () {
            Route::group(['prefix' => 'Bouns', 'namespace' => 'Bouns'], function () {
                Route::resource('/', "IndexController");
            });
            Route::group(['prefix' => 'Deposits', 'namespace' => 'Deposits'], function () {
                Route::get('/', "IndexController@index");
                Route::post('add', "IndexController@store");
                Route::get('/export', "IndexController@Export");
                Route::post('/update/{id}', "IndexController@update");
                Route::post('/filter', "IndexController@Filter");
                Route::post('/filter/text', "IndexController@FilterText");
                Route::post('/status', "IndexController@statusDeposit");
            });

            Route::group(['prefix' => 'trading/hourse', 'namespace' => 'TradingHourse'], function () {
                Route::resource('/', "IndexController");
            });


            Route::group(['prefix' => 'transactions', 'namespace' => 'transactions'], function () {
                Route::resource('/', "IndexController");
            });
            Route::group(['prefix' => 'withdrawal', 'namespace' => 'withdrawl'], function () {
                Route::get('/', "IndexController@index");
                Route::post('add', "IndexController@store");
                Route::get('/export', "IndexController@Export");
                Route::post('/update/{id}', "IndexController@update");
                Route::post('/filter', "IndexController@Filter");
                Route::post('/filter/text', "IndexController@FilterText");
                Route::post('/status', "IndexController@statuswithdrawal");
            });
        });


        Route::group(['namespace' => 'ActionTab'], function () {
            Route::group(['prefix' => 'kyc', 'namespace' => 'KYC'], function () {
                Route::get('/', "IndexController@index");
                Route::post('/store/image', "IndexController@storeImage");
                Route::post('{id}/replace', "IndexController@replace");
                Route::delete('/delete-document', "IndexController@deleteDocument");
                Route::get('/export', "IndexController@Export");
                Route::put('/Change/Status/{id}', "IndexController@ChangeStatus");
                Route::post('/destroy', "IndexController@destroy");
                Route::post('/filter', "IndexController@Filter");
                Route::post('/filter/text', "IndexController@FilterText");
            });
            Route::group(['prefix' => 'mailing', 'namespace' => 'Mailing'], function () {
                Route::resource('/', "IndexController");
                Route::post('/store', "IndexController@store");
                Route::get('/show/{id}', "IndexController@show");
                Route::get('/export', "IndexController@Export");
                Route::post('/filter/text', "IndexController@Filter");
                Route::post('/filter', "IndexController@Filter");
            });

            Route::group(['prefix' => 'money/manager', 'namespace' => 'Money'], function () {
                Route::resource('/', "IndexController");
            });

            Route::group(['prefix' => 'trading/account', 'namespace' => 'Trading'], function () {
                Route::get('/', "IndexController@index");
                Route::post('add', "IndexController@store");
                Route::get('/export', "IndexController@Export");
                Route::post('/update/{id}', "IndexController@update");
                Route::post('/filter', "IndexController@Filter");
                Route::post('/filter/text', "IndexController@FilterByText");
            });
        });

        Route::group(['prefix' => 'trade', 'namespace' => 'Trading'], function () {
            Route::post('/index/close', "IndexController@close");
            Route::post('/index/open', "IndexController@open");
            Route::post('/index/pending', "IndexController@pending");
            Route::post('/store', "IndexController@storeTrade")->middleware('check.time');
            Route::post('/update/{id}', "IndexController@update");

            Route::post('/delete', "IndexController@destroy");
            Route::post('/close', "IndexController@apiCloseTrade");
            Route::post('close/all', "IndexController@closeAllTrades");
            Route::get('/all', "IndexController@allTrades");
            Route::get('/check', "IndexController@checkTrades");
            Route::get('/update/profit',  "IndexController@loopTrades");
            Route::get('/mytrades', "IndexController@myTrades");

            Route::post('/filter', "IndexController@searchrequest");
            Route::post('/filter/texts', "IndexController@filterText");
        });


        Route::group(['prefix' => 'permission', 'namespace' => 'Permission'], function () {
            Route::resource('/', "PermissionController");
            Route::get('/user', "PermissionController@PermissionsUser");
            Route::get('/agent', "PermissionController@indexagent");
            Route::get('/all/user', "PermissionController@AllPermissionsUser");
        });

        Route::group(['prefix' => 'certificates', 'namespace' => 'Certifications'], function () {
            Route::post('/change/status', "certificationController@ChangeStatusCertificated");
            Route::post('/change/percentage', "certificationController@ChangePercentageCertificated");
        });

        Route::group(['prefix' => 'IB', 'namespace' => 'IB'], function () {
            // Route::resource('/',"IndexController");
            Route::get('/', "IndexController@index");
            Route::get('/all', "IndexController@allindex");
            Route::post('/store', "IndexController@register");
            Route::post('/update/{id}', "IndexController@update");
            Route::post('/register', "IndexController@register");
            Route::get('/show/{id}', "IndexController@show");
            Route::post('/destroy', "IndexController@destroy");
            Route::get('/export', "IndexController@export");
            Route::post('/filter', "IndexController@Filter");
            Route::post('/filter/text', "IndexController@FilterText");
            Route::get('/open/trade/{id}', "IndexController@openTrade");
            Route::get('/close/trade/{id}', "IndexController@CloseTrade");
            Route::get('/customers/{id}', "IndexController@getCustomers");
            Route::get('/agent/conversion/{id}', "IndexController@agentConversion");
            Route::get('/agent/retenation/{id}', "IndexController@agentRetention");
            Route::get('/teamleader/retenation/{id}', "IndexController@teamLeaderRetention");
            Route::get('/teamleader/conversion/{id}', "IndexController@teamLeaderConversion");

            // Route::resource('/',"IndexController");
        });


        Route::group(['prefix' => 'user', 'namespace' => 'Users'], function () {

            Route::group(['prefix' => 'clients', 'namespace' => 'Client'], function () {
                Route::resource('/', "IndexController");
                Route::get('/show/{id}', "IndexController@show");
                Route::get('/all', "IndexController@index");
                Route::post('/destroy', "IndexController@destroy");
                Route::post('/assign/account/manager', "IndexController@assignAccountMananger");
                 Route::post('/assign/account/sources ', "IndexController@assignAccountSources");
                Route::post('/assign/lead/status', "IndexController@assignLeadStatus");
                Route::post('/verification/lead/status', "IndexController@verificationLeadStatus");
                Route::post('/assign/branch', "IndexController@assignBranch");
                Route::post('/mass/mailing', "IndexController@MassMailing");
                Route::post('/broker/notification', "IndexController@BrokerNotification");
                Route::post('/import', "IndexController@import");
                Route::post('/export', "IndexController@ExportLeads");
            });
            // Route::group(['prefix'=>'leads','namespace'=>'Leads'],function(){
            //     Route::resource('/',"IndexController");
            //     Route::get('/show/{id}',"IndexController@show");
            //     Route::post('/destroy',"IndexController@destroy");
            //     Route::post('/Convert',"IndexController@ConvertUSers");
            // });
            //eslam
            Route::group(['prefix' => 'leads', 'namespace' => 'Leads'], function () {
                Route::resource('/', 'IndexController');
                Route::get('/show/{id}', 'IndexController@show');
                Route::post('/destroy', 'IndexController@destroy');
                Route::post('/Convert', 'IndexController@ConvertUSers');
                Route::post('/convert', 'IndexController@ConvertUSers');
                Route::post('/convert/potential', 'IndexController@ConvertUSersTopotential');
                Route::post('/deposit', 'IndexController@DepositClientOrTrading');
                Route::post('/assign-manager', 'IndexController@assignAccountMananger');
                Route::post('/Center/total', 'IndexController@leadCenter');
                Route::post('/Center/total/Dashboard', 'IndexController@leadCenterDashboard');
                Route::get('/Center', 'IndexController@publicLead');
                Route::get('/Potential', 'IndexController@Potential');
            });






            Route::group(['prefix' => 'admins', 'namespace' => 'Admin'], function () {
                // Route::resource('/',"IndexController");
                Route::get('/', "IndexController@index");
                Route::get('/affiliates', "IndexController@indexAffiliates");
                Route::post('/affiliates/{id}/regenerate-token',"IndexController@RegenerateTokenAffiliates");
                Route::post('/store', "IndexController@store");
                Route::post('/destroy', "IndexController@destroy");
                Route::post('/update/{id}', "IndexController@update");
                Route::post('filter/text', "IndexController@FilterByText");
                Route::post('filter', "IndexController@Filter");
            });

            Route::group(['prefix' => 'customers', 'namespace' => 'Customer'], function () {
                Route::resource('/', "IndexController");
                Route::post('/destroy', "IndexController@destroy");
                Route::post('/Convert', "IndexController@ConvertUSers");
                Route::get('/show/{id}', "IndexController@show");
                Route::post('/balance/{id}', "IndexController@balance");
                Route::get('/Deposit/{id}', "IndexController@Deposit");
                Route::get('/Withdrawals/{id}', "IndexController@Withdrawals");
                Route::get('/kyc/{id}', "IndexController@kyc");
                Route::post('/mailing/{id}', "IndexController@mailing");
                Route::get('certificates/{id}', "IndexController@getCertificates");
                Route::post('/notes/{id}', "IndexController@notes");
                Route::get('/transactionwallet/{id}', "IndexController@transactionwallet");


                Route::post('/Active', "IndexController@ActiveCustomer");
                Route::post('/disabled/trade', "IndexController@DisabledTrade");
                 Route::post('/disabled/trade/hourse', "IndexController@DisabledTradeHourse");
                Route::post('/disabled/withdrawal', "IndexController@DisabledWithdrawal");
                Route::get('/open/trades/{id}', "IndexController@openTrade");
                Route::get('/pending/trade/{id}', "IndexController@pendingTrade");
                Route::get('/close/trade/{id}', "IndexController@CloseTrade");
            });

            Route::group(['prefix' => 'account', 'namespace' => 'Account'], function () {
                Route::post('/change/password', "ChangePasswordController@index");
                Route::post('/update/image', "indexController@updateimage");
            });
            Route::group(['prefix' => 'active/customers', 'namespace' => 'Customer'], function () {
                Route::resource('/', "ActiveController");
                Route::get('/all', "ActiveController@all");
                Route::post('/destroy', "ActiveController@destroy");
                Route::post('/Convert', "ActiveController@ConvertUSers");
                Route::get('/getUsers', "ActiveController@getUsers");
            });

            Route::group(['prefix' => 'Archive/customers', 'namespace' => 'Customer'], function () {
                Route::resource('/', "ArchiveController");
                Route::post('/destroy', "ArchiveController@destroy");
                Route::post('/Convert', "ArchiveController@ConvertUSers");
            });


            Route::group(['prefix' => '/', 'namespace' => 'Agents'], function () {

                Route::group(['prefix' => 'Agents', 'namespace' => 'Conversion'], function () {
                    Route::resource('/', "IndexController");
                    Route::get('/show/{id}', "IndexController@show");
                    Route::get('/customers/{id}', "IndexController@getcustomers");
                    Route::get('/lead/center/{id}', "IndexController@getLeadCenter");
                    Route::get('/delete/customer/{id}', "IndexController@getcustomers");
                    Route::get('agents/{id}', 'IndexController@getClints');
                    Route::post('/destroy', "IndexController@destroy");
                    Route::post('/destroy/customer', "IndexController@DeleteCustomer");
                    Route::get('/export/user', "IndexController@ExportLeads");
                    Route::post('/update/profile/image', "IndexController@updateimage");
                });
                Route::group(['prefix' => 'agents/conversion', 'namespace' => 'Conversion'], function () {
                    Route::resource('/', "IndexController");
                    Route::get('/all', "IndexController@all");
                    Route::get('/customers', "IndexController@customers");
                    Route::get('/clients/{id}', 'IndexController@getClints');
                    Route::post('/can/fund', "IndexController@canFund");
                    Route::post('/block', "IndexController@Blocked");
                    Route::post('/Verify', "IndexController@Verify");
                    Route::post('/assign/user', "IndexController@assignUser");
                    Route::post('/assign/users', "IndexController@assignUsers");
                    Route::post('/recored/notice', "IndexController@recoredNotice");
                    Route::get('/export/user', "IndexController@ExportLeads");
                });

                Route::group(['prefix' => 'Agents/Conversion', 'namespace' => 'Conversion'], function () {
                    Route::resource('/', "IndexController");
                    Route::get('/all', "IndexController@all");
                    Route::get('/customers', "IndexController@customers");
                    Route::get('/clients/{id}', 'IndexController@getClints');
                    Route::post('/can/fund', "IndexController@canFund");
                    Route::post('/block', "IndexController@Blocked");
                    Route::post('/Verify', "IndexController@Verify");
                    Route::post('/assign/user', "IndexController@assignUser");
                    Route::post('/assign/users', "IndexController@assignUsers");
                    Route::post('/recored/notice', "IndexController@recoredNotice");
                    Route::get('/export/user', "IndexController@ExportLeads");
                });

                Route::group(['prefix' => 'Agents/retentions', 'namespace' => 'Retentions'], function () {
                    Route::resource('/', "IndexController");
                    Route::get('/all', "IndexController@all");
                    Route::get('/customers', "IndexController@customers");
                    Route::post('/can/fund', "IndexController@canFund");
                    Route::post('/block', "IndexController@Blocked");
                    Route::post('/Verify', "IndexController@Verify");
                    Route::post('/assign/user', "IndexController@assignUser");
                    Route::post('/assign/users', "IndexController@assignUsers");
                    Route::get('/export/user', "IndexController@ExportLeads");
                    Route::post('/recored/notice', "IndexController@recoredNotice");
                });

                Route::group(['prefix' => 'team/leader', 'namespace' => 'TeamLeader'], function () {
                    Route::resource('/', "IndexController");
                    Route::get('/', "IndexController@index");
                    Route::get('/all', "IndexController@all");
                    Route::get('/show/{id}', "IndexController@show");
                    Route::post('/assignAgent', "IndexController@assignAgent");
                    Route::post('/store', "IndexController@store");
                    Route::post('/update/{id}', "IndexController@update");
                    Route::post('/block', "IndexController@Blocked");
                    Route::get('/customers', "IndexController@customers");
                    Route::get('/getClients/{id}', "IndexController@getClints");
                    Route::post('/recordNotice', "IndexController@recoredNotice");
                    Route::post('/record/notice', "IndexController@recoredNoticeAgent");
                    Route::post('/verify', "IndexController@Verify");
                    Route::post('/assignUsers', "IndexController@assignUsers");
                    Route::post('/deleteClient', "IndexController@DeleteClient");
                    Route::post('/destroy', "IndexController@destroy");
                    Route::get('/export/agents', "IndexController@ExportAgents");
                    Route::get('/export/user', "IndexController@ExportLeads");
                    Route::post('filter/text', "IndexController@FilterByText");
                    Route::post('filter', "IndexController@Filter");
                });
                Route::post('add', "IndexController@store");
                Route::post('/update/{id}', "IndexController@updated");
                Route::post('/update/image', "IndexController@updateimage");
                Route::post('/change/psssword', "IndexController@changePsssword");
            });
            Route::post('add', "IndexController@store");
            Route::post('/update/{id}', "IndexController@updated");
            Route::get('/profile', "IndexController@profile");
            Route::post('/transfer/balanace', "IndexController@transferBalanace");
            Route::post('/update/profile/image', "IndexController@updateimage");
            Route::post('/change/password', "IndexController@changePsssword");

            Route::post('notes/record', "IndexController@storeMessage");
            Route::post('/record/notes', "IndexController@storeNotes");
            Route::post('/Desposit/record', "IndexController@storeAbstractDesposit");
            Route::post('/import/user', "IndexController@importLeads");
            Route::get('/export/user', "IndexController@ExportLeads");
            Route::post('/filter/text', 'IndexController@FilterByText');
            Route::post('/filter/date', 'IndexController@FilterByDate');
            Route::get('/{id}', "IndexController@show");

            Route::post('/filter/sort', "IndexController@Filter");
            Route::post('/update/properties', "IndexController@Updateproperties");
        });




        Route::group(['prefix' => 'settings', 'namespace' => 'Settings'], function () {
            Route::group(['prefix' => 'type/user', 'namespace' => 'TypeUser'], function () {
                Route::resource('/', "indexController");
            });

            Route::group(['prefix' => 'assets', 'namespace' => 'Assets'], function () {
                Route::post('/', "IndexController@index");
                Route::get('/types', "IndexController@types");
                Route::get('/show/type', "IndexController@showByTypes");
                Route::get('/show/{id}', "IndexController@show");
                Route::post('/create', "IndexController@store");
                Route::post('/update/asset/{id}', "IndexController@update");
                Route::post('/update/leverage', "IndexController@leverage_update");
                Route::post('/update/sell', "IndexController@sell_update");
                Route::post('/update/price/assets', 'IndexController@updatePriceAssets');
                Route::post('/update/hours', 'IndexController@updatehoursAssets');
                Route::post('/update/buy', "IndexController@buy_update");
                Route::post('/destroy/{id}', 'IndexController@destroy');
            });

            Route::group(['prefix' => 'dev', 'namespace' => 'Dev'], function () {


                Route::post('/update/defualt', "IndexController@updatedefault");
                Route::post('/test/email', "IndexController@TestEmailSmtp");
                Route::post('/update/payment/methods', "IndexController@updatepayment");
                Route::post('/create/crypto/methods', "IndexController@createcryptomethods");
                Route::post('/delete/crypto/methods', "IndexController@deletecrypto");

                Route::post('/update', "IndexController@update");
                // -------------------------------------------------
                Route::post('/update/fees', "FeesController@update"); // Finished
                Route::post('/update/margin', "MarginCallController@update"); // Finished
                Route::post('/update/withdrawals', "WithdrawalController@update"); // Finished
                Route::post('/update/site', "SiteController@update"); // Finished
                Route::post('/update/pages', "PageController@update"); // Finished
                Route::post('/update/custome/payment', "CustomPaymentLinkController@update");
                Route::post('/update/leads', "LeadConfigurationController@update"); // Finished
                // Route::post('/update/smtp', "SmtpController@update"); // Finished
                Route::post('/update/crm', "CrmController@update"); // Finished
                Route::post('/update/modules', "ModuleController@update"); // Finished
                // Route::post('/update/general', "generalController@update");
                Route::post('/update/Mails', "MailsController@update"); // Finished
                Route::post('/update/apis', "ApisController@update"); // Finished
            });



            Route::group(['prefix' => 'sources', 'namespace' => 'Sources'], function () {
                Route::resource('/', "IndexController");
                Route::get('/', 'IndexController@index');
                Route::post('/add', 'IndexController@store');
                Route::get('/show/{id}', 'IndexController@show');
                Route::post('/update/{id}', 'IndexController@update');
                Route::post('/destroy/{id}', 'IndexController@destroy');
            });
            
             Route::group(['prefix' => 'desk', 'namespace' => 'Desk'], function () {
                Route::resource('/', "IndexController");
                Route::get('/', 'IndexController@index');
                Route::post('/add', 'IndexController@store');
                Route::get('/show/{id}', 'IndexController@show');
                Route::post('/update/{id}', 'IndexController@update');
                Route::post('/destroy/{id}', 'IndexController@destroy');
            });

            Route::group(['prefix' => 'status', 'namespace' => 'Status'], function () {
                Route::resource('/', "IndexController");
                Route::get('/', 'IndexController@index');
                Route::post('/add', 'IndexController@store');
                Route::get('/show/{id}', 'IndexController@show');
                Route::post('/update/{id}', 'IndexController@update');
                Route::post('/destroy/{id}', 'IndexController@destroy');
            });
            
             Route::group(['prefix' => 'asset-types', 'namespace' => 'AssetType'], function () {
                Route::resource('/', "IndexController");
                Route::get('/', 'IndexController@index');
                Route::post('/add', 'IndexController@store');
                Route::get('/show/{id}', 'IndexController@show');
                Route::post('/update/{id}', 'IndexController@update');
                Route::post('/destroy/{id}', 'IndexController@destroy');
            });
            
            Route::group(['prefix' => 'campaigns', 'namespace' => 'Campaigns'], function () {
                Route::resource('/', "IndexController");
                Route::get('/', 'IndexController@index');
                Route::post('/add', 'IndexController@store');
                Route::get('/show/{id}', 'IndexController@show');
                Route::post('/edit/{id}', 'IndexController@update');
                Route::post('/destroy/{id}', 'IndexController@destroy');
            });

            Route::group(['prefix' => 'currency', 'namespace' => 'Currency'], function () {
                Route::resource('/', "indexController");
                Route::get('/', 'IndexController@index');
                Route::post('/add', 'IndexController@store');
                Route::post('/edit/{id}', 'IndexController@update');
                Route::post('/destroy/{id}', 'IndexController@destroy');
            });

            Route::group(['prefix' => 'plans', 'namespace' => 'PLans'], function () {
                Route::resource('/', "IndexController");
                Route::post('/add', 'IndexController@store');
                Route::get('/show/{id}', 'IndexController@show');
                Route::post('/edit/{id}', 'IndexController@update');
                Route::post('/destroy/{id}', 'IndexController@destroy');
            });

            Route::group(['prefix' => 'payments', 'namespace' => 'Payments'], function () {
                Route::resource('/', "IndexController");
            });

            Route::group(['prefix' => 'countries', 'namespace' => 'Countries'], function () {
                Route::resource('/', "IndexController");
                Route::get('/all', "IndexController@all");
                Route::get('/', 'IndexController@index');
                Route::post('/add', 'IndexController@store');
                Route::get('/show/{id}', 'IndexController@show');
                Route::post('/edit/{id}', 'IndexController@update');
                Route::post('/destroy/{id}', 'IndexController@destroy');
            });
        });
    });

 Route::group(['prefix' => 'settings', 'namespace' => 'Settings'], function () {
      Route::group(['prefix' => 'dev', 'namespace' => 'Dev'], function () {

           Route::get('/fees', "FeesController");
                Route::get('/site', "SiteController");
                Route::get('/api', "ApisController");
                Route::get('/module', "ModuleController");
                Route::get('/crm', "CrmController");
                Route::get('/smtp', "SmtpController");
                Route::get('/withdrawal', "WithdrawalController");

                Route::get('/page', "PageController");

                Route::get('/margin', "MarginCallController");

                Route::get('/lead', "LeadConfigurationController");

                Route::get('/payment', "CustomPaymentLinkController");

                Route::get('/messages', "IndexController@messages");
                Route::get('/payment/methods', "IndexController@paymentmethods");
                Route::get('/crypto/methods', "IndexController@cryptomethods");
      });

 });
    Route::group(['prefix' => 'settings/plans', 'namespace' => 'Settings\PLans'], function () {
        Route::get('/', 'IndexController@index');
    });
    Route::group(['prefix' => 'countries', 'namespace' => 'Settings\Countries'], function () {
        Route::get('/all', "IndexController@index");
    });
    Route::group(['prefix' => 'currency', 'namespace' => 'Settings\Currency'], function () {

        Route::get('/all', "IndexController@all");
    });
    Route::post('/external/leads', [LeadController::class, 'store']);
    Route::post('/external/ftd', [LeadController::class, 'storeFTD']);

});



Route::get('Account/autologin',[AffilatorController::class,'login']);
Route::post('Partner/AddLead',[AffilatorController::class,'lead']);
Route::post('Partner/GetDeposits',[AffilatorController::class,'affilator']);
Route::post('Partner/GetLeads',[AffilatorController::class,'GetLeads']);


Route::get('/addd', function () {
 $trades = Trade::where('close_at',null)->get();

    foreach ($trades as $trade) {
        // Create a new Position for each trade
        
        $asset = CurrencyPair::where('ex_sym',"$trade->currency_pair")->first();
        if($asset){
            $assetype = AssetType::where('title',"LIKE","$asset->type")->first();
            Position::create([
                'user_id'           => $trade->user_id,
                'symbol'            => $trade->currency_pair,
                'direction'         => $trade->trade_type == "Buy"?"buy":"sell",
                'opening_price'     => $trade->opening_price,
                'lot'               => $assetype->amount/$trade->leverage,
                'amount'            => $trade->leverage,
                'spread'            => $trade->trade_type == "Buy"?$asset->buy_spread:$asset->sell_spread,
                'leverage'          => $trade->lavarag,
            ]);
        }
        
    }

    return response()->json([
        'status' => true,
        'message' => 'Trades transferred successfully.'
    ]);
});
use App\Http\Controllers\admin\CRM\Api\salesDashboard\indexController;

// Route for accessing the index function
Route::get('/sales-dashboard', [indexController::class, 'index']);
