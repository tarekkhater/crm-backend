<?php

namespace App\Http\Controllers\admin\Settings\Plans;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\Settings\Plans\IndexServices;
use App\Models\Package;
class IndexController extends Controller
{
    public $plans;
    public function __construct() {
        $this->plans = new IndexServices();
    }


    public function index(Request $requet){
        $this->setMessage("jssjfhsfdkj");
        $this->setData($this->plans->all($requet));
        return $this->sendApiResonse();
    }

    public function all(){
        $packages = Package::get();
        $this->setMessage("jssjfhsfdkj");
        $this->setData($packages);
        return $this->sendApiResonse();
    }

    public function show($id){
        $this->setMessage("jssjfhsfdkj");
        $this->setData($this->plans->show($id));
        return $this->sendApiResonse();
    }


    public function store(request $requet){
        $this->plans->store($requet);
        return $this->sendApiResonse();
    }

    public function update(Request $requet,$id){
        $id = $id;
        $this->plans->update($requet,$id);
        return $this->sendApiResonse();
    }


    public function destroy(Request $requet){
        $this->plans->destroy($requet);
        return $this->sendApiResonse();
    }
}
