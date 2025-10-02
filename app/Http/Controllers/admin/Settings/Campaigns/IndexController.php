<?php

namespace App\Http\Controllers\admin\Settings\Campaigns;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\Settings\Campaigns\IndexServices;
class IndexController extends Controller
{
    public $campaigns;
    public function __construct() {
        $this->campaigns = new IndexServices();
    }


    public function index(Request $requet){
        $this->setMessage("campaigns");
        $this->setData($this->campaigns->all($requet));
        return $this->sendApiResonse();
    }

    public function show($id){
        $this->setMessage("campaigns");
        $this->setData($this->campaigns->show($id));
        return $this->sendApiResonse();
    }


    public function store(request $requet){
        $this->campaigns->store($requet);
        return $this->sendApiResonse();
    }

    public function update(Request $requet,$id){
        // $id = $id;
        $this->campaigns->update($requet,$id);
        return $this->sendApiResonse();
    }


    public function destroy(Request $requet){
        $this->campaigns->destroy($requet);
        return $this->sendApiResonse();
    }
}
