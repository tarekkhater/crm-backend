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
use Illuminate\Support\Facades\Cache;
use Carbon\Carbon;
class IndexController extends Controller
{
    // Cache duration in seconds (30 days)
    protected $cacheDuration = 2592000; // 30 * 24 * 60 * 60
    
    /**
     * Generate cache key based on user and request parameters
     */
    private function getCacheKey($prefix, Request $request = null)
    {
        $userId = auth()->user()->id;
        $typeId = auth()->user()->type_id;
        $type = $request ? ($request->type ?? 'default') : 'default';
        
        return "home_stats:{$prefix}:user_{$userId}:type_{$typeId}:filter_{$type}";
    }
    
    /**
     * Clear all cached data for current user
     * Call this when user data changes (new deposits, withdrawals, etc.)
     */
    public function clearCache()
    {
        $userId = auth()->user()->id;
        $typeId = auth()->user()->type_id;
        
        $prefixes = ['index', 'ftd', 'kyc', 'leads_chart', 'withdrawal', 'deposite', 'trades_chart'];
        $types = ['default', 'day', 'month', 'weekly', 'all', 'year'];
        
        foreach ($prefixes as $prefix) {
            foreach ($types as $type) {
                $cacheKey = "home_stats:{$prefix}:user_{$userId}:type_{$typeId}:filter_{$type}";
                Cache::forget($cacheKey);
            }
        }
        
        return response()->json(['message' => 'Cache cleared successfully']);
    }
    // public function index(Request $request){
    //     $data = [
    //         // [
    //         //     'name'=>"Total Leads",
    //         //     'total'=>$this->firstSecation($request)['leads']['total'],
    //         //     'month'=>$this->firstSecation($request)['leads']['deffrient'],
    //         //     'url'=>'../leads'
    //         // ],
    //         [
    //             'name'=>"Total Leads",
    //             'total'=>$this->firstSecation($request)['client']['total'],
    //             'month'=>$this->firstSecation($request)['client']['deffrient'],
    //             'url'=>'../leads'
    //             ],[
    //             'name'=>"Total TeamLeaders",
    //             'total'=>$this->firstSecation($request)['teamleader']['total']??0,
    //             'month'=>$this->firstSecation($request)['teamleader']['deffrient']??0,
    //             'url'=>'../conversion/team-leader'
    //             ],
    //             [
    //             'name'=>"Total Agents",
    //             'total'=>$this->firstSecation($request)['agent']['total']??0,
    //             'month'=>$this->firstSecation($request)['agent']['deffrient']??0,
    //             'url'=>'../conversion/agents'
    //             ]
    //         ];
    //         $this->setData($data);
    //     $this->setMessage("success");
    //     return $this->sendApiResonse();
    // }

    public function index(Request $request)
{
    // Cache for 30 days per user
    $cacheKey = $this->getCacheKey('index', $request);
    
    $data = Cache::remember($cacheKey, $this->cacheDuration, function() use ($request) {
        $section = $this->firstSecation($request);

        return [
            [
                'name' => "Total Leads",
                'total' => $section['client']['total'],
                'month' => $section['client']['deffrient'],
                'url' => '../leads'
            ],
            [
                'name' => "Total TeamLeaders",
                'total' => $section['teamleader']['total'] ?? 0,
                'month' => $section['teamleader']['deffrient'] ?? 0,
                'url' => '../conversion/team-leader'
            ],
            [
                'name' => "Total Agents",
                'total' => $section['agent']['total'] ?? 0,
                'month' => $section['agent']['deffrient'] ?? 0,
                'url' => '../conversion/agents'
            ]
        ];
    });

    $this->setData($data);
    $this->setMessage("success");
    return $this->sendApiResonse();
}




