<?php

namespace App\Http\Controllers\admin\Settings\Status;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\Settings\Status\IndexServices;
class IndexController extends Controller
{
    public $statuss;
    public function __construct() {
        $this->statuss = new IndexServices();
    }


    public function index(Request $requet){
        $this->setMessage("status");
        $this->setData($this->statuss->all($requet));
        return $this->sendApiResonse();
    }

    public function show($id){
        $this->setMessage("status");
        $this->setData($this->statuss->show($id));
        return $this->sendApiResonse();
    }


    public function store(request $requet){
        $this->statuss->store($requet);
        return $this->sendApiResonse();
    }

    public function update(Request $requet,$id){
        // $id = $id;
        $this->statuss->update($requet,$id);
        return $this->sendApiResonse();
    }


    public function destroy(Request $requet){
        $this->statuss->destroy($requet);
        return $this->sendApiResonse();
    }
}
