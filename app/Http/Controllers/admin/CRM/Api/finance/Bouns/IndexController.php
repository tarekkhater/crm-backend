<?php

namespace App\Http\Controllers\admin\CRM\Api\finance\Bouns;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class IndexController extends Controller
{
    public function index(Request $request){
        $deposites = Deposit::paginate(20);
        $this->setMessage("success");
        $this->setData($deposites);
        return $this->sendApiResonse();
    }

}
