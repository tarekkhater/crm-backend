<?php

namespace App\Http\Controllers\admin\Home;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\AssignUserManager;
use App\Models\InfoTradeUser;
use App\Models\User;
use App\Models\Admin;
use App\Models\Identity;
use App\Models\TradingAccount;
use App\Models\Trade;
use App\Models\Deposit;
use App\Models\Withdrawal;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
class IndexController extends Controller
{
    public function index(Request $request){
        $data = [
            // [
            //     'name'=>"Total Leads",
            //     'total'=>$this->firstSecation($request)['leads']['total'],
            //     'month'=>$this->firstSecation($request)['leads']['deffrient'],
            //     'url'=>'../leads'
            // ],
            [
                'name'=>"Total Leads",
                'total'=>$this->firstSecation($request)['client']['total'],
                'month'=>$this->firstSecation($request)['client']['deffrient'],
                'url'=>'../leads'
                ],[
                'name'=>"Total TeamLeaders",
                'total'=>$this->firstSecation($request)['teamleader']['total']??0,
                'month'=>$this->firstSecation($request)['teamleader']['deffrient']??0,
                'url'=>'../conversion/team-leader'
                ],
                [
                'name'=>"Total Agents",
                'total'=>$this->firstSecation($request)['agent']['total']??0,
                'month'=>$this->firstSecation($request)['agent']['deffrient']??0,
                'url'=>'../conversion/agents'
                ]
            ];
            $this->setData($data);
        $this->setMessage("success");
        return $this->sendApiResonse();
    }






    public function FTD(Request $request){
        $data = [[
                'name'=>"Public Customers",
                'total'=>$this->FTDCustomer($request)['total']??0,
                'month'=>$this->FTDCustomer($request)['deffrient']??0,
                'url'=>"../public_retention",
            ],[
                'name'=>"Active Customers",
                'total'=>$this->ActiveCustomer($request)['total']??0,
                'month'=>$this->ActiveCustomer($request)['deffrient']??0,
                'url'=>"../active_customer",
                ]
            ];
            $this->setData($data);
        $this->setMessage("success");
        return $this->sendApiResonse();
    }
   
    public function firstSecation(Request $request)
    {
        $data = [];
        $ids = getUsersIds();
        $month = date("m", strtotime('-1 month'));
        $year = date('Y');

        $data['client']['deffrient'] = User::whereIn('id', $ids)
            ->where('type_id', 2)
            ->whereYear("created_at", $year)
            ->whereMonth("created_at", $month)
            ->count();
        $data['client']['total'] = User::whereIn('id', $ids)
            ->where('type_id', 2)
            ->count();

        $data['leads']['deffrient'] = User::whereIn('id', $ids)
            ->where('type_id', 1)
             ->whereYear("created_at", $year)
            ->whereMonth("created_at", $month)
            ->count();
        $data['leads']['total'] = User::whereIn('id', $ids)
            ->where('type_id', 1)
            ->count();

        $data['teamleader']['deffrient'] = Admin::where('type_id', 6)
             ->whereYear("created_at", $year)
            ->whereMonth("created_at", $month)
            ->count();
        $data['teamleader']['total'] = Admin::where('type_id', 6)
            ->count();

        $data['agent']['deffrient'] = Admin::whereIn('type_id', [7,8])
             ->whereYear("created_at", $year)
            ->whereMonth("created_at", $month)
            ->count();
        $data['agent']['total'] = Admin::whereIn('type_id', [7,8])
            ->count();
        // $data['broker']['deffrient'] = IB::whereMonth("created_at",$leftmonth)->count();
        // $data['broker']['total'] = IB::whereMonth("created_at",$month)->count();


        // $data['Deposit']['deffrient'] = Deposit::whereIn('user_id', $ids)->whereMonth("created_at",$leftmonth)->count();
        // $data['Deposit']['total'] = Deposit::whereIn('user_id', $ids)->whereMonth("created_at",$month)->count();

        // $data['withdrawal']['deffrient'] = Withdrawal::whereIn('user_id', $ids)->whereMonth("created_at",$leftmonth)->count();
        // $data['withdrawal']['total'] = Withdrawal::whereIn('user_id', $ids)->whereMonth("created_at",$month)->count();

        // $data['trade']['deffrient'] = Trade::whereIn('user_id', $ids)->whereMonth("created_at",$leftmonth)->count();
        // $data['trade']['total'] = Trade::whereIn('user_id', $ids)->whereMonth("created_at",$month)->count();

        return $data;
    }



