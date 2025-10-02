<?php

namespace App\Services\Users\Leads;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\InfoTradeUser;
use App\Models\AssignUserManager;
class IndexSearchServices extends Controller
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
            default:
                return $this->FTD($request);  
        endswitch;
            
        
    }
    
    
    public function leads($request){
        $idspotinal = InfoTradeUser::whereIn('user_id',getUsersIds())->pluck('user_id');
        $users = User::whereIn('id', $idspotinal)->with(['Manager','userInfo'])->where($this->searchrequest($request))->where('type_id',1)->paginate(15);
        return $users;
    }
    
    public function Active($request){
            $id = AssignUserManager::where('admin_id','<>',0)->whereIn('user_id', getUsersIds())->pluck('user_id');
            // $idspotinal = InfoTradeUser::whereIn('user_id',$id)->where('status_id','<>',4)->pluck('user_id');
            // $users = User::whereIn('id',$idspotinal)->with(['Manager','userInfo'])->where($this->searchrequest($request))->where('type_id',2)->paginate(15);
             $idspotinal = InfoTradeUser::whereIn('user_id',$id)->where('status_id','<>',4)->pluck('user_id');
            $users = User::whereIn('id', $idspotinal)->with(['Manager','userInfo','countries'])->where('type_id',2)->where($this->searchrequest($request))->orderByDESC('created_at')->paginate(15);

            return $users;
    }
    
    public function potinal($request){
        $idspotinal = InfoTradeUser::whereIn('user_id',getUsersIds())->where('status_id',4)->pluck('user_id');
        $users = User::whereIn('id', $idspotinal)->with(['Manager','userInfo'])->where($this->searchrequest($request))->where('type_id',2)->paginate(15);
        return $users;
    }
    
    public function ArchiveCustomer($request){
           $idspotinal = InfoTradeUser::whereIn('user_id',getUsersIds())->where('status_id','<>',4)->pluck('user_id');
           $users = User::whereIn('id', $idspotinal)->with(['Manager','userInfo','countries'])->where($this->searchrequest($request))->where('type_id',9)->orderByDESC('created_at')->paginate(15);
     
        return $users;
    }
    
    public function FTD($request){
         $idspotinal = InfoTradeUser::whereIn('user_id',getUsersIds())->where('status_id','<>',4)->pluck('user_id');
         $users = User::whereIn('id', $idspotinal)->Where('type_id',2)->where('depositedAcount',1)->with(['Manager','userInfo','countries'])->where($this->searchrequest($request))->orderByDESC('created_at')->paginate(15);

        // $users = User::whereIn('id', getUsersIds())->where('type_id',2)->with(['Manager','userInfo'])->where('depositedAcount',1)->paginate(15);
        return $users;
    }
    
    public function publicCustomer($request){
            $id = AssignUserManager::where('admin_id','<>',0)->pluck('user_id');
            $idspotinal = InfoTradeUser::whereIn('user_id',getUsersIds())->where('status_id','<>',4)->pluck('user_id');
            $users = User::whereIn('id', getUsersIds())->with(['Manager','userInfo'])->where($this->searchrequest($request))->whereNotIn('id',$id)->where('type_id',2)->paginate(15);
            return $users;
    }
    
    
    public function searchrequest($request){
        return [[$request->key,'LIKE','%'.$request->input.'%']];
    }
} 