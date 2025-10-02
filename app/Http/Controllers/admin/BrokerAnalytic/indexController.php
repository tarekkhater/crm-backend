<?php

namespace App\Http\Controllers\admin\BrokerAnalytic;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Trade;
use App\Models\TradingAccount;
use App\Models\Deposit;
use App\Models\IBUSer;
use App\Models\IBRequest;
use App\Models\Admin;
use App\Models\InfotradeUser;
class indexController extends Controller
{

    public function __construct() {
        // $user = auth()->id();
        // $user->hasPermission('Dashboard-data-manger')->only('store');
    }

    public function index(){
        $data = [];
        $data['Business'] = $this->CountMainbusiness();
        $data['chart']=$this->CountMainChart();
        $this->setData($data);
        $this->setMessage("success");
        return $this->sendApiResonse();
    }


    public function CountMainbusiness(){
        $data = [];
        $data['clients'] = TradingAccount::count();
        $data['leads'] = User::where('type_id',1)->count();
        $data['tradeingAccount'] = TradingAccount::groupBy('user_id')->count();


        $data['money_manager'] = Admin::where('type_id',1)->count();
        $data['manager'] = Admin::where('type_id',4)->count();
        $data['tm_sales'] = Admin::where('type_id',6)->count();
        $data['tm_retenation'] = Admin::where('type_id',6)->count();
        $data['sales'] = Admin::where('type_id',7)->count();
        $data['retenation'] = Admin::where('type_id',8)->count();


        $data['Depositclients'] = Deposit::groupBy('user_id')->count();
        $data['IBClients'] = IBUser::groupBy('user_id')->count();
        $data['IBrequests'] = IBRequest::groupBy('user_id')->count();


        $data['Deposit'] = Deposit::groupBy('user_id')->count();

        // $data['withdrawal'] = withdrawal::groupBy('user_id')->count();

        $data['trade'] = Trade::count();
        $data['opentrades'] = Trade::whereStatus(0)->count();
        $data['Closetrades'] = Trade::whereStatus(1)->count();
        $data['ibopentrades'] = Trade::whereStatus(0)->count();
        $data['ibClosetrades'] = Trade::whereStatus(1)->count();
        return $data;
    }


    public function CountMainChart(){
        $year = 2024;
        $data = [
            'clients'=>[],
            'Profit'=>[],
            'Net'=>[]
        ];
        $months = [ 1,2,3,4,5,6,7,8,9,10,11,12];
        foreach($months as $month){
            $data['clients'][] = TradingAccount::whereYear('created_at', "$year")->whereMonth('created_at', "$month")->count();
            $data['Profit'][] = InfotradeUser::whereYear('created_at', "$year")->whereMonth('created_at', "$month")->sum('profit');
            $data['Net'][] = Deposit::whereYear('created_at', "$year")->whereMonth('created_at', "$month")->sum('net_amount');
        }
        return $data;
    }


    public function dataUser(){
        $data['Loss'] = Deposit::whereYear('created_at', "$year")->whereMonth('created_at', "$month")->sum('net_amount');
        $data['Profit'] = Deposit::whereYear('created_at', "$year")->whereMonth('created_at', "$month")->sum('net_amount');
        $data['deposit'] = Deposit::whereYear('created_at', "$year")->whereMonth('created_at', "$month")->sum('net_amount');

        return $data;
    }


}