    public function ActiveCustomer(Request $request)
    {
        $data = [];
        $ids = getUsersIds();
         $id = AssignUserManager::whereIn('user_id', $ids)->where('admin_id','<>',0)->pluck('user_id');
         $idspotinal = InfoTradeUser::whereIn('user_id',$id)->where('status_id','<>',4)->pluck('user_id');
        if(isset($request->type)) {
            switch($request->type) {
                case 'day':
                    $daily = date('Y-m-d');
                    $leftdaily = date('Y-m-d', strtotime('-1 day'));
                    $data['deffrient'] = User::whereIn('id', $idspotinal)->where('type_id',2)->whereDay('created_at', $leftdaily)->count();
                    $data['total'] = User::whereIn('id', $idspotinal)->where('type_id',2)->whereDay('created_at', $daily)->count();
                    break;

                case 'month':
                    $month = date("Y-m");
                    $leftmonth = date('Y-m', strtotime('-1 month'));
                    $data['deffrient'] = User::whereIn('id', $idspotinal)->where('type_id',2)->whereMonth('created_at', $leftmonth)->count();
                    $data['total'] = User::whereIn('id', $idspotinal)->where('type_id',2)->whereMonth('created_at', $month)->count();
                    break;

                case 'weekly':
                    $start = now()->startOfWeek(Carbon::TUESDAY);
                    $end = now()->endOfWeek(Carbon::MONDAY);
                    $data['deffrient'] = User::whereIn('id', $idspotinal)->whereBetween('created_at', [$start, $end])->where('type_id',2)->count();
                    $data['total'] = User::whereIn('id', $idspotinal)->whereBetween('created_at', [$start, $end])->where('type_id',2)->count();
                    break;

                case 'all':
                    $data['deffrient'] = User::whereIn('id', $idspotinal)->where('type_id',2)->count();
                    $data['total'] = User::whereIn('id', $idspotinal)->where('type_id',2)->count();
                    break;
            }
        } else {
            $year = date('y');
            $leftyear = $year - 1;
            $data['deffrient'] = User::whereIn('id', $idspotinal)->where('type_id',2)->whereYear('created_at', $leftyear)->count();
            $data['total'] = User::whereIn('id', $idspotinal)->where('type_id',2)->whereYear('created_at', $year)->count();
        }

        return $data;
    }


