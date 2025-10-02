<?php

namespace App\Http\Controllers\admin\CRM\Api\Integrations;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use Illuminate\Http\Request;
use App\Models\Integration;
use Illuminate\Support\Facades\Hash;
use Tymon\JWTAuth\Facades\JWTAuth;

class IndexController extends Controller
{

    public function __construct() {
    }

    public function index(){
        $data = Integration::with(['user'])->get();
        $this->setData($data);
        $this->setMessage("success");
        return $this->sendApiResonse();
    }


    public function store(Request $request){

        // $request->validate([

        // ]);

        $data = $request->all();
        $user = Admin::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'branch_id' => $data['source'],
            'type_id' => $data['type_id'],
            'password' => Hash::make($data['password']),
            'pass' => $data['password'],
            'image' => 'faild',
        ]);
        $user->roles()->create([
            'role_id'=>$data['role'],
            'user_type'=>'App\Models\Admin',
        ]);
        $user->Integration()->create([
            'number_leads'=>$data['number'],
            'token'=>JWTAuth::fromUser($user),
        ]);

        $this->setMessage("success");
        return $this->sendApiResonse();
    }


    public function ChangeStatus(Request $request){
        $integration = Integration::find($request->id);
        $integration->expired = 1;
        $integration->save();
        $this->setMessage("success");
        return $this->sendApiResonse();
    }





}
