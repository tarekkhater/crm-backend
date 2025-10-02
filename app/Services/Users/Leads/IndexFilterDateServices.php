<?php

namespace App\Services\Users\Leads;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\InfoTradeUser;
use App\Models\AssignUserManager;
class IndexFilterDateServices extends Controller
{
    public function index(Request $request){
     
        switch($request->type):
            case 0:
                return $this->leads($request); 
            case 1:
                return $this->potinal($request);    
            break;
            case 2:
                return $this->Active($request);    
            break;
            case 5:
                return $this->publicCustomer($request);    
            break;
             case 9:
                return $this->ArchiveCustomer($request);    
            break;
             case 10:
                return $this->publicLeadCenter($request); 
            default:
                return $this->FTD($request);  
        endswitch;
            
        
    }
    
    
    public function leads($request){
        $idspotinal = InfoTradeUser::whereIn('user_id',getUsersIds())->where('status_id','<>',4)->pluck('user_id');
        $users = User::whereIn('id', $idspotinal)->with(['Manager','userInfo'])->whereBetween('created_at',$this->searchrequest($request))->where('type_id',1)->paginate(15);
        return $users;
    }
    
    public function Active($request){
            $id = AssignUserManager::whereIn('user_id', getUsersIds())->where('admin_id','<>',0)->pluck('user_id');
            $users = User::whereIn('id',$id)->with(['Manager','userInfo'])->whereBetween('created_at',$this->searchrequest($request))->where('type_id',2)->paginate(15);
            return $users;
    }
    
    public function potinal($request){
        $idspotinal = InfoTradeUser::whereIn('user_id',getUsersIds())->where('status_id',4)->pluck('user_id');
        $users = User::whereIn('id', $idspotinal)->with(['Manager','userInfo'])->whereBetween('created_at',$this->searchrequest($request))->where('type_id',1)->paginate(15);
        return $users;
    }
    
    public function ArchiveCustomer($request){
           $users = User::whereIn('id', getUsersIds())->with(['Manager','userInfo'])->whereBetween('created_at',$this->searchrequest($request))->where('type_id',2)->paginate(15);
        return $users;
    }
    
    public function FTD($request){
    $users = User::whereIn('id', getUsersIds())->where('type_id',2)->with(['Manager','userInfo'])->whereBetween('created_at',$this->searchrequest($request))->where('depositedAcount',1)->paginate(15);
        //   $users = User::whereIn('id', $idspotinal)->Where('type_id',2)->where('depositedAcount',1)->with(['Manager','userInfo','countries'])->orderByDESC('created_at')->paginate(15);

        return $users;
    }
    
    public function publicCustomer($request){
            $id = AssignUserManager::pluck('user_id');
            $users = User::whereIn('id', getUsersIds())->with(['Manager','userInfo'])->whereBetween('created_at',$this->searchrequest($request))->whereNotIn('id',$id)->where('type_id',2)->paginate(15);
            return $users;
    }
    
    public function publicLeadCenter($request){
        $idspotinal = InfoTradeUser::whereIn('user_id',getUsersIds())->where('status_id','<>',4)->pluck('user_id');
        $users = User::whereIn('id', $idspotinal)->with(['Manager','userInfo'])->whereBetween('created_at',$this->searchrequest($request))->where('type_id',1)->orderByDESC('created_at')->limit(8)->paginate(15);
        return $users;

    }
    
    
    public function searchrequest($request){
        $date = explode("to",$request->date);
        if(count($date) > 1){
            $from = date('Y-m-d',strtotime($date[0]));
            $to = date('Y-m-d',strtotime($date[1]));
            return [$from,$to];
        }else{
            $from = date('Y-m-d',strtotime($request->date));
            $to = date('Y-m-d');
            return [$from,$to];
        }
       
        
        
    }
} 