    public function FTDCustomer(Request $request)
    {
        $data = [];
       $id = AssignUserManager::where('admin_id','<>',0)->pluck('user_id');
        $idspotinal = InfoTradeUser::whereIn('user_id',getUsersIds())->whereNotIn('user_id',$id)->where('status_id','<>',4)->pluck('user_id');

        switch($request->type ?? null) {
            case 'day':
                $daily = date('Y-m-d');
                $leftdaily = date('Y-m-d', strtotime('-1 day'));
                $data['deffrient'] = User::whereIn('id', $idspotinal)->where('type_id',2)->orWhere('type_id',5)
                    ->whereDay('depositedAcount', $leftdaily)
                    ->count();
                $data['total'] = User::whereIn('id', $idspotinal)->where('type_id',2)->orWhere('type_id',5)
                    ->whereDay('depositedAcount', $daily)
                    ->count();
                break;

            case 'month':
                $month = date("Y-m");
                $leftmonth = date('Y-m', strtotime('-1 month'));
                $data['deffrient'] = User::whereIn('id', $idspotinal)->where('type_id',2)->orWhere('type_id',5)
                    ->whereMonth('depositedAcount', $leftmonth)
                    ->count();
                $data['total'] = User::whereIn('id', $idspotinal)->where('type_id',2)->orWhere('type_id',5)
                    ->whereMonth('depositedAcount', $month)
                    ->count();
                break;

            case 'weekly':
                $start = now()->startOfWeek(Carbon::TUESDAY);
                $end = now()->endOfWeek(Carbon::MONDAY);
                $data['deffrient'] = User::whereIn('id', $idspotinal)->where('type_id',2)->orWhere('type_id',5)
                    ->whereBetween('depositedAcount', [$start, $end])
                    ->count();
                $data['total'] = User::whereIn('id', $idspotinal)->where('type_id',2)->orWhere('type_id',5)
                    ->whereBetween('depositedAcount', [$start, $end])
                    ->count();
                break;

            case 'all':
                $data['deffrient'] = User::whereIn('id', $idspotinal)->where('type_id',2)->orWhere('type_id',5)->count();
                $data['total'] = User::whereIn('id', $idspotinal)->where('type_id',2)->orWhere('type_id',5)->count();
                break;

            default:
                $year = date('y');
                $leftyear = $year - 1;
                $data['deffrient'] = User::whereIn('id', $idspotinal)->where('type_id',2)->orWhere('type_id',5)
                    ->whereYear('depositedAcount', $leftyear)
                    ->count();
                $data['total'] = User::whereIn('id', $idspotinal)->where('type_id',2)->orWhere('type_id',5)
                    ->whereYear('depositedAcount', $year)
                    ->count();
        }

        return $data;
    }


    public function kyc(Request $request){
        $data = [];
        $ids = getUsersIds();

        if(isset($request->type) && $request->type == 'day'){
            $data['complated'] = Identity::whereIn('user_id', $ids)->wherestatus('1')->whereDay('created_at', Carbon::today() )->count();
            $data['faild'] = Identity::whereIn('user_id', $ids)->wherestatus('0')->whereDay('created_at', Carbon::today() )->count();
        }else if(isset($request->type) && $request->type == 'month'){
            $month = date("m");
            $year = date("Y");
            $data['complated'] = Identity::whereIn('user_id', $ids)->wherestatus('1')->whereYear('created_at', $year)->whereMonth('created_at', $month )->count();
            $data['faild'] = Identity::whereIn('user_id', $ids)->wherestatus('0')->whereYear('created_at', $year)->whereMonth('created_at', $month )->count();
        }else if(isset($request->type) && $request->type == 'weekly'){
            $start = now()->startOfWeek(Carbon::TUESDAY);
            $end = now()->endOfWeek(Carbon::MONDAY);
            $data['complated'] = Identity::whereIn('user_id', $ids)->wherestatus('1')->whereBetween('created_at', [
                $start,
                $end,
            ])->count();
            $data['faild'] = Identity::whereIn('user_id', $ids)->wherestatus('0')->whereBetween('created_at', [
                $start,
                $end,
            ])->count();
        }else if(isset($request->type) && $request->type == 'all'){
            $data['complated'] = Identity::whereIn('user_id', $ids)->wherestatus('1')->count();
            $data['faild'] = Identity::whereIn('user_id', $ids)->wherestatus('0')->count();
        }else{
            $year=date('y');
            $data['complated'] = Identity::whereIn('user_id', $ids)->where('status','1')->count();
            $data['faild'] = Identity::whereIn('user_id', $ids)->where('status','0')->count();
        }


            $datas = [[
                'name'=>"Completed",
                'total'=>$data['complated'],
            ],[
                'name'=>"Pending",
                'total'=>$data['faild'],
                ]
            ];
        $this->setData($datas);
        $this->setMessage("success");
        return $this->sendApiResonse();
    }

