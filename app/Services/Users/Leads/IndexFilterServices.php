<?php

namespace App\Services\Users\Leads;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\InfoTradeUser;
use App\Models\AssignUserManager;
use App\Http\Resources\Admin\User\UsersResource;
use Carbon\Carbon;
use Log;
class IndexFilterServices extends Controller
{
    public $userFilters = [];
    public $tradeFilters = [];
    public $managerIds = null;
    public $sortBy = 'id';
    public $sortDirection = 'desc';
    public $dateFilters = [];
    
    public function index(Request $request)
    {
        $this->processSearchRequest($request);
       
        switch($request->id):
            case 0:
                return $this->leads($request); 
            case 1:
                return $this->potinal($request);    
            case 2:
                return $this->Active($request);    
            case 5:
                return $this->publicCustomer($request);    
            case 9:
                return $this->ArchiveCustomer($request);    
            default:
                return $this->FTD($request);  
        endswitch;
    }
    
    public function leads($request)
    {
        
        
        
        $users = User::leadsFilter($this->userFilters, $this->tradeFilters, $this->managerIds)
                    ->with(['Manager', 'userInfo', 'countries'])
                    ->customSort($this->sortBy, $this->sortDirection)
                    ->applyDateFilters($this->dateFilters)
                    ->paginate($request->per_page);
                    
        
        return $this->formatResponse($users);
    }
    
    public function Active($request)
    {
        $users = User::activeCustomers($this->userFilters, $this->tradeFilters, $this->managerIds)
                    ->with(['Manager', 'userInfo', 'countries'])
                    ->customSort($this->sortBy, $this->sortDirection)
                    ->paginate($request->per_page);
        
        return $this->formatResponse($users);
    }
    
    public function potinal($request)
    {
        $users = User::potentialCustomers($this->userFilters, $this->tradeFilters, $this->managerIds)
                    ->with(['Manager', 'userInfo', 'countries'])
                    ->customSort($this->sortBy, $this->sortDirection)
                    ->paginate($request->per_page);
        
        return $this->formatResponse($users);
    }
    
    public function ArchiveCustomer($request)
    {
        $users = User::archiveCustomers($this->userFilters, $this->tradeFilters, $this->managerIds)
                    ->with(['Manager', 'userInfo', 'countries'])
                    ->customSort($this->sortBy, $this->sortDirection)
                    ->paginate($request->per_page);
        
        return $this->formatResponse($users);
    }
    
    public function FTD($request)
    {
        $users = User::ftdCustomers($this->userFilters, $this->tradeFilters, $this->managerIds)
                    ->with(['Manager', 'userInfo', 'countries'])
                    ->customSort($this->sortBy, $this->sortDirection)
                    ->paginate($request->per_page);
        
        return $this->formatResponse($users);
    }
    
    public function publicCustomer($request)
    {
        $users = User::publicCustomers($this->userFilters, $this->tradeFilters, $this->managerIds)
                    ->with(['Manager', 'userInfo', 'countries'])
                    ->customSort($this->sortBy, $this->sortDirection)
                    ->paginate($request->per_page);
        
        return $this->formatResponse($users);
    }
    
    /**
     * Process search request and categorize filters
     */
    // public function processSearchRequest($request)
    // {
    //     $userFilters = [];
    //     $tradeFilters = [];
    //     $managerIds = null;
        
    //     // Handle search filters
    //     if($request->search && count($request->search) > 0) {
    //         foreach($request->search as $req) {
    //             if($req['value'] != 0) {
    //                 // User table fields
    //                 if(in_array($req['key'], ['no_of_logins', 'block', 'manager_id', 'country', 'email', 'name'])) {
    //                     if($req['key'] == 'manager_id' && $req['value'] != 0) {
    //                         $managerIds = AssignUserManager::where('admin_id', $req['value'])->pluck('user_id')->toArray();
    //                         if(empty($managerIds)) {
    //                             $managerIds = [0]; // No results                     
    //                         }
    //                     } else {
    //                         $userFilters[] = [$req['key'], $req['value']];   
    //                     }
    //                 } else {
    //                     // InfoTradeUser table fields
    //                     $tradeFilters[] = [$req['key'], $req['value']];
    //                 }
    //             } else {
    //                 // Handle case where manager_id is 0 (unassigned)
    //                 $assignedUserIds = AssignUserManager::select('user_id')->pluck('user_id')->toArray();
    //                 $managerIds = User::whereNotIn('id', $assignedUserIds)->pluck('id')->toArray();
    //             }   
    //         }
    //     }

