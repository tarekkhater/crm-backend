<?php

use App\Models\Admin;
use App\Models\CurrencyPair;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
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



Route::get('/permissions',function(){
    //  Permission::where('id','<>',0)->delete();
    // $assets = CurrencyPair::get();
    
    // foreach($assets as $asset){
    //      $text = $asset->image;
    //     $finaltext = str_replace('/storage','',$text);
    //     $asset->image = $finaltext;
    //     $asset->save();
    // }
    // return true;
   
    
    
    $array=[
        [
            'permission'=>
            [
                [
                    'name'=>"View-DashBoard",
                    'display_name'=>"View DashBoard",
                    'path'=>"dashboard",
                ]
            ],
            "guard_name"=>"api",
            "title"=>"DashBoard",
        ],
        [
            'permission'=>
            [
                [
                    'name'=>"View-Deposits",
                    'display_name'=>"View Deposits",
                    'path'=>"deposits",
                ]
            ],
            "guard_name"=>"api",
            "title"=>"Deposits",
        ],
        [
            'permission'=>
            [
                [
                    'name'=>"View-Withdrawals",
                    'display_name'=>"View Withdrawals",
                    'path'=>"withdrawals",
                ]
            ],
            "guard_name"=>"api",
            "title"=>"Withdrawals",
        ],
        [
            'permission'=>
            [
                [
                    'name'=>"View-Identity",
                    'display_name'=>"View Identity",
                    'path'=>"kyc",
                ]
            ],
            "guard_name"=>"api",
            "title"=>"Identity",
        ],
        [
            'permission'=>
            [
                [
                    'name'=>"View-Mailing",
                    'display_name'=>"View Mailing",
                    'path'=>"mailing",
                ]
            ],
            "guard_name"=>"api",
            "title"=>"Mailing",
        ],
        [
            'permission'=>
            [
                [
                    'name'=>"View-Lead",
                    'display_name'=>"View Lead",
                    'path'=>"leads",
                ],
                [
                    'name'=>"View-Lead-center",
                    'display_name'=>"View Lead Center",
                    'path'=>"lead-center",
                ],
                [
                    'name'=>"Show-Lead",
                    'display_name'=>"Show Lead",
                ],
                [
                    'name'=>"Delete-Lead",
                    'display_name'=>"Delete Lead",
                ],
                [
                    'name'=>"Create-Lead",
                    'display_name'=>"Create Lead",
                ],
                [
                    'name'=>"Edit-Lead",
                    'display_name'=>"Edit Lead",
                ],
                [
                    'name'=>"Assign-Manager-Lead",
                    'display_name'=>"Assign Manager Lead",
                ],
                [
                    'name'=>"Convert-Lead",
                    'display_name'=>"Convert Lead",
                ],
                [
                    'name'=>"Create-Note-Lead",
                    'display_name'=>"Create Note Lead",
                ],
                [
                    'name'=>"Create-Mailing-Lead",
                    'display_name'=>"Create Mailing Lead",
                ],
                [
                    'name'=>"Change-Status-Lead",
                    'display_name'=>"Change Status Lead",
                ],
                [
                    'name'=>"Export-Lead",
                    'display_name'=>"Export Lead",
                ],
                [
                    'name'=>"Import-Lead",
                    'display_name'=>"Import Lead",
                ]
            ],
            "guard_name"=>"api",
            "title"=>"Lead",
        ],
        [
            'permission'=>
            [
                [
                    'name'=>"View-Potential-Customers",
                    'display_name'=>"View Customers",
                    'path'=>"Potential/Customer",
                ],
                [
                    'name'=>"Show-Potential-Customer",
                    'display_name'=>"Show Customer",
                     'path'=>"profile/user"
                ],
                [
                    'name'=>"Delete-Potential-Customer",
                    'display_name'=>"Delete Customer",
                ],
                [
                    'name'=>"Create-Potential-Customer",
                    'display_name'=>"Create Customer",
                ],
                [
                    'name'=>"Edit-Potential-Customer",
                    'display_name'=>"Edit Customer",
                ],
                [
                    'name'=>"Assign-Manager-Potential-Customer",
                    'display_name'=>"Assign Manager Customer",
                ],
                [
                    'name'=>"Convert-Potential-Customer",
                    'display_name'=>"Convert Customer",
                ],
                [
                    'name'=>"Create-Note-Potential-Customer",
                    'display_name'=>"Create Note Customer",
                ],
                [
                    'name'=>"Create-Mailing-Potential-Customer",
                    'display_name'=>"Create Mailing  Customer",
                ],
                [
                    'name'=>"Change-Status-Potential-Customer",
                    'display_name'=>"Change Status  Customer",
                ],
                [
                    'name'=>"Export-Potential-Customers",
                    'display_name'=>"Export  Customers",
                ],
                [
                    'name'=>"Import-Potential-Customers",
                    'display_name'=>"Import  Customers",
                ]
            ],
            "guard_name"=>"api",
            "title"=>"potential Customers",
        ],
        [
            'permission'=>
            [
                [
                    'name'=>"View-Active-Customers",
                    'display_name'=>"View Customers",
                    'path'=>"active_customer"
                ],
                [
                    'name'=>"View-Public-Customers",
                    'display_name'=>"View Public Customers",
                    'path'=>"public_retention"
                ],
                [
                    'name'=>"View-FTD-Customers",
                    'display_name'=>"View FTD Customers",
                    'path'=>"FTD_customers"
                ],
                [
                    'name'=>"Show-Active-Customer",
                    'display_name'=>"Show Customer",
                ],
                [
                    'name'=>"Delete-Active-Customer",
                    'display_name'=>"Delete Customer",
                ],
                [
                    'name'=>"Create-Active-Customer",
                    'display_name'=>"Create Customer",
                ],
                [
                    'name'=>"Edit-Active-Customer",
                    'display_name'=>"Edit Customer",
                ],
                [
                    'name'=>"Assign-Manager-Active-Customer",
                    'display_name'=>"Assign Manager Customer",
                ],
                [
                    'name'=>"Convert-Active-Customer",
                    'display_name'=>"Convert Customer",
                ],
                [
                    'name'=>"Create-Note-Active-Customer",
                    'display_name'=>"Create Note Customer",
                ],
                [
                    'name'=>"Create-Mailing-Active-Customer",
                    'display_name'=>"Create Mailing Customer",
                ],
                [
                    'name'=>"Change-Status-Active-Customer",
                    'display_name'=>"Change Status Customer",
                ],
                [
                    'name'=>"Export-Active-Customers",
                    'display_name'=>"Export Customers",
                ],
                [
                    'name'=>"Import-Active-Customers",
                    'display_name'=>"Import Customers",
                ]
            ],
            "guard_name"=>"api",
            "title"=>"Active Customers",
        ],
        [
            'permission'=>
            [
                [
                    'name'=>"View-Archive-Customers",
                    'display_name'=>"View Customers",
                    'path'=>"archive_customers"
                ],
                [
                    'name'=>"Show-Archive-Customer",
                    'display_name'=>"Show Customer",
                ],
                [
                    'name'=>"Delete-Archive-Customer",
                    'display_name'=>"Delete Customer",
                ],
                [
                    'name'=>"Create-Archive-Customer",
                    'display_name'=>"Create Customer",
                ],
                [
                    'name'=>"Edit-Archive-Customer",
                    'display_name'=>"Edit Customer",
                ],
                [
                    'name'=>"Assign-Manager-Archive-Customer",
                    'display_name'=>"Assign Manager Customer",
                ],
                [
                    'name'=>"Convert-Archive-Customer",
                    'display_name'=>"Convert Customer",
                ],
                [
                    'name'=>"Create-Note-Archive-Customer",
                    'display_name'=>"Create Note Customer",
                ],
                [
                    'name'=>"Create-Mailing-Archive-Customer",
                    'display_name'=>"Create Mailing Customer",
                ],
                [
                    'name'=>"Change-Status-Archive-Customer",
                    'display_name'=>"Change Status Customer",
                ],
                [
                    'name'=>"Export-Archive-Customers",
                    'display_name'=>"Export Customers",
                ],
                [
                    'name'=>"Import-Archive-Customers",
                    'display_name'=>"Import Customers",
                ]
            ],
            "guard_name"=>"api",
            "title"=>"Archive Customers",
        ],
        [
            'permission'=>
            [
                [
                    'name'=>"View-Brokers",
                    'display_name'=>"View Brokers",
                     'path'=>"Broker/Account"
                ],
                [
                    'name'=>"Show-Broker",
                    'display_name'=>"Show Broker",
                    
                ],
                [
                    'name'=>"Delete-Broker",
                    'display_name'=>"Delete Broker",
                ],
                [
                    'name'=>"Create-Broker",
                    'display_name'=>"Create Broker",
                ],
                [
                    'name'=>"Edit-Broker",
                    'display_name'=>"Edit Broker",
                ],
                [
                    'name'=>"Change-Status-Broker",
                    'display_name'=>"Change Status Broker",
                ],
                
            ],
            "guard_name"=>"api",
            "title"=>"Broker Account",
        ],
        [
            'permission'=>
            [
                [
                    'name'=>"View-Teamleaders",
                    'display_name'=>"View Teamleaders",
                    'path'=>"conversion/team-leader"
                ],
                [
                    'name'=>"Show-Teamleader",
                    'display_name'=>"Show Teamleader",
                ],
                [
                    'name'=>"Delete-Teamleader",
                    'display_name'=>"Delete Teamleader",
                ],
                [
                    'name'=>"Create-Teamleader",
                    'display_name'=>"Create Teamleader",
                ],
                [
                    'name'=>"Edit-Teamleader",
                    'display_name'=>"Edit Teamleader",
                ],
                [
                    'name'=>"Assign-Broker-Teamleader",
                    'display_name'=>"Assign Broker Teamleader",
                ],
                [
                    'name'=>"Change-Status-Teamleader",
                    'display_name'=>"Change Status Teamleader",
                ],
                
            ],
            "guard_name"=>"api",
            "title"=>"Conversion Teamleader",
        ],
        [
            'permission'=>
            [
                [
                    'name'=>"View-Sales-Agents",
                    'display_name'=>"View Agents",
                    'path'=>"conversion/agents"
                ],
                [
                    'name'=>"Show-Sales-Agent",
                    'display_name'=>"Show Agent",
                ],
                [
                    'name'=>"Delete-Sales-Agent",
                    'display_name'=>"Delete Agent",
                ],
                [
                    'name'=>"Create-Sales-Agent",
                    'display_name'=>"Create Agent",
                ],
                [
                    'name'=>"Edit-Sales-Agent",
                    'display_name'=>"Edit Agent",
                ],
                [
                    'name'=>"Assign-Manager-Sales-Agent",
                    'display_name'=>"Assign Manager Agent",
                ],
                [
                    'name'=>"Change-Status-Sales-Agent",
                    'display_name'=>"Change Status Agent",
                ],
                
            ],
            "guard_name"=>"api",
            "title"=>"Conversion Agent",
        ],
            [
            'permission'=>
            [
                [
                    'name'=>"View-Retenation-Teamleaders",
                    'display_name'=>"View Teamleaders",
                    'path'=>"retention/team-leader"
                ],
                [
                    'name'=>"Show-Retenation-Teamleader",
                    'display_name'=>"Show Teamleader",
                ],
                [
                    'name'=>"Delete-Retenation-Teamleader",
                    'display_name'=>"Delete Teamleader",
                ],
                [
                    'name'=>"Create-Retenation-Teamleader",
                    'display_name'=>"Create Teamleader",
                ],
                [
                    'name'=>"Edit-Retenation-Teamleader",
                    'display_name'=>"Edit Teamleader",
                ],
                [
                    'name'=>"Assign-Broker-Retenation-Teamleader",
                    'display_name'=>"Assign Broker Teamleader",
                ],
                [
                    'name'=>"Change-Status-Retenation-Teamleader",
                    'display_name'=>"Change Status Teamleader",
                ],
                
            ],
            "guard_name"=>"api",
            "title"=>"Retenation Teamleader",
        ],
        [
            'permission'=>
            [
                [
                    'name'=>"View-Retenation-Agents",
                    'display_name'=>"View Agents",
                    'path'=>"retention/agents"
                ],
                [
                    'name'=>"Show-Retenation-Agent",
                    'display_name'=>"Show Agent",
                ],
                [
                    'name'=>"Delete-Retenation-Agent",
                    'display_name'=>"Delete Agent",
                ],
                [
                    'name'=>"Create-Retenation-Agent",
                    'display_name'=>"Create Agent",
                ],
                [
                    'name'=>"Edit-Retenation-Agent",
                    'display_name'=>"Edit Agent",
                ],
                [
                    'name'=>"Assign-Manager-Retenation-Agent",
                    'display_name'=>"Assign Manager Agent",
                ],
                [
                    'name'=>"Change-Status-Retenation-Agent",
                    'display_name'=>"Change Status Agent",
                ],
                
            ],
            "guard_name"=>"api",
            "title"=>"Retenation Agent",
        ],
        [
            'permission'=>
            [
                [
                    'name'=>"View-Admins",
                    'display_name'=>"View Admins",
                    'path'=>"admins"
                    
                ],
                [
                    'name'=>"Show-Admin",
                    'display_name'=>"Show Admin",
                ],
                [
                    'name'=>"Delete-Admin",
                    'display_name'=>"Delete Admin",
                ],
                [
                    'name'=>"Create-Admin",
                    'display_name'=>"Create Admin",
                ],
                [
                    'name'=>"Edit-Admin",
                    'display_name'=>"Edit Admin",
                ],
                
            ],
            "guard_name"=>"api",
            "title"=>"Admins",
        ],
        [
            'permission'=>
            [
                [
                    'name'=>"View-Settings-fees",
                    'display_name'=>"View Settings fees",
                    'path'=>"backendSettings/fees"
                ],
                [
                    'name'=>"View-Settings-site",
                    'display_name'=>"View Settings site",
                    'path'=>"backendSettings/site"
                ],
                [
                    'name'=>"View-Settings-user-withdrawals",
                    'display_name'=>"View Settings userWithdrawals",
                    'path'=>"backendSettings/userWithdrawals"
                ],
                [
                    'name'=>"View-Settings-payment-methods",
                    'display_name'=>"View Settings paymentMethods",
                    'path'=>"backendSettings/paymentMethods"
                ],
                [
                    'name'=>"View-Settings-crypto-payment",
                    'display_name'=>"View Settings cryptoPayment",
                    'path'=>"backendSettings/cryptoPayment"
                ],
                [
                    'name'=>"View-Settings-custom-paymentLink",
                    'display_name'=>"View Settings customPaymentLink",
                    'path'=>"backendSettings/customPaymentLink"
                ],
                [
                    'name'=>"View-Settings-lead-configuration",
                    'display_name'=>"View Settings leadConfiguration",
                    'path'=>"backendSettings/leadConfiguration"
                ],
                [
                    'name'=>"View-Settings-stmp",
                    'display_name'=>"View Settings stmp",
                    'path'=> "backendSettings/stmp"
                ],
                [
                    'name'=>"View-Settings-crm",
                    'display_name'=>"View Settings crm",
                    'path'=> "backendSettings/crm"
                ],
                [
                    'name'=>"View-Settings-modules",
                    'display_name'=>"View Settings modules",
                    'path'=> "backendSettings/modules"
                ],
                [
                    'name'=>"View-Settings-general",
                    'display_name'=>"View Settings general",
                    'path'=> "backendSettings/general"
                ],
                [
                    'name'=>"View-Settings-mail",
                    'display_name'=>"View Settings mail",
                    'path'=> "backendSettings/mail"
                ],
                 [
                    'name'=>"View-Settings-api",
                    'display_name'=>"View Settings apiSettings",
                    'path'=> "backendSettings/apiSettings"
                ],
                 [
                    'name'=>"View-Settings-pages-settings",
                    'display_name'=>"View Settings pagesSettings",
                    'path'=> "backendSettings/pagesSettings"
                ],
                 [
                    'name'=>"View-Settings-margin-call",
                    'display_name'=>"View Settings marginCall",
                    'path'=> "backendSettings/marginCall"
                ],
                [
                    'name'=>"Dafulte-Settings",
                    'display_name'=>"Dafulte Settings",
                ],
                [
                    'name'=>"Edit-Settings",
                    'display_name'=>"Edit Settings",
                ],
                
            ],
            "guard_name"=>"api",
            "title"=>"DEV Setting",
        ],
        [
            'permission'=>
            [
                [
                    'name'=>"View-Assets",
                    'display_name'=>"View Assets",
                    'path'=>"backendSettings/assets"
                ],
                [
                    'name'=>"Show-Asset",
                    'display_name'=>"Show Asset",
                ],
                [
                    'name'=>"Delete-Asset",
                    'display_name'=>"Delete Asset",
                ],
                [
                    'name'=>"Create-Asset",
                    'display_name'=>"Create Asset",
                ],
                [
                    'name'=>"Edit-Asset",
                    'display_name'=>"Edit Asset",
                ],
                [
                    'name'=>"Edit-Lavarage-Asset",
                    'display_name'=>"Edit Lavarage Asset",
                ],
                [
                    'name'=>"Edit-Sell-Asset",
                    'display_name'=>"Edit Sell Asset",
                ],
                [
                    'name'=>"Edit-Buy-Asset",
                    'display_name'=>"Edit Buy Asset",
                ],
                
            ],
            "guard_name"=>"api",
            "title"=>"Assets",
        ],
        [
            'permission'=>
            [
                [
                    'name'=>"View-Roles",
                    'display_name'=>"View Roles",
                    'path'=>"backendSettings/manageRoles"
                ],
                [
                    'name'=>"Delete-Role",
                    'display_name'=>"Delete Role",
                ],
                [
                    'name'=>"Create-Role",
                    'display_name'=>"Create Role",
                ],
                [
                    'name'=>"Edit-Role",
                    'display_name'=>"Edit Role",
                ],
                
            ],
            "guard_name"=>"api",
            "title"=>"Roles",
        ],
        [
            'permission'=>
            [
                [
                    'name'=>"View-Risk",
                    'display_name'=>"View Risk",
                    'path'=>"risk/managment/trades"
                ],
                [
                    'name'=>"Delete-Risk",
                    'display_name'=>"Delete Risk",
                ],
                [
                    'name'=>"Edit-Risk",
                    'display_name'=>"Edit Risk",
                ],
                
            ],
            "guard_name"=>"api",
            "title"=>"Risk Managment",
        ],
    ];
        // $admin = Admin::find(149);
        // $role = Role::find(2);
        //  $permissios = Permission::get();
        // foreach($permissios as $permissio){
        //     $role->givePermissionTo($permissio->name);
        // }
        // $admin->assignRole($role);
       
        // foreach ($array as $rows){
        //     foreach ($rows['permission'] as $row){
        //         Permission::create([
        //             'name'=>$row['name'],
        //             'display_name'=>$row['display_name'],
        //             'description'=>$row['display_name'],
        //             'guard_name'=>$rows['guard_name'],
        //             'title'=>$rows['title'],
        //             'path'=>isset($row['path'])?$row['path']:NULL,
        //         ]);
        //     }
        // } 
});
Route::group(['middleware'=>['check.system.status','cors'],'namespace'=>'App\Http\Controllers\User'],function(){

    Route::group(['prefix'=>'Auth','namespace'=>'Auth'],function(){
        Route::post('/login',"LoginController@login")->middleware(['CheckLead']);
        Route::post('/register',"RegisterController@Register");
        Route::post('/register/google',"RegisterController@RegisterGoogle");
        Route::post('/verification', "VerificationController@index")->middleware(['CheckOtpEmailVerified']);
        Route::post('/resend', "VerificationController@resend")->middleware(['CheckOtpEmailVerified','throttle:6,1']);
        Route::get('/check/token',"LoginController@CheckToken");
        Route::get('/autologin', "LoginController@autoLogin");


        Route::group(['prefix' => 'forget/password'], function() {
            Route::post('/', "ForgetPasswordController@ReciveEmail")->middleware(['CheckLead']);
            Route::post('check/code', "ForgetPasswordController@CheckCodeForget")->middleware(['CheckOtpEmailForget','throttle:6,1']);
            Route::post('change', "ForgetPasswordController@changePassword")->middleware('CheckOtpEmailForget');
        });


    });





        Route::group(['prefix'=>'trade','namespace'=>'Trading'],function(){
            Route::get('/top/traders', "IndexController@topTraders");
        });

         Route::group(['namespace'=>'ActionTab'],function(){

            Route::group(['prefix'=>'mailing','namespace'=>'Mailing'],function(){
                Route::resource('/',"IndexController");
            });
        });

    Route::group(['prefix'=>'/','middleware'=>['auth:apiUser,api','UserVerified','blockedUser']],function(){


        Route::group(['prefix'=>'assets','namespace'=>'Assets'],function(){
            Route::get('/', "IndexController@getAssets");
            Route::get("/type","IndexController@getAssetsByType");
            Route::get('/types', "IndexController@types");
            Route::post('/search', "IndexController@searchByName");
            Route::get('/markets', "IndexController@markets");
            Route::get('/show/{id}', "IndexController@show");
            Route::get('/show/finance/{id}', "IndexController@showfinance");
            Route::get('/curPrice/{sym}/{base}/{type}', "IndexController@curPrice");
        });

        Route::group(['prefix'=>'Home','namespace'=>'Home'],function(){
            Route::get('/finance',"IndexController@index");
            Route::get('/favourite',"IndexController@favourite");
            Route::get('/trade/pending',"IndexController@pending");
            Route::get('/trade/pending',"IndexController@pending");
            Route::get('/trade/open',"IndexController@open");
            Route::get('/trade/close',"IndexController@close");
            
            Route::get('/trade/indextwo',"IndexController@indextwo");
        });

        
        Route::group(['prefix'=>'certificates','namespace'=>'Certificates','middleware'=>['CheckKyc']],function(){
            Route::get('/',"certificatesController@index");
            Route::post('/post',"certificatesController@store");
        });
        
        Route::group(['prefix'=>'profile','namespace'=>'Profile'],function(){
            Route::get('/index',"IndexController@index");
            Route::get('/statistics',"IndexController@getstatistics");
            Route::post('/update/{id}',"IndexController@update");
            Route::get('investments','IndexController@investments');
        });
        
        Route::group(['prefix'=>'wallet','namespace'=>'Wallet','middleware'=>['CheckKyc']],function(){
            Route::get('/',"IndexController@index");
            Route::get('/transfers',"IndexController@transfers");
            Route::post('/store',"IndexController@store");
        });





        Route::group(['namespace'=>'finance'],function(){
           Route::group(['prefix'=>'Bouns','namespace'=>'Bouns'],function(){
                Route::resource('/',"IndexController");
            });
            Route::group(['prefix'=>'transactions','namespace'=>'transactions'],function(){
                Route::post('/',"IndexController@index");
                Route::get('/show/{id}',"IndexController@show");
            });

            Route::group(['prefix'=>'Deposits','namespace'=>'Deposits','middleware'=>['CheckKyc']],function(){
                Route::resource('/',"IndexController");
                Route::get('/index',"IndexController@index");
                Route::post('/store/credit',"IndexController@apiStore");
                Route::post('/store/bank',"IndexController@dStore");
                Route::post('/store/usdt',"IndexController@crypto");
            });

             Route::group(['prefix'=>'withdrawal','namespace'=>'withdrawl','middleware'=>['CheckKyc','CheckWithdrawal']],function(){
                Route::resource('/',"IndexController");
                Route::get('/wire',"IndexController@wire");
                Route::post('/store/usdt',"IndexController@crypto");
                Route::post('/store/wireaccount',"IndexController@wireaccout");
                Route::post('/store/bank',"IndexController@storewireaccout");
            });
        });

        Route::group(['prefix'=>'trade','namespace'=>'Trading','middleware'=>['CheckKyc']],function(){
            Route::post('/index/close', "IndexController@close");
            Route::post('/index/open', "IndexController@open");
            Route::post('/store', "IndexController@storeTrade")->middleware(['balanceuser','checktotalamounttrading','CheckTrade','check.time']);
            Route::post('/create', "IndexController@create")->middleware(['balanceuser','checktotalamounttrading','CheckTrade','check.time']);
            Route::post('/close/trade', "IndexController@Methodclosetrade");
            Route::post('/close', "IndexController@apiCloseTrade");
            Route::post('close/all', "IndexController@closeAllTrades");
            Route::get('/all', "IndexController@allTrades");
            Route::get('/check', "IndexController@checkTrades");
            Route::get('/update/profit',  "IndexController@loopTrades");
            Route::get('/mytrades', "IndexController@myTrades");
        });


        // Route::group(['prefix'=>'transactions','namespace'=>'Transaction'],function(){
        //     Route::get('/', "TransactionController@index");
        // });

        Route::group(['prefix'=>'plans','namespace'=>'Plan','middleware'=>['CheckKyc']],function(){
            Route::get('/', "indexController@index");
            Route::get('/investment', "indexController@investmentPlan");
            Route::get('/investment/plan','indexController@investmentPlan');
            Route::post('/post', "indexController@store")->middleware(['moneyuser']);
            Route::get('/setup', "indexController@setup");
            Route::get('/activate/{id}',"indexController@activate");
            Route::post('/{id}/upgrade',"indexController@upgrade");
        });


         Route::group(['prefix'=>'assets','namespace'=>'Assets'],function(){
            Route::post('/favourite', "IndexController@favourite");
            Route::get('/remove/favourite', "IndexController@removeFromfavourite");
            Route::get('/get/favourite', "IndexController@getfavourite");
        });

        Route::group(['prefix'=>'account','namespace'=>'Account'],function(){
            Route::post('/change/password',"ChangePasswordController@index");
            Route::post('/update',"indexController@update");
            Route::get('/data',"indexController@index");
            Route::post('/update/image',"indexController@updateimage");

        });


        Route::group(['namespace'=>'ActionTab'],function(){
               Route::group(['prefix'=>'kyc','namespace'=>'KYC'],function(){
                Route::get('/',"IndexController@index");
                Route::post('/store',"IndexController@store");
                Route::post('/update/{id}',"IndexController@update");
            });

        });

        Route::group(['prefix'=>'Auth','namespace'=>'Auth'],function(){
            Route::post('/logout',"LogoutController@logout");
            Route::group(['prefix'=>'forget/password'],function(){
                Route::post('/change',"ForgetPasswordController@changePassword");
            });
        });

    });
});