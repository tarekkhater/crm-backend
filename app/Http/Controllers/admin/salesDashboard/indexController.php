<?php
namespace App\Http\Controllers\admin\salesDashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Trade;
use App\Models\TradingAccount;
use App\Models\Deposit;
use App\Models\IBUSer;
use App\Models\IBRequest;
use App\Models\InfoTradeUser;
class indexController extends Controller
{

    public function __construct() {
        // $user = auth()->id();
        // $user->hasPermission('Dashboard-data-manger')->only('store');
    }

    public function index(){
        $data = [];
        $data = $this->CountMainSummary();
        $this->setData($data);
        $this->setMessage("success");
        return $this->sendApiResonse();
    }

    public function CountMainSummary(){
        $data = [];

        $userIds = User::where('type_id', 1)->pluck('id');

        $data['leads'] = User::whereIn('id', $userIds)->orderBy('created_at')->get();
        $data['no_answer'] = InfoTradeUser::whereIn('user_id', $userIds)->where('status_id', 1)->orderBy('created_at')->get();
        $data['call_back'] = InfoTradeUser::whereIn('user_id', $userIds)->where('status_id', 2)->orderBy('created_at')->get();
        $data['new'] = InfoTradeUser::whereIn('user_id', $userIds)->where('status_id', 3)->orderBy('created_at')->get();
        $data['interested'] = InfoTradeUser::whereIn('user_id', $userIds)->where('status_id', 4)->orderBy('created_at')->get();
        $data['not_interested'] = InfoTradeUser::whereIn('user_id', $userIds)->where('status_id', 5)->orderBy('created_at')->get();
        $data['busy'] = InfoTradeUser::whereIn('user_id', $userIds)->where('status_id', 6)->orderBy('created_at')->get();
        $data['worng_number'] = InfoTradeUser::whereIn('user_id', $userIds)->where('status_id', 7)->orderBy('created_at')->get();

        return $data;
    }


    // public function CountMainSummary(){
    //     $data = [];
    //     $userId = User::whereIn('id',getUsersIds())->where('type_id',1)->pluck('id');
    //     $data['leads'] = User::whereIn('id',$userId)->orderBy('created_at')->get();
    //     $data['no_answer'] = InfoTradeUser::whereIn('user_id',$userId)->where('status_id',1)->orderBy('created_at')->get();
    //     $data['call_back'] = InfoTradeUser::whereIn('user_id',$userId)->where('status_id',2)->orderBy('created_at')->get();
    //     $data['new'] = InfoTradeUser::whereIn('user_id',$userId)->where('status_id',3)->orderBy('created_at')->get();
    //     $data['interested'] = InfoTradeUser::whereIn('user_id',$userId)->where('status_id',4)->orderBy('created_at')->get();
    //     $data['not_interested'] = InfoTradeUser::whereIn('user_id',$userId)->where('status_id',5)->orderBy('created_at')->get();
    //     $data['busy'] = InfoTradeUser::whereIn('user_id',$userId)->where('status_id',6)->orderBy('created_at')->get();
    //     $data['worng_number'] = InfoTradeUser::whereIn('user_id',$userId)->where('status_id',7)->orderBy('created_at')->get();
    //     return $data;
    // }



}