    public function LeadsChart(){
        $ids = getUsersIds();
        $months =[];
        $data = [];
        $nummonths =12;
        for($i=1;$i<=$nummonths;$i++){
             $months[] = "01-".$i.'-'.date('Y');
             $data['dataset'][] = User::whereIn('id',$ids)->where('type_id',1)->whereYear('created_at',date('Y'))->whereMonth('created_at',$i)->count();   
        }
        // foreach($months as $month){
        //     $data['dataset'][] = User::whereIn('id',$ids)->where('type_id',1)->whereMonth('created_at',$month)->count();
        // }
        $data['labels'] = $months;
         $this->setData($data);
        $this->setMessage("success");
        return $this->sendApiResonse();
    }

    public function withdrawal(Request $request){
                $data = [];
        $ids = getUsersIds();
        if(isset($request->type)) {
            switch($request->type) {
                case 'day':
                    $daily = date('Y-m-d');
                    $leftdaily = date('Y-m-d', strtotime('-1 day'));
                    $data['deffrient'] = Withdrawal::whereIn('user_id', $ids)->whereDay('created_at', $leftdaily)->sum("amount");
                    $data['total'] = Withdrawal::whereIn('user_id', $ids)->whereDay('created_at', $daily)->sum("amount");
                    $data['completed'] = Withdrawal::whereIn('user_id', $ids)->whereStatus('1')->whereDay('created_at', $daily)->count();
                    $data['pending'] = Withdrawal::whereIn('user_id', $ids)->whereStatus('0')->whereDay('created_at', $daily)->count();
                    $data['failed'] = Withdrawal::whereIn('user_id', $ids)->whereStatus('2')->whereDay('created_at', $daily)->count();
                    break;

                case 'month':
                    $month = date("Y-m");
                    $leftmonth = date('Y-m', strtotime('-1 month'));
                    $data['deffrient'] = Withdrawal::whereIn('user_id', $ids)->whereMonth('created_at', $leftmonth)->sum("amount");
                    $data['total'] = Withdrawal::whereIn('user_id', $ids)->whereMonth('created_at', $month)->sum("amount");
                     $data['completed'] = Withdrawal::whereIn('user_id', $ids)->whereStatus('1')->whereMonth('created_at', $month)->count();
                    $data['pending'] = Withdrawal::whereIn('user_id', $ids)->whereStatus('0')->whereMonth('created_at', $month)->count();
                    $data['failed'] = Withdrawal::whereIn('user_id', $ids)->whereStatus('2')->whereMonth('created_at', $month)->count();
                    break;

                case 'weekly':
                    $start = now()->startOfWeek(Carbon::TUESDAY);
                    $end = now()->endOfWeek(Carbon::MONDAY);
                    $data['deffrient'] = Withdrawal::whereBetween('created_at', [$start, $end])->sum("amount");
                    $data['total'] = Withdrawal::whereIn('user_id', $ids)->whereBetween('created_at', [$start, $end])->sum("amount");
                    $data['completed'] = Withdrawal::whereIn('user_id', $ids)->whereStatus('1')->whereBetween('created_at', [$start, $end])->count();
                    $data['pending'] = Withdrawal::whereIn('user_id', $ids)->whereStatus('0')->whereBetween('created_at', [$start, $end])->count();
                    $data['failed'] = Withdrawal::whereIn('user_id', $ids)->whereStatus('2')->whereBetween('created_at', [$start, $end])->count();
                    break;

                case 'all':
                    $data['deffrient'] = Withdrawal::whereIn('user_id', $ids)->sum("amount");
                    $data['total'] = Withdrawal::whereIn('user_id', $ids)->sum("amount");
                     $data['completed'] = Withdrawal::whereIn('user_id', $ids)->whereStatus('1')->count();
                    $data['pending'] = Withdrawal::whereIn('user_id', $ids)->whereStatus('0')->count();
                    $data['failed'] = Withdrawal::whereIn('user_id', $ids)->whereStatus('2')->count();
                    break;
            }
        } else {
            $year = date('y');
            $leftyear = $year - 1;
            $data['deffrient'] = Withdrawal::whereIn('user_id', $ids)->whereYear('created_at', $leftyear)->sum("amount");
            $data['total'] = Withdrawal::whereIn('user_id', $ids)->whereYear('created_at', $year)->sum("amount");
            $data['completed'] = Withdrawal::whereIn('user_id', $ids)->whereStatus('1')->whereYear('created_at', $leftyear)->count();
                    $data['pending'] = Withdrawal::whereIn('user_id', $ids)->whereStatus('0')->whereYear('created_at', $leftyear)->count();
                    $data['failed'] = Withdrawal::whereIn('user_id', $ids)->whereStatus('2')->whereYear('created_at', $leftyear)->count();
        }

        if ($data['deffrient'] != 0) {
            $percentDifference = (($data['total'] - $data['deffrient']) / $data['deffrient']) * 100;
            $data['deffrient_percent'] = $percentDifference;
        } else {
            $percentDifference = $data['total'] > 0 ? 100 : 0;  // إذا كان المبلغ في الشهر السابق 0، فالنسبة تكون 100% إذا كان هناك مبلغ في الشهر الحالي.
        
            $data['deffrient_percent'] = $percentDifference;
        }

        $data['dataset']= $this->WithdrawalChart();
        
        $this->setData($data);
        $this->setMessage("success");
        return $this->sendApiResonse();
    }

