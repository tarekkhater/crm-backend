<?php

namespace App\Http\Controllers\admin\Settings\TypeUser;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\Settings\TypeUser\TypeUsersServices;
use App\Models\TypeUser;
class IndexController extends Controller
{
    public $TypeUser;
    public function __construct() {
        $this->TypeUser = new TypeUsersServices();
    }
    public function index(Request $requet){
        $this->setMessage("jssjfhsfdkj");
        $this->setData($this->TypeUser->allactive($requet));
        return $this->sendApiResonse();
    }

    public function show($id){
        $this->setMessage("jssjfhsfdkj");
        $this->setData($this->TypeUser->show($id));
        return $this->sendApiResonse();
    }


    public function store(request $requet){
        $this->TypeUser->store($requet);
        return $this->sendApiResonse();
    }

    public function update(Request $requet,$id){
        $id = $id;
        $this->TypeUser->update($requet,$id);
        return $this->sendApiResonse();
    }


    public function destroy(Request $requet){
        $this->TypeUser->destroy($requet);
        return $this->sendApiResonse();
    }
}
