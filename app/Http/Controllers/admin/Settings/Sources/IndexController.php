<?php

namespace App\Http\Controllers\admin\Settings\Sources;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\Settings\Sources\IndexServices;
class IndexController extends Controller
{
    public $sources;
    public function __construct() {
        $this->sources = new IndexServices();
    }


    public function index(Request $requet){
        $this->setMessage("sources");
        $this->setData($this->sources->all($requet));
        return $this->sendApiResonse();
    }

    public function show($id){
        $this->setMessage("sources");
        $this->setData($this->sources->show($id));
        return $this->sendApiResonse();
    }


    public function store(request $requet){
        $this->sources->store($requet);
        return $this->sendApiResonse();
    }

    public function update(Request $requet,$id){
        // $id = $id;
        $this->sources->update($requet,$id);
        return $this->sendApiResonse();
    }


    public function destroy(Request $requet){
        $this->sources->destroy($requet);
        return $this->sendApiResonse();
    }
}