    //     // Handle sorting
    //     $sortBy = $request->sort_by ?? 'id';
    //     $sortDirection = strtolower($request->sort_direction ?? 'desc');
        
    //     // Validate sort parameters
    //     $allowedSortColumns = [
    //         'id', 'surname', 'email', 'phone', 'balance', 'last_comment',
    //         'country', 'source', 'plan', 'created_at', 'updated_at',
    //         'status_id', 'manager_id', 'agent', 'no_of_logins', 'block', 'depositedAcount'
    //     ];

    //     if(!in_array($sortBy, $allowedSortColumns)) {
    //         $sortBy = 'id';
    //     }

    //     if(!in_array($sortDirection, ['asc', 'desc'])) {
    //         $sortDirection = 'desc';
    //     }

    //     $this->userFilters = $userFilters;
    //     $this->tradeFilters = $tradeFilters;
    //     $this->managerIds = $managerIds;
    //     $this->sortBy = $sortBy;
    //     $this->sortDirection = $sortDirection;
    // }
    
       public function processSearchRequest($request)
    {
        $userFilters = [];
        $tradeFilters = [];
        $dateFilters = [];
        $managerIds = null;
        
        // Handle search filters
        if($request->search && count($request->search) > 0) {
            foreach($request->search as $req) {
                if($req['value'] != 0) {
                    // User table fields
                    if(in_array($req['key'], ['no_of_logins', 'block', 'manager_id', 'country', 'email', 'name'])) {
                        if($req['key'] == 'manager_id' && $req['value'] != 0) {
                            $managerIds = AssignUserManager::whereIn('admin_id', $this->explodedata($req['value']))->pluck('user_id')->toArray();
                            if(empty($managerIds)) {
                                $managerIds = [0]; // No results                     
                            }
                        } else {
                            $userFilters[] = [$req['key'], $req['value']];   
                        }
                    } else {
                        // InfoTradeUser table fields
                        $tradeFilters[] = [$req['key'], $this->explodedata($req['value'])];
                    }
                } else {
                    // Handle case where manager_id is 0 (unassigned)
                    $assignedUserIds = AssignUserManager::select('user_id')->pluck('user_id')->toArray();
                    $managerIds = User::whereNotIn('id', $assignedUserIds)->pluck('id')->toArray();
                }   
            }
        }

        // Handle date filters
        $dateFilters = $this->processDateFilters($request);

        // Handle sorting
        $sortBy = $request->sort_by ?? 'id';
        $sortDirection = strtolower($request->sort_direction ?? 'desc');
        
        // Validate sort parameters
        $allowedSortColumns = [
            'id', 'surname', 'email', 'phone', 'balance', 'last_comment',
            'country', 'source', 'plan', 'created_at', 'updated_at',
            'status_id', 'manager_id', 'agent', 'no_of_logins', 'block', 'depositedAcount'
        ];

        if(!in_array($sortBy, $allowedSortColumns)) {
            $sortBy = 'id';
        }

        if(!in_array($sortDirection, ['asc', 'desc'])) {
            $sortDirection = 'desc';
        }

        $this->userFilters = $userFilters;
        $this->tradeFilters = $tradeFilters;
        $this->dateFilters = $dateFilters;
        $this->managerIds = $managerIds;
        $this->sortBy = $sortBy;
        $this->sortDirection = $sortDirection;
    }
    
    /**
     * Process date filters from request
     */
    private function processDateFilters($request)
    {
        $dateFilters = [];
        
        // Handle date filters from frontend
        if ($request->has('dateFilters')) {
            $dateFilterData = $request->dateFilters;
            
            if (isset($dateFilterData['dateField']) && !empty($dateFilterData['dateField'])) {
                $dateField = $dateFilterData['dateField'];
                $startDate = $dateFilterData['startDate'] ?? null;
                $endDate = $dateFilterData['endDate'] ?? null;
                $preset = $dateFilterData['preset'] ?? null;
                
                // If preset is provided, calculate the date range
                if ($preset && $preset !== 'custom') {
                    $dateRange = $this->getDatePresetRange($preset);
                    $startDate = $dateRange['startDate'];
                    $endDate = $dateRange['endDate'];
                }
                
                // Validate and format dates
                if ($startDate || $endDate) {
                    $dateFilters = [
                        'field' => $dateField,
                        'start_date' => $startDate ? Carbon::parse($startDate)->startOfDay() : null,
                        'end_date' => $endDate ? Carbon::parse($endDate)->endOfDay() : null,
                        'preset' => $preset
                    ];
                }
            }
        }
        
        // Legacy support for individual date parameters
        if (empty($dateFilters)) {
            if ($request->has('date_field') && ($request->has('start_date') || $request->has('end_date'))) {
                $dateFilters = [
                    'field' => $request->date_field,
                    'start_date' => $request->start_date ? Carbon::parse($request->start_date)->startOfDay() : null,
                    'end_date' => $request->end_date ? Carbon::parse($request->end_date)->endOfDay() : null,
                    'preset' => 'custom'
                ];
            }
        }
        
        return $dateFilters;
    }
    
