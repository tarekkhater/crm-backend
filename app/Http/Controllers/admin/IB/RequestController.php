<?php

namespace App\Http\Controllers\admin\IB;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\IBRequest;
use Illuminate\Support\Facades\Hash;
use App\Models\Admin;
use App\Models\User;
use Illuminate\Support\Facades\Schema;
class RequestController extends Controller
{

    public function index(Request $request){
        $user = IBRequest::wherestatus('0')->paginate();
        $user->load(['user.TradingAccount']);
        $this->setData($user);
        $this->setMessage("success");
        return $this->sendApiResonse();
    }


    public function ChangeStatus(Request $request){
        $request->validate([
            'id'=>"required|numeric|exists:i_b_requests,id",
            'type'=>"required|in:1,0",
        ]);
        $IBRequest = IBRequest::with(['user'])->whereId($request->id)->first();
        if($IBRequest){
            $IBRequest->update([
                'status'=>$request->type,
            ]);
        }


        if($request->type == '1'){

            $ib = Admin::create([
                'name'=>$IBRequest->user->name,
                'surname'=>$IBRequest->user->name,
                'email'=>$IBRequest->user->email,
                'password'=>Hash::make($IBRequest->user->pass),
                'pass'=>$IBRequest->user->pass,
                'image'=>"Faild",
                'type_id'=>5,
            ]);
            $ib->IB()->create([
                'currency'=>"USD",
                'balance'=>0,
                'comission_id'=>null
            ]);
            $ib->roles()->create([
                'role_id'=>39,
                'user_type'=>'App\Models\Admin',
            ]);
            // sendMessage([

            // ]);
            // $IBRequest->delete();
        }
        // sendMessage([

        // ]);
        $this->setMessage("success");
        return $this->sendApiResonse();
    }

    public function Filter(Request $request){
        $data = [];
        $ibs = IBRequest::pluck('user_id');
        $users = User::query();
        $columns = Schema::getColumnListing('users');
        foreach($columns as $column){
            $users->orWhere($column,'LIKE','%'.$request->input.'%');
        }
        $users = $users->whereIn('id', $ibs)->where($data)->paginate(15);
        $this->setData($users);
        $this->setMessage("success");
        return $this->sendApiResonse();
    }
}
