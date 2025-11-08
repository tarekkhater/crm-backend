<?php

namespace App\Exports;

use App\Models\User;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\Exportable;
use App\Models\InfoTradeUser;
use App\Models\AssignUserManager;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class UsersClientExport implements FromCollection, WithHeadings, WithTitle
{
    use Exportable;

    protected $type;
    protected $selectedColumns;
    protected $filters;
    protected $userFilters = [];
    protected $tradeFilters = [];
    protected $managerIds = null;
    protected $sortBy = 'id';
    protected $sortDirection = 'desc';
    protected $dateFilters = [];
    
    protected $allColumns = [
        'id', 'surname', 'email', 'source', 'status_id', 'manager_id', 
        'balance', 'phone', 'last_comment', 'last_comment_content', 
        'plan', 'country', 'campaign', 'agent', 'created_at', 'category'
    ];

    public function __construct($type, $selectedColumns = null, $filters = [])
    {
        ini_set('memory_limit', '512M');
        $this->type = (int)$type;
        $this->selectedColumns = $selectedColumns ?? $this->allColumns;
        $this->filters = $filters;
        $this->processFilters(); // معالجة الفلاتر
    }
    
    // Filter users based on the type
    public function collection()
    {
        
        switch ($this->type) {
            case 0:
                return $this->leads(); 
            case 10:
                return $this->leadsCenter(); 
            case 1:
                return $this->potinal();    
            case 2:
                return $this->Active();    
            case 5:
                return $this->publicCustomer();    
            case 9:
                return $this->ArchiveCustomer();    
            default:
                return $this->FTD();  
        }
    }

    /**
     * Define the headings for the export.
     *
     * @return array
     */
    public function headings(): array
    {
        // رجع headings للأعمدة المختارة بس
        $headingsMap = [
            'id' => 'ID',
            'surname' => 'Name',
            'email' => 'Email',
            'source' => 'Source',
            'status_id' => 'Status',
            'manager_id' => 'Manager',
            'balance' => 'Balance',
            'phone' => 'Phone',
            'last_comment' => 'Last Comment Date',
            'last_comment_content' => 'Last Comment',
            'plan' => 'Plan',
            'country' => 'Country',
            'campaign' => 'Campaign',
            'agent' => 'Agent',
            'created_at' => 'Created At',
            'category' => 'Category'
        ];
        
        $headings = [];
        foreach ($this->selectedColumns as $column) {
            $headings[] = $headingsMap[$column] ?? ucfirst($column);
        }
        
        return $headings;
    }

    public function title(): string
    {
        // Return the sheet title based on the type
        $typeTitles = [
            0 => 'leads',
            1 => 'potinal',
            2 => 'Active',
            4 => 'FTD',
            5 => 'publicCustomer',
            9 => 'ArchiveCustomer',
            10 => 'leadsCenter',
        ];

        return $typeTitles[(int)$this->type] ?? 'Users';
    }

    // Method to get leads (استخدام نفس الـ scope زي IndexFilterServices مع الفلاتر)
    public function leads()
    {
        
        
        // استخدم نفس الـ scope مع الفلاتر المطبقة
        $query = User::leadsFilter($this->userFilters, $this->tradeFilters, $this->managerIds)
                    ->customSort($this->sortBy, $this->sortDirection)
                    ->applyDateFilters($this->dateFilters);
        
        // حمل الـ relationships المطلوبة
        $with = $this->getRequiredRelationships();
        
        $users = $query->with($with)->take(10000)->get();


        return $this->transformUsers($users);
    }

    // Method to get leads from center
    public function leadsCenter()
    {
        // نفس منطق leads
        return $this->leads();
    }

    // Method to get active users (استخدام activeCustomers scope مع الفلاتر)
    public function Active()
    {
        $query = User::activeCustomers($this->userFilters, $this->tradeFilters, $this->managerIds)
                    ->customSort($this->sortBy, $this->sortDirection)
                    ->applyDateFilters($this->dateFilters);
        
        $with = $this->getRequiredRelationships();
        
        $users = $query->with($with)->take(10000)->get();


        return $this->transformUsers($users);
    }

    // Method to get potential users (استخدام potentialCustomers scope مع الفلاتر)
    public function potinal()
    {
        $query = User::potentialCustomers($this->userFilters, $this->tradeFilters, $this->managerIds)
                    ->customSort($this->sortBy, $this->sortDirection)
                    ->applyDateFilters($this->dateFilters);
        
        $with = $this->getRequiredRelationships();
        
        $users = $query->with($with)->take(10000)->get();


        return $this->transformUsers($users);
    }

    // Method to get archived users (استخدام archiveCustomers scope مع الفلاتر)
    public function ArchiveCustomer()
    {
        $query = User::archiveCustomers($this->userFilters, $this->tradeFilters, $this->managerIds)
                    ->customSort($this->sortBy, $this->sortDirection)
                    ->applyDateFilters($this->dateFilters);
        
        $with = $this->getRequiredRelationships();
        
        $users = $query->with($with)->take(10000)->get();


        return $this->transformUsers($users);
    }

    // Method to get FTD users (استخدام ftdCustomers scope مع الفلاتر)
    public function FTD()
    {
        $query = User::ftdCustomers($this->userFilters, $this->tradeFilters, $this->managerIds)
                    ->customSort($this->sortBy, $this->sortDirection)
                    ->applyDateFilters($this->dateFilters);
        
        $with = $this->getRequiredRelationships();
        
        $users = $query->with($with)->take(10000)->get();


        return $this->transformUsers($users);
    }

    // Method to get public customers (استخدام publicCustomers scope مع الفلاتر)
    public function publicCustomer()
    {
        $query = User::publicCustomers($this->userFilters, $this->tradeFilters, $this->managerIds)
                    ->customSort($this->sortBy, $this->sortDirection)
                    ->applyDateFilters($this->dateFilters);
        
        $with = $this->getRequiredRelationships();
        
        $users = $query->with($with)->take(10000)->get();


        return $this->transformUsers($users);
    }

    // Helper method to transform user data
    private function transformUsers($users)
    {
        $data = [];
        $processedCount = 0;
        
        foreach ($users as $user) {
            $row = [];
            
            // بناء الـ row بناءً على الأعمدة المختارة
            foreach ($this->selectedColumns as $column) {
                $row[$column] = $this->getColumnValue($user, $column);
            }
            
            $data[] = $row;
            $processedCount++;
            
            // تنظيف الـ memory كل 1000 row
            if ($processedCount % 1000 == 0) {
                gc_collect_cycles();
            }
        }
        
        
        return collect($data);
    }
    
    /**
     * حدد الـ relationships المطلوبة بناءً على الأعمدة المختارة
     */
    private function getRequiredRelationships()
    {
        $relationships = [];
        $userInfoNested = [];
        $needsUserInfo = false;
        
        foreach ($this->selectedColumns as $column) {
            switch ($column) {
                case 'source':
                    $userInfoNested[] = 'source';
                    $needsUserInfo = true;
                    break;
                
                case 'status_id':
                    $userInfoNested[] = 'status';
                    $needsUserInfo = true;
                    break;
                
                case 'plan':
                    $userInfoNested[] = 'plan';
                    $needsUserInfo = true;
                    break;
                
                case 'campaign':
                    $userInfoNested[] = 'campaign';
                    $needsUserInfo = true;
                    $relationships[] = 'sourcerecord'; // للـ fallback
                    break;
                
                case 'manager_id':
                case 'agent':
                case 'category':
                    $relationships[] = 'Manager.manager.desk';
                    break;
                
                case 'country':
                case 'phone':
                    $relationships[] = 'countries';
                    break;
                
                case 'last_comment':
                case 'last_comment_content':
                    $relationships[] = 'latestAgentNote';
                    break;
                
                case 'balance':
                    $needsUserInfo = true;
                    break;
            }
        }
        
        // بناء الـ relationships array بالطريقة الصحيحة لـ Laravel
        $result = [];
        
        // إضافة userInfo مع nested relationships
        if ($needsUserInfo) {
            if (!empty($userInfoNested)) {
                $nested = array_unique($userInfoNested);
                // استخدام closure للـ nested relationships
                $result['userInfo'] = function($query) use ($nested) {
                    $query->with($nested);
                };
            } else {
                $result[] = 'userInfo';
            }
        }
        
        // إضافة باقي الـ relationships
        foreach ($relationships as $rel) {
            $result[] = $rel;
        }
        
        return $result;
    }
    
    /**
     * جيب قيمة العمود للـ user (بنفس طريقة UsersResource)
     */
    private function getColumnValue($user, $column)
    {
        try {
            switch ($column) {
                case 'id':
                    return $user->id;
                
                case 'surname':
                    return trim($user->name . ' ' . ($user->surname ?? ''));
                
                case 'email':
                    return $user->email ?? '-';
                
                case 'source_id':
                    // source من userInfo->source (مثل UserResource)
                    return $user->userInfo?->source?->name ?? 'No Source';
                
                case 'status_id':
                    return $user->userInfo?->status?->name ?? '-';
                
                case 'manager_id':
                    $manager = $user->Manager?->manager;
                    if ($manager) {
                        return trim(($manager->name ?? '') . ' ' . ($manager->surname ?? ''));
                    }
                    return 'not assign';
                
                case 'balance':
                    $money = $user->userInfo?->money ?? 0;
                    $balance = $user->userInfo?->balance ?? 0;
                    return ($money + $balance);
                
                case 'phone':
                    $phone = $user->phone ?? '';
                    if ($phone && strpos($phone, '+') !== 0) {
                        return '+' . $phone;
                    }
                    return $phone ?: '-';
                
                case 'last_comment':
                    return $user->last_agent_note_date ?? '-';
                
                case 'last_comment_content':
                    return $user->last_agent_note_content ?? '-';
                
                case 'plan_id':
                    // plan من userInfo->plan (مثل UserResource)
                    return $user->userInfo?->plan?->name ?? 'No Plan';
                
                case 'country':
                    return $user->countries?->name ?? '-';
                
                case 'campaign_id':
                    // campaign في UserResource بيجيب من User->source مباشر
                    // لكن ممكن يكون في userInfo->campaign كمان
                    // نجرب userInfo->campaign الأول، لو مش موجود ناخد User->sourcerecord
                    $campaign = $user->userInfo?->campaign?->name;
                    if (!$campaign) {
                        $campaign = $user->sourcerecord?->name;
                    }
                    return $campaign ?? 'No Campaign';
                
                case 'agent_id':
                    $manager = $user->Manager?->manager;
                    if ($manager) {
                        return trim(($manager->name ?? '') . ' ' . ($manager->surname ?? ''));
                    }
                    return 'not assign';
                
                case 'created_at':
                    return $user->created_at ? $user->created_at->format('Y-m-d H:i:s') : '-';
                
                case 'category_id':
                    $manager = $user->Manager?->manager;
                    if (!$manager) {
                        return 'not assign';
                    }
                    
                    $desk = $manager->desk?->name ?? '';
                    $deskName = $desk ? " $desk" : '';
                    
                    if (in_array($manager->type_id ?? 0, [7, 8])) {
                        return $manager->type_id == 7 ? "Conversion{$deskName}" : "Retention{$deskName}";
                    } elseif (in_array($manager->sub_type_id ?? 0, [7, 8])) {
                        return $manager->sub_type_id == 7 ? "Conversion{$deskName}" : "Retention{$deskName}";
                    }
                    
                    return 'not assign';
                
                default:
                    return '-';
            }
        } catch (\Exception $e) {
            // لو حصل أي error، ارجع قيمة default
            Log::warning("Error getting column value for {$column}: " . $e->getMessage());
            return '-';
        }
    }
    
    /**
     * معالجة الفلاتر من الـ Request (نفس منطق IndexFilterServices)
     */
    private function processFilters()
    {
        $userFilters = [];
        $tradeFilters = [];
        $dateFilters = [];
        $managerIds = null;
        
        // معالجة فلاتر البحث
        if (isset($this->filters['search']) && is_array($this->filters['search']) && count($this->filters['search']) > 0) {
            foreach ($this->filters['search'] as $req) {
                if (isset($req['value']) && $req['value'] != 0) {
                    // فلاتر جدول Users
                    if (in_array($req['key'], ['no_of_logins', 'block', 'manager_id', 'country', 'email', 'name', 'campaign'])) {
                        if ($req['key'] == 'manager_id' && $req['value'] != 0) {
                            $managerIds = AssignUserManager::whereIn('admin_id', $this->explodedata($req['value']))
                                ->pluck('user_id')
                                ->toArray();
                            if (empty($managerIds)) {
                                $managerIds = [0];
                            }
                        } else {
                            $userFilters[] = [$req['key'], $req['value']];   
                        }
                    } else {
                        // فلاتر جدول InfoTradeUser
                        $tradeFilters[] = [$req['key'], $this->explodedata($req['value'])];
                    }
                } else {
                    // معالجة manager_id = 0 (unassigned)
                    if (isset($req['key']) && $req['key'] == 'manager_id') {
                        $assignedUserIds = AssignUserManager::select('user_id')->pluck('user_id')->toArray();
                        $managerIds = User::whereNotIn('id', $assignedUserIds)->pluck('id')->toArray();
                    }
                }   
            }
        }

        // معالجة فلاتر التاريخ
        if (isset($this->filters['dateFilters']) && is_array($this->filters['dateFilters'])) {
            $dateFilterData = $this->filters['dateFilters'];
            
            // تأكد من وجود dateField وإنه مش فاضي
            if (!empty($dateFilterData['dateField'])) {
                $startDate = !empty($dateFilterData['startDate']) ? $dateFilterData['startDate'] : null;
                $endDate = !empty($dateFilterData['endDate']) ? $dateFilterData['endDate'] : null;
                
                // لو فيه على الأقل تاريخ واحد
                if ($startDate || $endDate) {
                    $dateFilters = [
                        'field' => $dateFilterData['dateField'],
                        'start_date' => $startDate ? \Carbon\Carbon::parse($startDate)->startOfDay() : null,
                        'end_date' => $endDate ? \Carbon\Carbon::parse($endDate)->endOfDay() : null,
                    ];
                }
            }
        }

        // معالجة الترتيب
        $sortBy = $this->filters['sort_by'] ?? 'id';
        $sortDirection = strtolower($this->filters['sort_direction'] ?? 'desc');

        $this->userFilters = $userFilters;
        $this->tradeFilters = $tradeFilters;
        $this->dateFilters = $dateFilters;
        $this->managerIds = $managerIds;
        $this->sortBy = $sortBy;
        $this->sortDirection = $sortDirection;
        
        
    }
    
    /**
     * تحويل القيم المفصولة بفواصل لـ array
     */
    private function explodedata($value)
    {
        if (is_array($value)) {
            return $value;
        }
        
        $items = explode(',', (string)$value);
        return array_map('intval', $items);
    }
}