    /**
     * Get date range for preset values
     */
    private function getDatePresetRange($preset)
    {
        $now = Carbon::now();
        $today = Carbon::today();
        
        switch ($preset) {
            case 'today':
                return [
                    'startDate' => $today->toDateString(),
                    'endDate' => $today->toDateString()
                ];
                
            case 'yesterday':
                $yesterday = Carbon::yesterday();
                return [
                    'startDate' => $yesterday->toDateString(),
                    'endDate' => $yesterday->toDateString()
                ];
                
            case 'this_week':
                return [
                    'startDate' => $now->copy()->startOfWeek()->toDateString(),
                    'endDate' => $today->toDateString()
                ];
                
            case 'last_week':
                $lastWeekStart = $now->copy()->subWeek()->startOfWeek();
                $lastWeekEnd = $now->copy()->subWeek()->endOfWeek();
                return [
                    'startDate' => $lastWeekStart->toDateString(),
                    'endDate' => $lastWeekEnd->toDateString()
                ];
                
            case 'this_month':
                return [
                    'startDate' => $now->copy()->startOfMonth()->toDateString(),
                    'endDate' => $today->toDateString()
                ];
                
            case 'last_month':
                $lastMonthStart = $now->copy()->subMonth()->startOfMonth();
                $lastMonthEnd = $now->copy()->subMonth()->endOfMonth();
                return [
                    'startDate' => $lastMonthStart->toDateString(),
                    'endDate' => $lastMonthEnd->toDateString()
                ];
                
            case 'this_quarter':
                return [
                    'startDate' => $now->copy()->startOfQuarter()->toDateString(),
                    'endDate' => $today->toDateString()
                ];
                
            case 'last_quarter':
                $lastQuarterStart = $now->copy()->subQuarter()->startOfQuarter();
                $lastQuarterEnd = $now->copy()->subQuarter()->endOfQuarter();
                return [
                    'startDate' => $lastQuarterStart->toDateString(),
                    'endDate' => $lastQuarterEnd->toDateString()
                ];
                
            case 'this_year':
                return [
                    'startDate' => $now->copy()->startOfYear()->toDateString(),
                    'endDate' => $today->toDateString()
                ];
                
            case 'last_year':
                $lastYearStart = $now->copy()->subYear()->startOfYear();
                $lastYearEnd = $now->copy()->subYear()->endOfYear();
                return [
                    'startDate' => $lastYearStart->toDateString(),
                    'endDate' => $lastYearEnd->toDateString()
                ];
                
            case 'last_7_days':
                return [
                    'startDate' => $now->copy()->subDays(7)->toDateString(),
                    'endDate' => $today->toDateString()
                ];
                
            case 'last_30_days':
                return [
                    'startDate' => $now->copy()->subDays(30)->toDateString(),
                    'endDate' => $today->toDateString()
                ];
                
            case 'last_90_days':
                return [
                    'startDate' => $now->copy()->subDays(90)->toDateString(),
                    'endDate' => $today->toDateString()
                ];
                
            default:
                return ['startDate' => null, 'endDate' => null];
        }
    }
    /**
     * Format the paginated response
     */
    private function formatResponse($users) 
    {
        return [
            'data' => UsersResource::make($users->items()),
            'total' => $users->total(),
            'per_page' => $users->perPage(),
            'current_page' => $users->currentPage(),
            'last_page' => $users->lastPage(),
            'from' => $users->firstItem(),
            'to' => $users->lastItem(),
            'links' => [],
        ];
    }
    
    
    public function explodedata($value){
        
        
    //   if (is_array($value)) {
    //         $value = $value[0]; // لو جالك ["3,1"]
    // }

        $items = explode(',', (string) $value);
    
        // تنظيف القيم وتحويلها إلى أرقام صحيحة
        return array_map('intval', $items);
    }
    
}



