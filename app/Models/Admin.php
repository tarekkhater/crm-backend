<?php

namespace App\Models;

use App\Models\Scopes\AdminScope;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Tymon\JWTAuth\Contracts\JWTSubject;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
// use Laratrust\Traits\LaratrustUserTrait;
use Spatie\Permission\Traits\HasRoles;

class Admin extends Authenticatable implements MustVerifyEmail, JWTSubject
{

    use HasFactory;
    use SoftDeletes;
    use HasRoles;

    /** Must match Spatie roles/permissions guard (see `auth.guards.api` for admins). */
    protected $guard_name = 'api';

    protected $dates = ['deleted_at'];

    protected $fillable = [
        'email',
        'password',
        'image',
        'name',
        'surname',
        'country',
        'manager_id','phone',
        'is_active','desk_id',
        'status',
        'broker_id',
        'type_id',
        'email_verified_at',
        'sub_type_id','token_affilator','source_id'
    ];
    protected $hidden = [
        'password',
        'remember_token',
    ];
    
    protected $with = ['source','desk'];


    public static function ids(){
        $ids = [];

        if (!auth()->check()) {
            return $ids;
        }

        $admin = auth()->user();

        // Use withoutGlobalScopes() to avoid recursive scope application
        if ($admin->type_id == 3) {
            $isDeskScoped = ($admin->sub_type_id == 4) || !empty($admin->desk_id);

            if ($isDeskScoped && $admin->desk_id) {
                $ids = static::withoutGlobalScopes()
                    ->where('desk_id', $admin->desk_id)
                    ->whereNull('deleted_at')
                    ->pluck('id');
            } else {
                $ids = static::withoutGlobalScopes()->whereNull('deleted_at')->pluck('id');
            }
        } elseif ($admin->type_id == 5) {
            $ids = static::withoutGlobalScopes()
                ->where('broker_id', $admin->id)
                ->whereNull('deleted_at')
                ->pluck('id');
        } elseif ($admin->type_id == 6) {
            $ids = getTeamLeaderVisibleAgentIds($admin)->push($admin->id)->unique()->values();
        } elseif (in_array((int) $admin->type_id, [7, 8], true)) {
            $ids = collect([$admin->id]);
        }

        return $ids;
    }

    protected static function booted(): void
    {
        static::addGlobalScope(new AdminScope);
         if (auth()->check()) {
             static::addGlobalScope('specific_ids', function (Builder $builder) {
                $builder->whereIn('id', self::ids());
            });
        }
    }

      public function countries(){
        return $this->belongsTo(Country::class, 'country');
    }
    public function Active(){
        return $this->hasMany(ActiveUser::class, 'user_id')->where('type','=', '1');
    }

    public function Integration(){
        return $this->hasOne(Integration::class, 'user_id');
    }
    
    public function source(){
        return $this->belongsTo(Source::class, 'source_id');
    }

    public function IB(){
        return $this->hasOne(IBUser::class, 'user_id');
    }


    public function typeUser(){
        return $this->belongsTo(TypeUser::class,'type_id','id');
    }

    public function teamleader(){
        return $this->belongsTo(Admin::class,'manager_id','id');
    }

    public function employee(){
        return $this->hasMany(UserManager::class,'admin_id');
    }

    public function usermanager(){
        return $this->hasMany(UserManager::class,'user_id');
    }

    public function clientsmanager(){
        return $this->hasMany(AssignUserManager::class,'admin_id');
    }

    public function clients(){
        return $this->hasMany(AgentUser::class,'agent_id');
    }

    public function AgentUser(){
        return $this->hasOne(AgentUser::class, 'admin_id');
    }

    public function maintype(){
        return $this->belongsTo(TypeUser::class,'type_id');
    }
    public function type(){
        return $this->belongsTo(TypeUser::class,'sub_type_id');
    }

    public function broker(){
        return $this->belongsTo(Admin::class,'broker_id');
    }
    
    public static function hasPermission(){
        
    }
    
    
    public function desk() {
        return $this->belongsTo(Desk::class);
    }


    public function getJWTIdentifier() {
        return $this->getKey();
    }

    public function getJWTCustomClaims() {
        return [];
    }
}