    public function deposite(Request $request){
        $data = [];
        
        if(isset($request->type)) {
            switch($request->type) {
                case 'day':
                    $daily = date('Y-m-d');
                    $leftdaily = date('Y-m-d', strtotime('-1 day'));
                    $data['deffrient'] = Deposit::whereDay('created_at', $leftdaily)->sum("amount");
                    $data['total'] = Deposit::whereDay('created_at', $daily)->sum("amount");
                    $data['completed'] = Deposit::whereStatus('1')->whereDay('created_at', $daily)->count();
                    $data['pending'] = Deposit::whereStatus('0')->whereDay('created_at', $daily)->count();
                    $data['failed'] = Deposit::whereStatus('2')->whereDay('created_at', $daily)->count();
                    break;

                case 'month':
                    $month = date("Y-m");
                    $leftmonth = date('Y-m', strtotime('-1 month'));
                    $data['deffrient'] = Deposit::whereMonth('created_at', $leftmonth)->sum("amount");
                    $data['total'] = Deposit::whereMonth('created_at', $month)->sum("amount");
                     $data['completed'] = Deposit::whereStatus('1')->whereMonth('created_at', $month)->count();
                    $data['pending'] = Deposit::whereStatus('0')->whereMonth('created_at', $month)->count();
                    $data['failed'] = Deposit::whereStatus('2')->whereMonth('created_at', $month)->count();
                    break;

                case 'weekly':
                    $start = now()->startOfWeek(Carbon::TUESDAY);
                    $end = now()->endOfWeek(Carbon::MONDAY);
                    $data['deffrient'] = Deposit::whereBetween('created_at', [$start, $end])->sum("amount");
                    $data['total'] = Deposit::whereBetween('created_at', [$start, $end])->sum("amount");
                    $data['completed'] = Deposit::whereStatus('1')->whereBetween('created_at', [$start, $end])->count();
                    $data['pending'] = Deposit::whereStatus('0')->whereBetween('created_at', [$start, $end])->count();
                    $data['failed'] = Deposit::whereStatus('2')->whereBetween('created_at', [$start, $end])->count();
                    break;

                case 'all':
                   
                    $data['deffrient'] = Deposit::select()->sum("amount");
                    $data['total'] = Deposit::select()->sum("amount");
                     $data['completed'] = Deposit::whereStatus('1')->count();
                    $data['pending'] = Deposit::whereStatus('0')->count();
                    $data['failed'] = Deposit::whereStatus('2')->count();
                    break;
                case 'year':
                    $year = date('y');
                    $leftyear = $year - 1;
                    $data['deffrient'] = Deposit::whereYear('created_at', $leftyear)->sum("amount");
                    $data['total'] = Deposit::whereYear('created_at', $year)->sum("amount");
                    $data['completed'] = Deposit::whereStatus('1')->whereYear('created_at', $leftyear)->count();
                    $data['pending'] = Deposit::whereStatus('0')->whereYear('created_at', $leftyear)->count();
                    $data['failed'] = Deposit::whereStatus('2')->whereYear('created_at', $leftyear)->count();
                    break;
            }
        } else {
            $year = date('y');
            $leftyear = $year - 1;
            $data['deffrient'] = Deposit::whereYear('created_at', $leftyear)->sum("amount");
            $data['total'] = Deposit::whereYear('created_at', $year)->sum("amount");
            $data['completed'] = Deposit::whereStatus('1')->whereYear('created_at', $leftyear)->count();
                    $data['pending'] = Deposit::whereStatus('0')->whereYear('created_at', $leftyear)->count();
                    $data['failed'] = Deposit::whereStatus('2')->whereYear('created_at', $leftyear)->count();
        }

   if ($data['deffrient'] != 0) {
            $percentDifference = (($data['total'] - $data['deffrient']) / $data['deffrient']) * 100;
            $data['deffrient_percent'] = $percentDifference;
        } else {
            $percentDifference = $data['total'] > 0 ? 100 : 0;  // إذا كان المبلغ في الشهر السابق 0، فالنسبة تكون 100% إذا كان هناك مبلغ في الشهر الحالي.
        
            $data['deffrient_percent'] = $percentDifference;
        }
        $data['dataset']= $this->depositeChart();
        $this->setData($data);
        $this->setMessage("success");
        return $this->sendApiResonse();
    }
    
    
      public function depositeChart(){
        $ids = getUsersIds();
        $months =[];
        $data = [];
        $nummonths =12;
        for($i=1;$i<=$nummonths;$i++){
             $months[] = "01-".$i.'-'.date('Y');
             $data[] = Deposit::whereIn('user_id',$ids)->whereYear('created_at',date('Y'))->whereMonth('created_at',$i)->count();   
        }
        return $data;
    }
    public function WithdrawalChart(){
        $ids = getUsersIds();
        $months =[];
        $data = [];
        $nummonths =12;
        for($i=1;$i<=$nummonths;$i++){
             $months[] = "01-".$i.'-'.date('Y');
             $data[] = Withdrawal::whereIn('user_id',$ids)->whereYear('created_at',date('Y'))->whereMonth('created_at',$i)->count();   
        }
        return $data;
    }

