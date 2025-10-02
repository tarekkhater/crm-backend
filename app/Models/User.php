<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Tymon\JWTAuth\Contracts\JWTSubject;
use Yadahan\AuthenticationLog\AuthenticationLogable;
// use Laratrust\Traits\DynamicUserRelationshipCalls;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Traits\HasRoles;
use App\Scopes\UserScope;
    use Illuminate\Database\Eloquent\Builder;
    use Log;

class User extends Authenticatable implements MustVerifyEmail, JWTSubject
{
    // use DynamicUserRelationshipCalls;
    use HasFactory, Notifiable;
    use Notifiable;
      use HasRoles;
    // use Impersonate;


   use SoftDeletes;
    // protected static function booted()
    // {
    //     // Apply the ActiveStatusScope globally to this model
    //     // static::addGlobalScope(new UserScope);
    // }
    protected $dates = ['deleted_at'];

    protected $fillable = [
        'source',
        'campaign',
        'email',
        'password',
        'btc','phone',
        'new_password',
        'avatar',
        'is_active',
        'type_account',
        'city',
        'allow_trade_after_hours',
        'name','surname',
        'pass','cur',
        'withdrawable',
        'can_withdraw',
        'can_upgrade','msg',
        'balance',
        'bonus',
        'source',
        'status',
        'email_verified_at',
        'allow_trade','offer_name',
        'type_id',
        'trader_request',
        'manager_id','webhook',
        'can_trade','plan_id',
        'fee','profit','about',"created_by",
        'birth','currency',
        'country', 'address', 'permanent_address', 'postal', 'dob','first_name','last_name','account_officer','phone_code',
        'plan','can_add_fund','google2fa_secret','can_refer',"last_seen" , "archive_customer?"
    ];

    protected $appends = ['cur_sym','online','last_agent_note_date','last_agent_note_content'];

    protected $hidden = [
        'password',
        'remember_token',
        'admin_notifications'
    ];

    protected $with = ['typeUser','identity','broker'];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'can_trade' => 'integer',
        'balance' => 'float',
        'admin_notifications' => 'array',
    ];
    
    

    public function getAdminNotificationsAttribute() {
        return json_decode($this->attributes['admin_notifications']) ?? [];
    }

    public function totalProfit(){
        return Trade::whereUserId($this->id)->sum('profit');
    }

    public function userInfo(){
        return $this->hasOne(InfoTradeUser::class,'user_id');
    }

    public function trades(){
        return $this->hasMany(Trade::class);
    }


    public function countries(){
        return $this->belongsTo(Country::class,'country');
    }

    // public function manager(){
    //     return $this->belongsTo(User::class,'manager_id');
    // }

    public function watchlists () {
        return $this->hasMany(Watchlist::class);
    }

    public function notes () {
        return $this->hasMany(Note::class)->orderByDesc('created_at');
    }

    public function statusrecord () {
        return $this->belongsTo(Status::class, 'status');
    }

    public function sourcerecord () {
        return $this->belongsTo(Source::class, 'source');
    }

    public function getPlanAtrribute(){
        return 'Starter';
    }

    public function getPhoneAtrribute(){
        return $this->phone_code.$this->phone;
    }

   /* public function getOnlineAttribute(){
        if($this->last_seen >= Carbon::now('Europe/London')->format('Y-m-d H:i:s')){
            return 1;
        }else{
            return 0;
        }
    } */

    public function getOnlineAttribute(){
        if($this->session_id !== "NULL" && $this->no_of_logins > 0){
            return 1;
        }else{
            return 0;
        }
    }


    public function getCurSymAttribute(){
        return $this->currency;
        if($this->currency){
            return $this->currency->sign;
        }else{
            return 0;
        }
    }

    public function deposits(){
        return $this->hasMany(Deposit::class,'user_id');
    }

    public function canImpersonate() : bool
    {
        // example
        if($this->hasRole(['admin','superadmin'])){
            return true;
        }else{
            return false;
        }
    }

    public function canBeImpersonated() : bool
    {
        // example
        if($this->hasRole(['user','manager', 'retention'])){
            return true;
        }else {
            return false;
        }
    }


