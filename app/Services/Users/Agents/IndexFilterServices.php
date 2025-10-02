<?php

namespace App\Services\Users\Agents;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Admin;
class IndexFilterServices extends Controller
{
    
    public $data = [];
    public $datauser = [];
  
    
    
    public function index($request){
        
        switch($request->type):
            case 0:
                return $this->teamleaderSales($request);    
            break;
            case 1:
                return $this->AgentSales($request);    
            break;
            case 2:
                return $this->teamleaderRetenation($request);    
            break;
             case 3:
                return $this->AgentRetenation($request);    
            break;
            default:
                return $this->teamleaderSales($request);  
        endswitch;
            
        
    }
    
    
    public function teamleaderSales($request){
        $users = Admin::where($this->searchrequest($request))->with('broker')->where('type_id',6)->where('sub_type_id',7)->paginate(15);
        return $users;
    }
    
    public function AgentSales($request){
        $users = Admin::where($this->searchrequest($request))->with(['teamleader','broker'])->where('type_id',7)->paginate(15);
        return $users;
    }
    
    public function teamleaderRetenation($request){
        $users = Admin::where($this->searchrequest($request))->with('broker')->where('type_id',6)->where('sub_type_id',8)->paginate(15);
        return $users;
    }
    
    public function AgentRetenation($request){
        $users = Admin::where($this->searchrequest($request))->with(['teamleader','broker'])->where('type_id',8)->paginate(15);
        return $users;
    }

    
    
    public function searchrequest($request){
        $data = [];
        $datauser = [];
        if(count($request->search)){
           foreach($request->search as $req){
               if($req['key'] == 'no_of_logins' ||$req['key'] == 'block'){
                   $datauser[] =[$req['key'],$req['value']];
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
