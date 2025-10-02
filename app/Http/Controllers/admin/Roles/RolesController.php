<?php

namespace App\Http\Controllers\admin\Roles;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\Roles\RolesServices;
use App\Models\Role;
use App\Rules\NoHtmlInjection;

class RolesController extends Controller
{
    public $Role;
    public function __construct() {
        $this->Role = new RolesServices();
    }

    public function all(Request $requet){
        $this->setMessage("jssjfhsfdkj");
        $this->setData($this->Role->all());
        return $this->sendApiResonse();
    }
    public function index(Request $requet){
        $this->setMessage("jssjfhsfdkj");
        $this->setData($this->Role->paginate($requet));
        return $this->sendApiResonse();
    }

    public function show($id){
        $this->setMessage("jssjfhsfdkj");
        $this->setData($this->Role->show($id));
        return $this->sendApiResonse();
    }


    public function store(request $requet){
        $requet->validate([
            'name'=>["required","unique:roles,name",new NoHtmlInjection],
            'display_name'=>["required","unique:roles,display_name",new NoHtmlInjection],
            'permissions'=>["required","array"],
            'permissions.*'=>["required","numeric","exists:permissions,id"]
        ]);
        $role = $this->Role->store($requet);
        $this->setData($role);
        return $this->sendApiResonse();
    }

    public function update(Request $requet,$id){
        $id = $id;
        $requet->validate([
            'name'=>["required",new NoHtmlInjection],
            'display_name'=>["required",new NoHtmlInjection],
            'permissions'=>["required","array"],
            'permissions.*'=>["required","numeric","exists:permissions,id"]
        ]);
        $this->Role->update($requet,$id);
        return $this->sendApiResonse();
    }


    public function destroy(Request $requet){
        $this->Role->destroy($requet);
        return $this->sendApiResonse();
    }

}