//    public function plan(){
//
//        return $this->hasOne(Package::class,'id','plan_id');
//
//    }

    public function invested(){
        return  UserPlan::whereUserId($this->id)->sum('amount');
    }

    public function invEarning(){
        return  UserPlan::whereUserId($this->id)->whereStatus(2)->sum('earned');
    }

    public function lockedInvFund(){
        return  UserPlan::whereUserId($this->id)->whereStatus(1)->sum('amount');
    }

    public function activeInvested(){
        return  InvProfit::whereUserId($this->id)->whereStatus(1)->sum('amount');
    }

    public function activeInvestedProfit(){
        return  InvProfit::whereUserId($this->id)->whereStatus(1)->sum('profit');
    }

    public function pendingInvested(){
        return  UserPlan::whereUserId($this->id)->whereStatus(0)->sum('amount');
    }

    public function totalDeposit(){
        return  Deposit::whereUserId($this->id)->sum('amount');
    }



    public function withdrawals(){
        return '$'. Withdrawal::whereUserId($this->id)->whereApproved(1)->sum('amount');
    }



    public function balance(){
        return $this->userInfo->balance;
    }
    public function profit(){
        return $this->userInfo->balance;
    }

    public function aBalance(){
        return $this->userInfo->balance - $this->userInfo->bonus - $this->userInfo->pnl;
    }

    public function total(){
        return $this->withdrawable + $this->userInfo->balance . ' USD';
    }

    public function bonus(){
        return $this->userInfo->bonus . ' USD';
    }


    public function getAvatarAttribute($value) {
        if(!$this->attributes['avatar']) {
            $colors = ['E91E63', '9C27B0', '673AB7', '3F51B5', '0D47A1', '01579B', '00BCD4', '009688', '33691E', '1B5E20', '33691E', '827717', 'E65100',  'E65100', '3E2723', 'F44336', '212121'];
            $background = "FF7700";
            return "https://ui-avatars.com/api/?size=256&background=".$background."&color=fff&name=".urlencode($this->name);
        }

        return asset($this->attributes['avatar']);
    }

    public function getBalanceAttribute($bal) {
        if(setting('separate_bonus', 'no') == 'yes'){
            return $bal;
        }else{
            return $bal + $this->userInfo->bonus + $this->userInfo->pnl;

        }
    }


    // AMR


    public function favourite(){
         return $this->hasMany(Favourite::class, 'user_id');
    }
    public function Active(){
        return $this->hasMany(ActiveUser::class, 'user_id')->where('type','=', '2');;
    }
    public function AgentUser(){
        return $this->hasOne(AgentUser::class, 'user_id');
    }

    public function AgentNotes(){
        return $this->hasOne(AgentNotes::class, 'user_id');
    }
    
    public function getLastAgentNoteDateAttribute()
    {
        // Get the latest note's date or null if none exists
        $date = $this->agentNotes()
                     ->orderBy('created_at', 'desc')
                     ->value('created_at');

        if ($date) {
            // Parse and format the date with Carbon
            return Carbon::parse($date)->format('d M Y, H:i'); // Example: 03 Sep 2025, 14:30
        }

        return null;

    }
    
    public function getLastAgentNoteContentAttribute()
    {
        // Get the latest note's date or null if none exists
        $date = $this->agentNotes()
                     ->orderBy('created_at', 'desc')
                     ->value('content');

        if ($date) {
            return $date;
        }

        return null;

    }

     public function broker(){
        return $this->belongsTo(Admin::class, 'broker_id');
    }

    public function IBClient(){
        return $this->hasOne(IBClient::class, 'user_id');
    }

    public function IBRequest(){
        return $this->hasOne(IBRequest::class, 'user_id');
    }

    public function Manager(){
        return $this->hasOne(AssignUserManager::class, 'user_id');
    }
    public function TradingAccount(){
        return $this->hasOne(TradingAccount::class, 'user_id');
    }
    public function Payments(){
        return $this->hasMany(AccountBankUser::class, 'user_id');
    }
    public function myWithdrawals() {
        return $this->hasMany(Withdrawal::class, 'user_id');
    }

    public function typeUser(){
        return $this->belongsTo(TypeUser::class,'type_id','id');
    }


    public function plans(){
        return $this->hasMany(UserPlan::class,'user_id');
    }


    public function activePlans(){
        return $this->hasMany(InvProfit::class,'user_id');
    }

    // public function accounts(){
    //     return $this->hasMany(Account::class,'user_id');
    // }

    public function wireAccounts(){
        return $this->hasMany(WireAccount::class,'user_id');
    }

    public function messages(){
        return $this->hasMany(Message::class);
    }

    public function identity(){

        return $this->hasMany(Identity::class);

    }
    public function getJWTIdentifier() {
        return $this->getKey();
    }

    public function getJWTCustomClaims() {
        return [];
    }
    public function transactions(){
        return $this->hasMany(Transaction::class);
    }
    
  
    public static function boot() {
        parent::boot();
        if (auth()->check() && auth()->user()->type_id !=3) {
             static::addGlobalScope('specific_ids', function (Builder $builder) {
                $builder->whereIn('id', getUsersIds());
            });
        }
       
        static::deleting(function($user) { // before deletenotes method call this
             $user->notes()->delete();
        });
        
    }
    




    public function scopeSearch($query, $request) {
        if ($request->filled('q')) {
            $query = $query->where(DB::raw("CONCAT(`first_name`, ' ', `last_name`)"), 'like', "%" . $request->q . "%")
                        ->orWhere('email', 'like', "%$request->q%")
                        ->orWhere('phone', 'like', "%$request->q%");
        }

        if ($request->filled('status')) {
            $query = $query->where('status', $request->status);
        }

        if ($request->filled('source')) {
            $query = $query->where('source', $request->source);
        }

        if ($request->filled('manager')) {
            $query = $query->where('manager_id', $request->manager);
        }

        return $query;
    }
    
    
     /**
     * Scope for leads filtering (combining multiple conditions)
     */
     
      /**
     * Scope for filtering by user type
     */
    public function scopeOfType($query, $types)
    {
        if (is_array($types)) {
            return $query->whereIn('type_id', $types);
        }
        return $query->where('type_id', $types);
    }
    
    
    /**
     * Scope for filtering by deposited account status
     */
    public function scopeDeposited($query, $deposited = true)
    {
        return $query->where('depositedAcount', $deposited ? 1 : 0);
    }

    /**
     * Scope for filtering by search parameters
     */
    public function scopeFilterBySearch($query, array $searchFilters)
    {
        foreach ($searchFilters as $filter) {
            $field = $filter[0];
            $value = $filter[1];

            switch ($field) {
                case 'name':
                    $query->where('surname', 'LIKE', '%' . $value . '%');
                    break;
                case 'email':
                     if (is_numeric($value)) {
        // لو المستخدم دخل رقم → ابحث في id أو phone مثلاً
                       $query->where('phone', 'LIKE', '%' . $value . '%')->orWhere('id', $value);
                    } else {
                        // لو المستخدم دخل نص → ابحث في email
                        $query->where('email', 'LIKE', '%' . $value . '%')->orWhere('name', 'LIKE', '%' . $value . '%');
                    }
                    
                    break;
                case 'phone':
                    $query->where('phone', 'LIKE', '%' . $value . '%');
                    break;
                case 'country':
                    $items = explode(',', (string) $value);
                    $query->whereIn('country',$items);
                    break;
                case 'no_of_logins':
                case 'block':
                    $query->where($field, $value);
                    break;
                default:
                    $query->where($field, 'LIKE', '%' . $value . '%');
            }
        }

        return $query;
    }

    /**
     * Scope for filtering by manager assignment
     */
    public function scopeFilterByManager($query, $managerIds = null)
    {
        if ($managerIds !== null && is_array($managerIds) && count($managerIds) > 0) {
            return $query->whereIn('id', $managerIds);
        }

        return $query;
    }

    /**
     * Scope for filtering by InfoTradeUser conditions
     */
    public function scopeFilterByTradeInfo($query, array $tradeFilters = [], array $statusFilters = [])
    {
       if (!empty($tradeFilters) || !empty($statusFilters)) {
            return $query->whereHas('userInfo', function ($q) use ($tradeFilters, $statusFilters) {
                // Apply trade filters
                foreach ($tradeFilters as $filter) {
                    if (count($filter) == 2 && is_array($filter[1])) {
                        $q->whereIn($filter[0], $filter[1]);
                    }
                }
        
                // Apply status filters
                foreach ($statusFilters as $condition) {
                    if (count($condition) === 2 && is_array($condition[1])) {
                        // Example: ['status', [1,2,3]]
                        $q->whereIn($condition[0], $condition[1]);
                    } elseif (count($condition) === 3) {
                        // Example: ['age', '>=', 18]
                        $q->where($condition[0], $condition[1], $condition[2]);
                    }
                }
            });
        }


        return $query;
    }

    /**
     * Scope for applying custom sorting with related tables
     */
 /**
 * Scope for applying custom sorting using relationships
 */