    public function tradesChart()
    {
        // $ids = getUsersIds();
        // $years = Trade::whereIn('user_id', $ids)
        //     ->select([DB::raw('extract(year FROM created_at) AS year')])
        //     ->get()
        //     ->unique('year')
        //     ->sortBy('year')
        //     ->pluck('year')
        //     ->toArray();

         $ids = getUsersIds();
        $months =[];
        $data = [];
        $nummonths =12;
        for($i=1;$i<=$nummonths;$i++){
             $months[] = "01-".$i.'-'.date('Y');
             $data['dataset'][] = Trade::whereIn('user_id',$ids)->whereYear('created_at',date('Y'))->whereMonth('created_at',$i)->count();   
        }
        $this->setData($data);
        $this->setMessage("success");
        return $this->sendApiResonse();
        
        
        $year = '2020';
        $months = [1,2,3,4,5,6,7,8,9,10,11,12];
        $dataopen = [];

        foreach($months as $month) {
            $dataopen[$month]['total'] = Trade::whereIn('user_id', $ids)
                ->whereYear("created_at", date('Y'))
                ->whereMonth("created_at", $month)
                ->count();

            $dataopen[$month]['opentrades'] = Trade::whereIn('user_id', $ids)
                ->whereStatus('0')
                ->whereYear("created_at", date('Y'))
                ->whereMonth("created_at", $month)
                ->count();

            $dataopen[$month]['Closetrades'] = Trade::whereIn('user_id', $ids)
                ->whereStatus('1')
                ->whereYear("created_at", date('Y'))
                ->whereMonth("close_at", $month)
                ->count();
        }

        // تعديل لإرجاع البيانات بنفس تنسيق باقي الدوال
        $this->setData($dataopen);
        $this->setMessage("success");
        return $this->sendApiResonse();
    }

}
