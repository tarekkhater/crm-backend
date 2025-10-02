<?php

namespace App\Services\Users\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Admin;
class IndexFilterServices extends Controller
{
    
    public $data = [];
    public $datauser = [];
  
    
    
    public function index($request){
        $users = Admin::with(['roles','roles.role'])->where('type_id',3)->where($this->searchrequest($request))->where('id', '!=', auth()->id())->paginate(15);
        return $users;
        
    }
    
    
    public function teamleaderSales($request){
        $users = Admin::where($this->searchrequest($request))->where('type_id',6)->where('sub_type_id',7)->paginate(15);
        return $users;
    }
    
    public function AgentSales($request){
        $users = Admin::where($this->searchrequest($request))->where('type_id',7)->paginate(15);
        return $users;
    }
    
    public function teamleaderRetenation($request){
        $users = Admin::where($this->searchrequest($request))->where('type_id',6)->where('sub_type_id',8)->paginate(15);
        return $users;
    }
    
    public function AgentRetenation($request){
        $users = Admin::where($this->searchrequest($request))->where('type_id',8)->paginate(15);
        return $users;
    }

    
    
    public function searchrequest($request){
        $data = [];
        $datauser = [];
        if(count($request->search)){
           foreach($request->search as $req){
               if($req['key'] == 'no_of_logins' || $req['key'] == 'block'){
                   $data[] =[$req['key'],$req['value']];
               }else{
                   $data[] =[$req['key'],$req['value']];
               }
            
            } 
        }
        return $data;
        $this->data = $data;
        $this->datauser = $datauser;
    }
}
