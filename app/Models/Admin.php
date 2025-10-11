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
        
        if (auth()->check()) {
            $user = auth()->user();
            if (auth()->user()->type_id == 3) {
                $ids = Admin::select()->pluck('id');
            } else if (auth()->user()->type_id == 5) {
                $idsTeamLeader = Admin::where('broker_id', auth()->user()->id)->whereIn('type_id', [7, 8])->pluck('id');
                $ids = UserManager::where('admin_id', $idsTeamLeader)->where('type', '0')->pluck('user_id');
            } else if (auth()->user()->type_id == 6) {
                $ids = UserManager::where('admin_id', auth()->user()->id)->where('type', '0')->pluck('user_id');
            }
        
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