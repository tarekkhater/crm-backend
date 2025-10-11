<?php

namespace App\Http\Controllers\admin\Settings\Desk;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\Settings\Desk\IndexServices;
class IndexController extends Controller
{
    public $desks;
    public function __construct() {
        $this->desks = new IndexServices();
    }


    public function index(Request $requet){
        $this->setMessage("desk");
        $this->setData($this->desks->all($requet));
        return $this->sendApiResonse();
    }

    public function show($id){
        $this->setMessage("desk");
        $this->setData($this->desks->show($id));
        return $this->sendApiResonse();
    }


    public function store(request $requet){
        $this->desks->store($requet);
        return $this->sendApiResonse();
    }

    public function update(Request $requet,$id){
        // $id = $id;
        $this->desks->update($requet,$id);
        return $this->sendApiResonse();
    }


    public function destroy(Request $requet){
        $this->desks->destroy($requet);
        return $this->sendApiResonse();
    }
}