/**
 * Scope for applying custom sorting - FIXED VERSION
 */
public function scopeCustomSort($query, $sortBy = 'id', $sortDirection = 'desc')
{
    // Ensure proper sort direction
    $sortDirection = strtolower($sortDirection);
    if (!in_array($sortDirection, ['asc', 'desc'])) {
        $sortDirection = 'desc';
    }

    switch ($sortBy) {
        case 'balance':
            // Use a more reliable subquery approach
            return $query->addSelect([
                        'sort_balance' => \DB::table('info_trade_users')
                            ->select('balance')
                            ->whereColumn('user_id', 'users.id')
                            ->limit(1)
                    ])
                    ->orderBy('sort_balance', $sortDirection);

        case 'status_id':
            return $query->addSelect([
                        'sort_status' => \DB::table('info_trade_users')
                            ->select('status_id')
                            ->whereColumn('user_id', 'users.id')
                            ->limit(1)
                    ])
                    ->orderBy('sort_status', $sortDirection);

        case 'manager_id':
            return $query->addSelect([
                        'sort_manager' => \DB::table('assign_user_managers')
                            ->select('admin_id')
                            ->whereColumn('user_id', 'users.id')
                            ->limit(1)
                    ])
                    ->orderBy('sort_manager', $sortDirection);

        case 'country':
            return $query->addSelect([
                        'sort_country' => \DB::table('countries')
                            ->select('name')
                            ->whereColumn('id', 'users.country_id')
                            ->limit(1)
                    ])
                    ->orderBy('sort_country', $sortDirection);

        case 'surname':
        case 'email':
        case 'phone':
        case 'created_at':
        case 'updated_at':
        case 'id':
        case 'no_of_logins':
        case 'block':
        case 'depositedAcount':
        case 'type_id':
            return $query->orderBy('users.' . $sortBy, $sortDirection);

        default:
            return $query->orderBy('users.id', $sortDirection);
    }
}


    
    public function scopeLeadsFilter(Builder $query, array $userFilters = [], array $tradeFilters = [], $managerIds = null)
    {
        Log::info($tradeFilters);
        return $query->ofType([1, 2])
                    ->filterBySearch($userFilters)
                    ->filterByTradeInfo($tradeFilters)
                    ->filterByManager($managerIds)
                    ->whereIn('id', function($subQuery) use ($tradeFilters) {
                        $subQuery->select('user_id')
                                ->from('info_trade_users')
                                ->whereIn('user_id', getUsersIds());
                        
                        foreach ($tradeFilters as $filter) {
                            $subQuery->whereIn($filter[0], $filter[1]);
                        }
                    });
    }

    /**
     * Scope for active customers
     */
    public function scopeActiveCustomers(Builder $query, array $userFilters = [], array $tradeFilters = [], $managerIds = null)
    {
        $assignedUserIds = \App\Models\AssignUserManager::where('admin_id', '<>', 0)
                                                        ->whereIn('user_id', getUsersIds())
                                                        ->pluck('user_id');

        return $query->ofType(2)
                    ->whereIn('id', $assignedUserIds)
                    ->filterBySearch($userFilters)
                    ->filterByTradeInfo($tradeFilters, [['status_id', '<>', 4]])
                    ->filterByManager($managerIds);
    }

    /**
     * Scope for potential customers
     */
    public function scopePotentialCustomers(Builder $query, array $userFilters = [], array $tradeFilters = [], $managerIds = null)
    {
        return $query->ofType(2)
                    ->filterBySearch($userFilters)
                    ->filterByTradeInfo($tradeFilters, [['status_id', '=', 4]])
                    ->filterByManager($managerIds);
    }

    /**
     * Scope for archive customers
     */
    public function scopeArchiveCustomers(Builder $query, array $userFilters = [], array $tradeFilters = [], $managerIds = null)
    {
        return $query->ofType(9)
                    ->filterBySearch($userFilters)
                    ->filterByTradeInfo($tradeFilters, [['status_id', '<>', 4]])
                    ->filterByManager($managerIds);
    }

    /**
     * Scope for FTD customers
     */
    public function scopeFtdCustomers(Builder $query, array $userFilters = [], array $tradeFilters = [], $managerIds = null)
    {
        return $query->ofType(2)
                    ->deposited(true)
                    ->filterBySearch($userFilters)
                    ->filterByTradeInfo($tradeFilters, [['status_id', '<>', 4]])
                    ->filterByManager($managerIds);
    }

    /**
     * Scope for public customers
     */
    public function scopePublicCustomers(Builder $query, array $userFilters = [], array $tradeFilters = [], $managerIds = null)
    {
        $assignedUserIds = \App\Models\AssignUserManager::where('admin_id', '<>', 0)->pluck('user_id');

        return $query->ofType(2)
                    ->whereNotIn('id', $assignedUserIds)
                    ->filterBySearch($userFilters)
                    ->filterByTradeInfo($tradeFilters, [['status_id', '<>', 4]])
                    ->filterByManager($managerIds);
    }
    
     public function scopeApplyDateFilters($query, array $dateFilters = [])
    {
        if (empty($dateFilters) || !isset($dateFilters['field'])) {
            return $query;
        }

        $field = $dateFilters['field'];
        $startDate = $dateFilters['start_date'] ?? null;
        $endDate = $dateFilters['end_date'] ?? null;

        // Map field names to their respective tables/relationships
        $fieldMappings = [
            'created_at' => 'users.created_at',
            'updated_at' => 'users.updated_at',
            'last_seen' => 'users.last_seen',
            'email_verified_at' => 'users.email_verified_at',
            'registration_date' => 'users.created_at', // Alias for created_at
            'last_login' => 'users.last_seen', // Alias for last_seen
        ];

        // Handle InfoTradeUser date fields
        $tradeUserDateFields = [
            'trade_created_at' => 'created_at',
            'trade_updated_at' => 'updated_at',
            'status_updated_at' => 'updated_at',
            'balance_updated_at' => 'updated_at'
        ];

        if (isset($fieldMappings[$field])) {
            // Direct user table date fields
            $dbField = $fieldMappings[$field];
            
            if ($startDate && $endDate) {
                $query->whereBetween($dbField, [$startDate, $endDate]);
            } elseif ($startDate) {
                $query->where($dbField, '>=', $startDate);
            } elseif ($endDate) {
                $query->where($dbField, '<=', $endDate);
            }
        } elseif (isset($tradeUserDateFields[$field])) {
            // InfoTradeUser date fields
            $tradeField = $tradeUserDateFields[$field];
            
            $query->whereHas('userInfo', function ($q) use ($tradeField, $startDate, $endDate) {
                if ($startDate && $endDate) {
                    $q->whereBetween($tradeField, [$startDate, $endDate]);
                } elseif ($startDate) {
                    $q->where($tradeField, '>=', $startDate);
                } elseif ($endDate) {
                    $q->where($tradeField, '<=', $endDate);
                }
            });
        } else {
            // Try to apply the filter to the users table as fallback
            if ($startDate && $endDate) {
                $query->whereBetween("users.{$field}", [$startDate, $endDate]);
            } elseif ($startDate) {
                $query->where("users.{$field}", '>=', $startDate);
            } elseif ($endDate) {
                $query->where("users.{$field}", '<=', $endDate);
            }
        }

        return $query;
    }

    /**
     * Scope for filtering by date range on specific field
     */
    public function scopeWhereDateRange($query, $field, $startDate = null, $endDate = null)
    {
        if ($startDate && $endDate) {
            return $query->whereBetween($field, [
                Carbon::parse($startDate)->startOfDay(),
                Carbon::parse($endDate)->endOfDay()
            ]);
        } elseif ($startDate) {
            return $query->where($field, '>=', Carbon::parse($startDate)->startOfDay());
        } elseif ($endDate) {
            return $query->where($field, '<=', Carbon::parse($endDate)->endOfDay());
        }

        return $query;
    }

    /**
     * Scope for filtering by creation date range
     */
    public function scopeCreatedBetween($query, $startDate, $endDate = null)
    {
        return $this->scopeWhereDateRange($query, 'created_at', $startDate, $endDate);
    }

    /**
     * Scope for filtering by update date range
     */
    public function scopeUpdatedBetween($query, $startDate, $endDate = null)
    {
        return $this->scopeWhereDateRange($query, 'updated_at', $startDate, $endDate);
    }

    /**
     * Scope for filtering by last seen date range
     */
    public function scopeLastSeenBetween($query, $startDate, $endDate = null)
    {
        return $this->scopeWhereDateRange($query, 'last_seen', $startDate, $endDate);
    }

    // ... rest of existing methods remain the same ...

    /**
     * Enhanced scope for leads filtering with date support
     */
    

    // ... rest of existing scope methods remain the same ...
    
    
    public function explodedata($value){
        return $value;
        
    //   if (is_array($value)) {
    //         $value = $value[0]; // لو جالك ["3,1"]
    // }

    // $items = explode(',', (string) $value);

    // // تنظيف القيم وتحويلها إلى أرقام صحيحة
    // return array_map('intval', $items);
    }

}