    public function FTD(Request $request){
        // Cache for 30 days per user
        $cacheKey = $this->getCacheKey('ftd', $request);
        
        $data = Cache::remember($cacheKey, $this->cacheDuration, function() use ($request) {
            // Cache the method results to avoid duplicate expensive queries
            $ftdData = $this->FTDCustomer($request);
            $activeData = $this->ActiveCustomer($request);
            
            return [[
                    'name'=>"Public Customers",
                    'total'=>$ftdData['total']??0,
                    'month'=>$ftdData['deffrient']??0,
                    'url'=>"../public_retention",
                ],[
                    'name'=>"Active Customers",
                    'total'=>$activeData['total']??0,
                    'month'=>$activeData['deffrient']??0,
                    'url'=>"../active_customer",
                    ]
                ];
        });
        
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

        // Optimize User queries by batching them into single query with conditional aggregation
        $userStats = User::whereIn('id', $ids)
            ->whereIn('type_id', [1, 2])
            ->selectRaw('
                type_id,
                COUNT(*) as total,
                SUM(CASE WHEN YEAR(created_at) = ? AND MONTH(created_at) = ? THEN 1 ELSE 0 END) as month_count
            ', [$year, $month])
            ->groupBy('type_id')
            ->get()
            ->keyBy('type_id');

        $data['client']['total'] = $userStats->get(2)->total ?? 0;
        $data['client']['deffrient'] = $userStats->get(2)->month_count ?? 0;
        
        $data['leads']['total'] = $userStats->get(1)->total ?? 0;
        $data['leads']['deffrient'] = $userStats->get(1)->month_count ?? 0;

        // Optimize Admin queries by batching them
        $adminStats = Admin::whereIn('type_id', [6, 7, 8])
            ->selectRaw('
                CASE WHEN type_id = 6 THEN 6 ELSE 78 END as type_group,
                COUNT(*) as total,
                SUM(CASE WHEN YEAR(created_at) = ? AND MONTH(created_at) = ? THEN 1 ELSE 0 END) as month_count
            ', [$year, $month])
            ->groupBy(DB::raw('CASE WHEN type_id = 6 THEN 6 ELSE 78 END'))
            ->get()
            ->keyBy('type_group');

        $data['teamleader']['total'] = $adminStats->get(6)->total ?? 0;
        $data['teamleader']['deffrient'] = $adminStats->get(6)->month_count ?? 0;

        $data['agent']['total'] = $adminStats->get(78)->total ?? 0;
        $data['agent']['deffrient'] = $adminStats->get(78)->month_count ?? 0;

        return $data;
    }



    public function ActiveCustomer(Request $request)
    {
        $data = [];
        $ids = getUsersIds();
        
        // Optimize by using JOIN instead of multiple whereIn with plucked IDs
        // This reduces the query to a single operation instead of 3 separate queries
        $baseQuery = User::query()
            ->whereIn('users.id', $ids)
            ->where('users.type_id', 2)
            ->whereExists(function ($query) {
                $query->select(DB::raw(1))
                    ->from('assign_user_managers')
                    ->whereColumn('assign_user_managers.user_id', 'users.id')
                    ->where('assign_user_managers.admin_id', '<>', 0);
            })
            ->whereExists(function ($query) {
                $query->select(DB::raw(1))
                    ->from('info_trade_users')
                    ->whereColumn('info_trade_users.user_id', 'users.id')
                    ->where('info_trade_users.status_id', '<>', 4);
            });
        
        if(isset($request->type)) {
            switch($request->type) {
                case 'day':
                    $daily = Carbon::today();
                    $leftdaily = Carbon::yesterday();
                    $data['deffrient'] = (clone $baseQuery)->whereDate('users.created_at', $leftdaily)->count();
                    $data['total'] = (clone $baseQuery)->whereDate('users.created_at', $daily)->count();
                    break;

                case 'month':
                    $month = date("m");
                    $year = date("Y");
                    $leftmonth = date('m', strtotime('-1 month'));
                    $leftyear = date('Y', strtotime('-1 month'));
                    $data['deffrient'] = (clone $baseQuery)->whereYear('users.created_at', $leftyear)->whereMonth('users.created_at', $leftmonth)->count();
                    $data['total'] = (clone $baseQuery)->whereYear('users.created_at', $year)->whereMonth('users.created_at', $month)->count();
                    break;

                case 'weekly':
                    $start = now()->startOfWeek(Carbon::TUESDAY);
                    $end = now()->endOfWeek(Carbon::MONDAY);
                    $data['deffrient'] = (clone $baseQuery)->whereBetween('users.created_at', [$start, $end])->count();
                    $data['total'] = (clone $baseQuery)->whereBetween('users.created_at', [$start, $end])->count();
                    break;

                case 'all':
                    $total = (clone $baseQuery)->count();
                    $data['deffrient'] = $total;
                    $data['total'] = $total;
                    break;
            }
        } else {
            $year = date('Y');
            $leftyear = $year - 1;
            $data['deffrient'] = (clone $baseQuery)->whereYear('users.created_at', $leftyear)->count();
            $data['total'] = (clone $baseQuery)->whereYear('users.created_at', $year)->count();
        }

        return $data;
    }


    public function FTDCustomer(Request $request)
    {
        $data = [];
        $ids = getUsersIds();
        
        // Optimize by using subqueries instead of plucking large arrays
        $baseQuery = User::query()
            ->whereIn('users.id', $ids)
            ->whereIn('users.type_id', [2, 5])
            ->whereExists(function ($query) use ($ids) {
                $query->select(DB::raw(1))
                    ->from('info_trade_users')
                    ->whereColumn('info_trade_users.user_id', 'users.id')
                    ->where('info_trade_users.status_id', '<>', 4)
                    ->whereNotExists(function ($subQuery) {
                        $subQuery->select(DB::raw(1))
                            ->from('assign_user_managers')
                            ->whereColumn('assign_user_managers.user_id', 'info_trade_users.user_id')
                            ->where('assign_user_managers.admin_id', '<>', 0);
                    });
            });

        switch($request->type ?? null) {
            case 'day':
                $daily = Carbon::today();
                $leftdaily = Carbon::yesterday();
                $data['deffrient'] = (clone $baseQuery)->whereDate('users.depositedAcount', $leftdaily)->count();
                $data['total'] = (clone $baseQuery)->whereDate('users.depositedAcount', $daily)->count();
                break;

            case 'month':
                $month = date("m");
                $year = date("Y");
                $leftmonth = date('m', strtotime('-1 month'));
                $leftyear = date('Y', strtotime('-1 month'));
                $data['deffrient'] = (clone $baseQuery)->whereYear('users.depositedAcount', $leftyear)->whereMonth('users.depositedAcount', $leftmonth)->count();
                $data['total'] = (clone $baseQuery)->whereYear('users.depositedAcount', $year)->whereMonth('users.depositedAcount', $month)->count();
                break;

            case 'weekly':
                $start = now()->startOfWeek(Carbon::TUESDAY);
                $end = now()->endOfWeek(Carbon::MONDAY);
                $total = (clone $baseQuery)->whereBetween('users.depositedAcount', [$start, $end])->count();
                $data['deffrient'] = $total;
                $data['total'] = $total;
                break;

            case 'all':
                $total = (clone $baseQuery)->count();
                $data['deffrient'] = $total;
                $data['total'] = $total;
                break;

            default:
                $year = date('Y');
                $leftyear = $year - 1;
                $data['deffrient'] = (clone $baseQuery)->whereYear('users.depositedAcount', $leftyear)->count();
                $data['total'] = (clone $baseQuery)->whereYear('users.depositedAcount', $year)->count();
        }

        return $data;
    }


    public function kyc(Request $request){
        // Cache for 30 days per user
        $cacheKey = $this->getCacheKey('kyc', $request);
        
        $datas = Cache::remember($cacheKey, $this->cacheDuration, function() use ($request) {
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

            return [[
                'name'=>"Completed",
                'total'=>$data['complated'],
            ],[
                'name'=>"Pending",
                'total'=>$data['faild'],
                ]
            ];
        });
        
        $this->setData($datas);
        $this->setMessage("success");
        return $this->sendApiResonse();
    }

    public function LeadsChart(){
        // Cache for 30 days per user
        $cacheKey = $this->getCacheKey('leads_chart', null);
        
        $data = Cache::remember($cacheKey, $this->cacheDuration, function() {
            $ids = getUsersIds();
            $year = date('Y');
            
            // Optimize: Single query with GROUP BY instead of 12 separate queries
            $results = User::whereIn('id', $ids)
                ->where('type_id', 1)
                ->whereYear('created_at', $year)
                ->selectRaw('MONTH(created_at) as month, COUNT(*) as count')
                ->groupBy(DB::raw('MONTH(created_at)'))
                ->pluck('count', 'month');
            
            // Fill in missing months with 0 and prepare labels
            $data = [];
            $data['dataset'] = [];
            $data['labels'] = [];
            for($i = 1; $i <= 12; $i++){
                $data['labels'][] = "01-".$i.'-'.$year;
                $data['dataset'][] = $results->get($i, 0);
            }
            
            return $data;
        });
        
        $this->setData($data);
        $this->setMessage("success");
        return $this->sendApiResonse();
    }

    public function withdrawal(Request $request){
        // Cache for 30 days per user
        $cacheKey = $this->getCacheKey('withdrawal', $request);
        
        $data = Cache::remember($cacheKey, $this->cacheDuration, function() use ($request) {
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
                $percentDifference = $data['total'] > 0 ? 100 : 0;
                $data['deffrient_percent'] = $percentDifference;
            }

            $data['dataset']= $this->WithdrawalChart();
            
            return $data;
        });
        
        $this->setData($data);
        $this->setMessage("success");
        return $this->sendApiResonse();
    }

    public function deposite(Request $request){
        // Cache for 30 days per user
        $cacheKey = $this->getCacheKey('deposite', $request);
        
        $data = Cache::remember($cacheKey, $this->cacheDuration, function() use ($request) {
            $data = [];
        
        if(isset($request->type)) {
            switch($request->type) {
                case 'day':
                    $daily = Carbon::today();
                    $leftdaily = Carbon::yesterday();
                    
                    // Batch queries using conditional aggregation
                    $currentStats = Deposit::whereDate('created_at', $daily)
                        ->selectRaw('
                            SUM(amount) as total_amount,
                            SUM(CASE WHEN status = 1 THEN 1 ELSE 0 END) as completed,
                            SUM(CASE WHEN status = 0 THEN 1 ELSE 0 END) as pending,
                            SUM(CASE WHEN status = 2 THEN 1 ELSE 0 END) as failed
                        ')
                        ->first();
                    
                    $data['deffrient'] = Deposit::whereDate('created_at', $leftdaily)->sum("amount");
                    $data['total'] = $currentStats->total_amount ?? 0;
                    $data['completed'] = $currentStats->completed ?? 0;
                    $data['pending'] = $currentStats->pending ?? 0;
                    $data['failed'] = $currentStats->failed ?? 0;
                    break;

                case 'month':
                    $month = date("m");
                    $year = date("Y");
                    $leftmonth = date('m', strtotime('-1 month'));
                    $leftyear = date('Y', strtotime('-1 month'));
                    
                    // Batch queries
                    $currentStats = Deposit::whereYear('created_at', $year)
                        ->whereMonth('created_at', $month)
                        ->selectRaw('
                            SUM(amount) as total_amount,
                            SUM(CASE WHEN status = 1 THEN 1 ELSE 0 END) as completed,
                            SUM(CASE WHEN status = 0 THEN 1 ELSE 0 END) as pending,
                            SUM(CASE WHEN status = 2 THEN 1 ELSE 0 END) as failed
                        ')
                        ->first();
                    
                    $data['deffrient'] = Deposit::whereYear('created_at', $leftyear)
                        ->whereMonth('created_at', $leftmonth)
                        ->sum("amount");
                    $data['total'] = $currentStats->total_amount ?? 0;
                    $data['completed'] = $currentStats->completed ?? 0;
                    $data['pending'] = $currentStats->pending ?? 0;
                    $data['failed'] = $currentStats->failed ?? 0;
                    break;

                case 'weekly':
                    $start = now()->startOfWeek(Carbon::TUESDAY);
                    $end = now()->endOfWeek(Carbon::MONDAY);
                    
                    $stats = Deposit::whereBetween('created_at', [$start, $end])
                        ->selectRaw('
                            SUM(amount) as total_amount,
                            SUM(CASE WHEN status = 1 THEN 1 ELSE 0 END) as completed,
                            SUM(CASE WHEN status = 0 THEN 1 ELSE 0 END) as pending,
                            SUM(CASE WHEN status = 2 THEN 1 ELSE 0 END) as failed
                        ')
                        ->first();
                    
                    $data['deffrient'] = $stats->total_amount ?? 0;
                    $data['total'] = $stats->total_amount ?? 0;
                    $data['completed'] = $stats->completed ?? 0;
                    $data['pending'] = $stats->pending ?? 0;
                    $data['failed'] = $stats->failed ?? 0;
                    break;

                case 'all':
                    // Single query to get all stats at once
                    $stats = Deposit::selectRaw('
                        SUM(amount) as total_amount,
                        SUM(CASE WHEN status = 1 THEN 1 ELSE 0 END) as completed,
                        SUM(CASE WHEN status = 0 THEN 1 ELSE 0 END) as pending,
                        SUM(CASE WHEN status = 2 THEN 1 ELSE 0 END) as failed
                    ')
                    ->first();
                    
                    $data['deffrient'] = $stats->total_amount ?? 0;
                    $data['total'] = $stats->total_amount ?? 0;
                    $data['completed'] = $stats->completed ?? 0;
                    $data['pending'] = $stats->pending ?? 0;
                    $data['failed'] = $stats->failed ?? 0;
                    break;
                    
                case 'year':
                    $year = date('Y');
                    $leftyear = $year - 1;
                    
                    $currentStats = Deposit::whereYear('created_at', $year)
                        ->selectRaw('
                            SUM(amount) as total_amount,
                            SUM(CASE WHEN status = 1 THEN 1 ELSE 0 END) as completed,
                            SUM(CASE WHEN status = 0 THEN 1 ELSE 0 END) as pending,
                            SUM(CASE WHEN status = 2 THEN 1 ELSE 0 END) as failed
                        ')
                        ->first();
                    
                    $data['deffrient'] = Deposit::whereYear('created_at', $leftyear)->sum("amount");
                    $data['total'] = $currentStats->total_amount ?? 0;
                    $data['completed'] = $currentStats->completed ?? 0;
                    $data['pending'] = $currentStats->pending ?? 0;
                    $data['failed'] = $currentStats->failed ?? 0;
                    break;
            }
        } else {
            $year = date('Y');
            $leftyear = $year - 1;
            
            $currentStats = Deposit::whereYear('created_at', $year)
                ->selectRaw('
                    SUM(amount) as total_amount,
                    SUM(CASE WHEN status = 1 THEN 1 ELSE 0 END) as completed,
                    SUM(CASE WHEN status = 0 THEN 1 ELSE 0 END) as pending,
                    SUM(CASE WHEN status = 2 THEN 1 ELSE 0 END) as failed
                ')
                ->first();
            
            $data['deffrient'] = Deposit::whereYear('created_at', $leftyear)->sum("amount");
            $data['total'] = $currentStats->total_amount ?? 0;
            $data['completed'] = $currentStats->completed ?? 0;
            $data['pending'] = $currentStats->pending ?? 0;
            $data['failed'] = $currentStats->failed ?? 0;
        }

            if ($data['deffrient'] != 0) {
                $percentDifference = (($data['total'] - $data['deffrient']) / $data['deffrient']) * 100;
                $data['deffrient_percent'] = $percentDifference;
            } else {
                $percentDifference = $data['total'] > 0 ? 100 : 0;
                $data['deffrient_percent'] = $percentDifference;
            }
            
            $data['dataset'] = $this->depositeChart();
            return $data;
        });
        
        $this->setData($data);
        $this->setMessage("success");
        return $this->sendApiResonse();
    }
    
    
      public function depositeChart(){
        $ids = getUsersIds();
        $year = date('Y');
        
        // Optimize: Single query with GROUP BY instead of 12 separate queries
        $results = Deposit::whereIn('user_id', $ids)
            ->whereYear('created_at', $year)
            ->selectRaw('MONTH(created_at) as month, COUNT(*) as count')
            ->groupBy(DB::raw('MONTH(created_at)'))
            ->pluck('count', 'month');
        
        // Fill in missing months with 0
        $data = [];
        for($i = 1; $i <= 12; $i++){
            $data[] = $results->get($i, 0);
        }
        
        return $data;
    }
    public function WithdrawalChart(){
        $ids = getUsersIds();
        $year = date('Y');
        
        // Optimize: Single query with GROUP BY instead of 12 separate queries
        $results = Withdrawal::whereIn('user_id', $ids)
            ->whereYear('created_at', $year)
            ->selectRaw('MONTH(created_at) as month, COUNT(*) as count')
            ->groupBy(DB::raw('MONTH(created_at)'))
            ->pluck('count', 'month');
        
        // Fill in missing months with 0
        $data = [];
        for($i = 1; $i <= 12; $i++){
            $data[] = $results->get($i, 0);
        }
        
        return $data;
    }

    public function tradesChart()
    {
        // Cache for 30 days per user
        $cacheKey = $this->getCacheKey('trades_chart', null);
        
        $data = Cache::remember($cacheKey, $this->cacheDuration, function() {
            $ids = getUsersIds();
            $year = date('Y');
            
            // Optimize: Single query with GROUP BY instead of 12 separate queries
            $results = Trade::whereIn('user_id', $ids)
                ->whereYear('created_at', $year)
                ->selectRaw('MONTH(created_at) as month, COUNT(*) as count')
                ->groupBy(DB::raw('MONTH(created_at)'))
                ->pluck('count', 'month');
            
            // Fill in missing months with 0
            $data = [];
            $data['dataset'] = [];
            for($i = 1; $i <= 12; $i++){
                $data['dataset'][] = $results->get($i, 0);
            }
            
            return $data;
        });
        
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
