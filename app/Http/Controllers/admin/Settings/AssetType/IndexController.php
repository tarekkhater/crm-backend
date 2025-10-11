<?php

namespace App\Http\Controllers\admin\Settings\AssetType;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\Settings\AssetType\IndexServices;
class IndexController extends Controller
{
    public $assetType;
    public function __construct() {
        $this->assetType = new IndexServices();
    }


    public function index(Request $requet){
        $this->setMessage("sources");
        $this->setData($this->assetType->all($requet));
        return $this->sendApiResonse();
    }

    public function show($id){
        $this->setMessage("sources");
        $this->setData($this->assetType->show($id));
        return $this->sendApiResonse();
    }


    public function store(request $requet){
        $this->assetType->store($requet);
        return $this->sendApiResonse();
    }

    public function update(Request $requet,$id){
        // $id = $id;
        $this->assetType->update($requet,$id);
        return $this->sendApiResonse();
    }


    public function destroy(Request $requet){
        $this->assetType->destroy($requet);
        return $this->sendApiResonse();
    }
}
