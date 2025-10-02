<?php
namespace App\Http\Controllers\admin\CRM\Api\ActionTab\KYC;

use App\Http\Controllers\Controller;
use App\Models\Identity;
use Illuminate\Http\Request;
use App\Models\Admin;
class IndexController extends Controller
{

    public function all(){
        $users = Admin::where('type_id',6)->get();
//        $users->load('Money');
        $this->setMessage("success");
        $this->setData($users);
        return $this->sendApiResonse();
    }
    public function index(){
        $users = Admin::where('type_id',6)->paginate(15);
        $users->load('Money');
        $this->setMessage("success");
        $this->setData($users);
        return $this->sendApiResonse();
    }




}
