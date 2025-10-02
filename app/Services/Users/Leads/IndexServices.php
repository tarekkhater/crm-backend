<?php

namespace App\Services\Users\Leads;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\InfoTradeUser;
use App\Models\AssignUserManager;
use App\Http\Resources\Admin\User\UsersResource;
class IndexServices extends Controller
{
    public function index(Request $request){
        
        switch($request->id):
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
        $users = User::whereIn('id', $idspotinal)->with(['Manager','userInfo','countries'])->where('type_id',1)->orderByDESC('created_at')->paginate(15);
        
        $data = [
            'data' => UsersResource::make($users->items()),
            'total' => $users->total(),
            'per_page' => $users->perPage(),
            'current_page' => $users->currentPage(),
            'last_page' => $users->lastPage(),
            'from' => $users->firstItem(),
            'to' => $users->lastItem(),
            'links'        =>  $this->getLinks($users),
        ];
        return $data;
    }
    
    public function Active($request){
            $id = AssignUserManager::whereIn('user_id', getUsersIds())->where('admin_id','<>',0)->pluck('user_id');
            // $users = User::whereIn('id',$id)->with(['Manager'])->where('type_id',2)->paginate(15);
            $idspotinal = InfoTradeUser::whereIn('user_id',$id)->where('status_id','<>',4)->pluck('user_id');
            $users = User::whereIn('id', $idspotinal)->with(['Manager','userInfo','countries'])->where('type_id',2)->orderByDESC('created_at')->paginate(15);

           $data = [
            'data' => UsersResource::make($users->items()),
            'total' => $users->total(),
            'per_page' => $users->perPage(),
            'current_page' => $users->currentPage(),
            'last_page' => $users->lastPage(),
            'from' => $users->firstItem(),
            'to' => $users->lastItem(),
            'links'        => $this->getLinks($users),
        ];
        return $data;
    }
    
    public function potinal($request){
        $idspotinal = InfoTradeUser::whereIn('user_id',getUsersIds())->where('status_id',4)->pluck('user_id');
        $users = User::whereIn('id', $idspotinal)->with(['Manager','userInfo','countries'])->where('type_id',2)->orderByDESC('created_at')->paginate(15);
        $data = [
        'data'         => UsersResource::make($users->items()),
        'current_page' => $users->currentPage(),
        'from'         => $users->firstItem(),
        'last_page'    => $users->lastPage(),
        'links'        => $this->getLinks($users),
        'per_page'     => $users->perPage(),
        'to'           => $users->lastItem(),
        'total'        => $users->total(),
        ];
        return $data;
    }
    
    public function ArchiveCustomer($request){
            $idspotinal = InfoTradeUser::whereIn('user_id',getUsersIds())->where('status_id','<>',4)->pluck('user_id');
           $users = User::whereIn('id', $idspotinal)->with(['Manager','userInfo','countries'])->where('type_id',9)->orderByDESC('created_at')->paginate(15);
           $data = [
           'data'         => UsersResource::make($users->items()),
        'current_page' => $users->currentPage(),
        'from'         => $users->firstItem(),
        'last_page'    => $users->lastPage(),
        'links'        => $this->getLinks($users),
        'per_page'     => $users->perPage(),
        'to'           => $users->lastItem(),
        'total'        => $users->total(),
        ];
        return $data;
    }
    
    public function FTD($request){
         $idspotinal = InfoTradeUser::whereIn('user_id',getUsersIds())->where('status_id','<>',4)->pluck('user_id');
    // $users = User::whereIn('id', $idspotinal)->orWhere('type_id',10)->with(['Manager','userInfo','countries'])->orderByDESC('created_at')->paginate(15);
      $users = User::whereIn('id', $idspotinal)->Where('type_id',2)->where('depositedAcount',1)->with(['Manager','userInfo','countries'])->orderByDESC('created_at')->paginate(15);
        $data = [
          'data'         => UsersResource::make($users->items()),
        'current_page' => $users->currentPage(),
        'from'         => $users->firstItem(),
        'last_page'    => $users->lastPage(),
        'links'        => $this->getLinks($users),
        'per_page'     => $users->perPage(),
        'to'           => $users->lastItem(),
        'total'        => $users->total(),
        ];
        return $data;
    }
    
    public function publicCustomer($request){
            $id = AssignUserManager::where('admin_id','<>',0)->pluck('user_id');
            $idspotinal = InfoTradeUser::whereIn('user_id',getUsersIds())->whereNotIn('user_id',$id)->where('status_id','<>',4)->pluck('user_id');
            $users = User::whereIn('id', $idspotinal)->with(['Manager','userInfo','countries'])->where('type_id',2)->orWhere('type_id',5)->orderBy('created_at')->paginate(15);
            $data = [
            'data'         => UsersResource::make($users->items()),
        'current_page' => $users->currentPage(),
        'from'         => $users->firstItem(),
        'last_page'    => $users->lastPage(),
        'links'        => $this->getLinks($users),
        'per_page'     => $users->perPage(),
        'to'           => $users->lastItem(),
        'total'        => $users->total(),
        ];
        return $data;
    }
    
    
    public function getLinks($users){
        $links = [];

for ($page = 1; $page <= $users->lastPage(); $page++) {
    $links[] = [
        'url' => $users->url($page),
        'label' => $page,
        'active' => $page == $users->currentPage(),
    ];
}
 return $links;
    }
    
    

    
}
