<?php
 
namespace App\Models\Scopes;
 
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;
use App\Models\Admin;
use App\Models\UserManager;
class AdminScope implements Scope
{
    /**
     * Apply the scope to a given Eloquent query builder.
     */
     
       
    
    public function apply(Builder $builder, Model $model): void
    {
        
         $builder->with(['maintype','type','countries']);
        
    }
}