<?php

namespace App\Models;

use Laratrust\Models\Permission as LaratrustPermission;
use Illuminate\Database\Eloquent\Model;

class Permission extends Model
// class Permission extends LaratrustPermission
{
    public $guarded = [];
    protected $fillable= ['title','name','display_name','description','path'];
}
