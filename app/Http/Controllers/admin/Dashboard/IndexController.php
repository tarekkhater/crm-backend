<?php

namespace App\Http\Controllers\admin\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\User;
use App\Models\Admin;
use App\Models\Identity;
use App\Models\TradingAccount;
use App\Models\AssignUserManager;
use App\Models\InfoTradeUser;
use App\Models\Trade;
use App\Models\Deposit;
use App\Models\AgentUser;
use App\Models\Withdrawal;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use App\Models\UserCertificate;
use Spatie\Permission\Models\Role;
class IndexController extends Controller
{
    public function index(Request $request){
        $data = [
            'customer'=>$this->users($request),
            'kyc'=>$this->kyc($request),
            'trades'=>$this->trades($request),
            'finical'=>$this->finical($request),
            'certificates'=>$this->certificates($request)];
        $this->setData($data);
        $this->setMessage("success");
        return $this->sendApiResonse();
    }



    public function users($request){
         $idspotinal = InfoTradeUser::whereIn('user_id',getUsersIds())->where('status_id','<>',4)->pluck('user_id');
         $Archive = User::whereIn('id', $idspotinal)->where('type_id',9)->orderByDESC('created_at')->count();
         $idspotinalf = InfoTradeUser::whereIn('user_id',getUsersIds())->where('status_id','<>',4)->pluck('user_id');
      $usersf = User::whereIn('id', $idspotinalf)->Where('type_id',2)->where('depositedAcount',1)->orderByDESC('created_at')->count();
            $id = AssignUserManager::pluck('user_id');
            $idspotinalp = InfoTradeUser::whereIn('user_id',getUsersIds())->where('status_id','<>',4)->pluck('user_id');
            $usersp = User::whereIn('id', $idspotinalp)->with(['Manager','userInfo','countries'])->whereNotIn('id',$id)->where('type_id',2)->orWhere('type_id',5)->orderBy('created_at')->count();
         $idspotinalp = InfoTradeUser::whereIn('user_id',getUsersIds())->where('status_id',4)->pluck('user_id');
        $userspo = User::whereIn('id', $idspotinalp)->with(['Manager','userInfo','countries'])->where('type_id',2)->orderByDESC('created_at')->count();
 $idspotinalc = InfoTradeUser::whereIn('user_id',getUsersIds())->where('status_id','<>',4)->pluck('user_id');
            $usersc = User::whereIn('id', $idspotinalc)->with(['Manager','userInfo','countries'])->where('type_id',2)->orderByDESC('created_at')->count();

         
        return ['publicc'=>$usersp,'active'=>$usersc,'ftd'=>$usersf,'arcive'=> $Archive,'potinial'=>$userspo];
    }
    
    public function kyc($request){
        $ids =  getUsersIds();
        return ['completed'=>Identity::whereIn('user_id', $ids)->wherestatus('1')->count(),'pendingk'=>Identity::whereIn('user_id', $ids)->wherestatus('0')->count()];
    }
    
     public function trades($request){
        $ids =  getUsersIds();
        return ['open'=>Trade::whereIn('user_id', $ids)->Where('is_pending_order',null)->whereStatus(0)->count(),'close'=>Trade::whereIn('user_id', $ids)->whereStatus(1)->count(),'pendingg'=>Trade::whereIn('user_id', $ids)->Where('is_pending_order','<>',null)->whereStatus(0)->count()];
    }
    
     public function finical($request){
        $ids =  getUsersIds();
        return ['deposite'=>Deposit::whereIn('user_id', $ids)->count(),'withdrawal'=>Withdrawal::whereIn('user_id', $ids)->count()];
    }
    
    public function certificates($request){
        $ids =  getUsersIds();
        return ['profit'=>UserCertificate::whereIn('user_id', $ids)->sum('total'),'accepted'=>UserCertificate::whereIn('user_id', $ids)->where('status','1')->count(),'rejected'=>UserCertificate::whereIn('user_id', $ids)->where('status','3')->count()];
    }
    public function getUserIds($id){
            $ids = AssignUserManager::where('admin_id', $id)->pluck('user_id');
            return $ids;
    }
    
     public function show(Request $request){
        $data = [
            'customer'=>$this->showusers($request),
            'kyc'=>$this->showkyc($request),
            'trades'=>$this->showtrades($request),
            'finical'=>$this->showfinical($request)];
        $this->setData($data);
        $this->setMessage("success");
        return $this->sendApiResonse();
    }

    

    public function showusers($request){
            $ids = $this->getUserIds($request->id);
            $idspotinal = InfoTradeUser::whereIn('user_id',$ids)->where('status_id','<>',4)->pluck('user_id');
            $Archive = User::whereIn('id', $idspotinal)->where('type_id',9)->orderByDESC('created_at')->count();
            $idspotinalf = InfoTradeUser::whereIn('user_id',$ids)->where('status_id','<>',4)->pluck('user_id');
            $usersf = User::whereIn('id', $idspotinalf)->Where('type_id',2)->where('depositedAcount',1)->orderByDESC('created_at')->count();
            $id = AssignUserManager::pluck('user_id');
            $idspotinalp = InfoTradeUser::whereIn('user_id',$ids)->where('status_id','<>',4)->pluck('user_id');
            $usersp = User::whereIn('id', $idspotinalp)->with(['Manager','userInfo','countries'])->whereNotIn('id',$id)->where('type_id',2)->orWhere('type_id',5)->orderBy('created_at')->count();
            $idspotinalp = InfoTradeUser::whereIn('user_id',$ids)->where('status_id',4)->pluck('user_id');
            $userspo = User::whereIn('id', $idspotinalp)->with(['Manager','userInfo','countries'])->where('type_id',2)->orderByDESC('created_at')->count();
            $idspotinalc = InfoTradeUser::whereIn('user_id',$ids)->where('status_id','<>',4)->pluck('user_id');
            $usersc = User::whereIn('id', $idspotinalc)->with(['Manager','userInfo','countries'])->where('type_id',2)->orderByDESC('created_at')->count();

         
        return ['publicc'=>$usersp,'active'=>$usersc,'ftd'=>$usersf,'arcive'=> $Archive,'potinial'=>$userspo];
    }
    
    public function showkyc($request){
        $ids = $this->getUserIds($request->id);;
        return ['completed'=>Identity::whereIn('user_id', $ids)->wherestatus('1')->count(),'pendingk'=>Identity::whereIn('user_id', $ids)->wherestatus('0')->count()];
    }
    
     public function showtrades($request){
         $ids = $this->getUserIds($request->id);
        return ['open'=>Trade::whereIn('user_id', $ids)->Where('is_pending_order',null)->whereStatus(0)->count(),'close'=>Trade::whereIn('user_id', $ids)->whereStatus(1)->count(),'pendingg'=>Trade::whereIn('user_id', $ids)->Where('is_pending_order','<>',null)->whereStatus(0)->count()];
    }
    
     public function showfinical($request){
         $ids = $this->getUserIds($request->id);
        return ['deposite'=>Deposit::whereIn('user_id', $ids)->count(),'withdrawal'=>Withdrawal::whereIn('user_id', $ids)->count()];
    }
    
    
    public function showrole(Request $request){
       
        $user = Admin::find($request->id);
        $role = $user->roles()->first();
        $permissions = $role->permissions;
        $this->setMessage("jssjfhsfdkj");
        $this->setData($role);
        return $this->sendApiResonse();
    }
  

}
