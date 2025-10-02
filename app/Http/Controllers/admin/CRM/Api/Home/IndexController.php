<?php

namespace App\Http\Controllers\admin\CRM\Api\Home;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

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
        $data = [[
                'name'=>"Total Leads",
                'total'=>$this->firstSecation($request)['leads']['total'],
                'month'=>$this->firstSecation($request)['leads']['deffrient']
            ],[
                'name'=>"Total Customers",
                'total'=>$this->firstSecation($request)['client']['total'],
                'month'=>$this->firstSecation($request)['client']['deffrient']
                ],[
                'name'=>"Total TeamLeaders",
                'total'=>$this->firstSecation($request)['teamleader']['total'],
                'month'=>$this->firstSecation($request)['teamleader']['deffrient']
                ],
                [
                'name'=>"Total Agents",
                'total'=>$this->firstSecation($request)['agent']['total'],
                'month'=>$this->firstSecation($request)['agent']['deffrient']
                ]
            ];
            $this->setData($data);
        $this->setMessage("success");
        return $this->sendApiResonse();
    }
    
  
    
    
   
    
    public function FTD(Request $request){
        $data = [[
                'name'=>"FTD Customers",
                'total'=>$this->FTDCustomer($request)['total'],
                'month'=>$this->FTDCustomer($request)['deffrient']
            ],[
                'name'=>"Active Customers",
                'total'=>$this->ActiveCustomer($request)['total'],
                'month'=>$this->ActiveCustomer($request)['deffrient']
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
        $month = date("Y-m");
        $leftmonth = date('Y-m', strtotime('-1 month'));
    
        $data['client']['deffrient'] = TradingAccount::whereIn('user_id', $ids)
            ->whereMonth("created_at", $leftmonth)
            ->count();
        $data['client']['total'] = TradingAccount::whereIn('user_id', $ids)
            ->whereMonth("created_at", $month)
            ->count();
    
        $data['leads']['deffrient'] = User::whereIn('id', $ids)
            ->where('type_id', 1)
            ->whereMonth("created_at", $leftmonth)
            ->count();
        $data['leads']['total'] = User::whereIn('id', $ids)
            ->where('type_id', 1)
            ->whereMonth("created_at", $month)
            ->count();
    
        $data['teamleader']['deffrient'] = Admin::where('type_id', 6)
            ->whereMonth("created_at", $leftmonth)
            ->count();
        $data['teamleader']['total'] = Admin::where('type_id', 6)
            ->whereMonth("created_at", $month)
            ->count();
    
        $data['agent']['deffrient'] = Admin::whereIn('type_id', [7,8])
            ->whereMonth("created_at", $leftmonth)
            ->count();
        $data['agent']['total'] = Admin::whereIn('type_id', [7,8])
            ->whereMonth("created_at", $month)
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
        
        if(isset($request->type)) {
            switch($request->type) {
                case 'day':
                    $daily = date('Y-m-d');
                    $leftdaily = date('Y-m-d', strtotime('-1 day'));
                    $data['deffrient'] = User::whereIn('id', $ids)->whereTypeId(2)->whereDay('created_at', $leftdaily)->count();
                    $data['total'] = User::whereIn('id', $ids)->whereTypeId(2)->whereDay('created_at', $daily)->count();
                    break;
                    
                case 'month':
                    $month = date("Y-m");
                    $leftmonth = date('Y-m', strtotime('-1 month'));
                    $data['deffrient'] = User::whereIn('id', $ids)->whereTypeId(2)->whereMonth('created_at', $leftmonth)->count();
                    $data['total'] = User::whereIn('id', $ids)->whereTypeId(2)->whereMonth('created_at', $month)->count();
                    break;
                    
                case 'weekly':
                    $start = now()->startOfWeek(Carbon::TUESDAY);
                    $end = now()->endOfWeek(Carbon::MONDAY);
                    $data['deffrient'] = User::whereBetween('created_at', [$start, $end])->whereTypeId(2)->count();
                    $data['total'] = User::whereIn('id', $ids)->whereBetween('created_at', [$start, $end])->whereTypeId(2)->count();
                    break;
                    
                case 'all':
                    $data['deffrient'] = User::whereIn('id', $ids)->whereTypeId(2)->count();
                    $data['total'] = User::whereIn('id', $ids)->whereTypeId(2)->count();
                    break;
            }
        } else {
            $year = date('y');
            $leftyear = $year - 1;
            $data['deffrient'] = User::whereIn('id', $ids)->whereTypeId(2)->whereYear('created_at', $leftyear)->count();
            $data['total'] = User::whereIn('id', $ids)->whereTypeId(2)->whereYear('created_at', $year)->count();
        }
        
        return $data;
    }


    public function FTDCustomer(Request $request)
    {
        $data = [];
        $ids = getUsersIds();
        
        switch($request->type ?? null) {
            case 'day':
                $daily = date('Y-m-d');
                $leftdaily = date('Y-m-d', strtotime('-1 day'));
                $data['deffrient'] = User::whereIn('id', $ids)
                    ->whereDay('depositedAcount', $leftdaily)
                    ->count();
                $data['total'] = User::whereIn('id', $ids)
                    ->whereDay('depositedAcount', $daily)
                    ->count();
                break;
                
            case 'month':
                $month = date("Y-m");
                $leftmonth = date('Y-m', strtotime('-1 month'));
                $data['deffrient'] = User::whereIn('id', $ids)
                    ->whereMonth('depositedAcount', $leftmonth)
                    ->count();
                $data['total'] = User::whereIn('id', $ids)
                    ->whereMonth('depositedAcount', $month)
                    ->count();
                break;
                
            case 'weekly':
                $start = now()->startOfWeek(Carbon::TUESDAY);
                $end = now()->endOfWeek(Carbon::MONDAY);
                $data['deffrient'] = User::whereIn('id', $ids)
                    ->whereBetween('depositedAcount', [$start, $end])
                    ->count();
                $data['total'] = User::whereIn('id', $ids)
                    ->whereBetween('depositedAcount', [$start, $end])
                    ->count();
                break;
                
            case 'all':
                $data['deffrient'] = User::whereIn('id', $ids)->count();
                $data['total'] = User::whereIn('id', $ids)->count();
                break;
                
            default:
                $year = date('y');
                $leftyear = $year - 1;
                $data['deffrient'] = User::whereIn('id', $ids)
                    ->whereYear('depositedAcount', $leftyear)
                    ->count();
                $data['total'] = User::whereIn('id', $ids)
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
            $data['complated'] = Identity::whereIn('user_id', $ids)->wherestatus('1')->whereYear('created_at', $year)->count();
            $data['faild'] = Identity::whereIn('user_id', $ids)->wherestatus('0')->whereYear('created_at', $year)->count();
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
         $months =['1-1-'.date('Y'),'1-2-'.date('Y'),'1-3-'.date('Y'),'1-4-'.date('Y'),'1-5-'.date('Y'),'1-6-'.date('Y'),'1-7-'.date('Y'),'1-8-'.date('Y'),'1-9-'.date('Y'),'1-10-'.date('Y'),'1-11-'.date('Y'),'1-12-'.date('Y')];
        $data = [];
        foreach($months as $month){
            $data['dataset'][] = User::whereIn('id',$ids)->where('type_id',1)->whereMonth('created_at',$month)->count();
        }
        $data['labels'] = $months;
         $this->setData($data);
        $this->setMessage("success");
        return $this->sendApiResonse();
    }

    public function withdrawal(){
        $ids = getUsersIds();
        $withdrawals = Withdrawal::whereIn('user_id',$ids)->orderByDESC('created_at')->limit(3)->get();
        $this->setData($withdrawals);
        $this->setMessage("success");
        return $this->sendApiResonse();
    }

    public function deposite(){
        $ids = getUsersIds();
        $deposites = Deposit::whereIn('user_id',$ids)->orderByDESC('created_at')->limit(3)->get();
        $this->setData($deposites);
        $this->setMessage("success");
        return $this->sendApiResonse();
    }


    public function tradesChart()
    {
        $ids = getUsersIds();
        $years = Trade::whereIn('user_id', $ids)
            ->select([DB::raw('extract(year FROM created_at) AS year')])
            ->get()
            ->unique('year')
            ->sortBy('year')
            ->pluck('year')
            ->toArray();

        $year = '2020';
        $months = [1,2,3,4,5,6,7,8,9,10,11,12];
        $dataopen = [];
        
        foreach($months as $month) {
            $dataopen[$month]['total'] = Trade::whereIn('user_id', $ids)
                ->whereYear("created_at", $year)
                ->whereMonth("created_at", $month)
                ->count();
                
            $dataopen[$month]['opentrades'] = Trade::whereIn('user_id', $ids)
                ->whereStatus(0)
                ->whereYear("created_at", $year)
                ->whereMonth("created_at", $month)
                ->count();
                
            $dataopen[$month]['Closetrades'] = Trade::whereIn('user_id', $ids)
                ->whereStatus(1)
                ->whereYear("created_at", $year)
                ->whereMonth("close_at", $month)
                ->count();
        }
        
        // تعديل لإرجاع البيانات بنفس تنسيق باقي الدوال
        $this->setData($dataopen);
        $this->setMessage("success");
        return $this->sendApiResonse();
    }

}
