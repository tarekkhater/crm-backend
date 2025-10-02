<?php

namespace App\Http\Controllers\admin\landing;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\User;
use App\Models\Admin;
use App\Models\Identity;
use App\Models\TradingAccount;
use App\Models\Trade;
use App\Models\Deposit;
use App\Models\Withdrawal;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Spatie\Permission\Models\Role;

class IndexController extends Controller
{
    public function index(Request $request){
         $validated = $request->validate([
        'name' => 'required|string|max:255', // Name is required and should be a string
        'surname' => 'nullable|string|max:255', // Surname is optional but should be a string if provided
        'email' => 'required|email|unique:users,email', // Email is required, must be valid, and unique in the users table
        'phone' => 'required|string|max:15|unique:users,phone', // Phone is optional but should be a string (max 15 characters)
        
    ]);
        $data = $request->all();
        
        $user = User::create([
            'name' => $data['name'],
            'surname' => $data['surname']??$data['name'],
            'email' => $data['email'],
            'phone' => $data['phone'],
            'type_id'=>1,
        ]);
        $user->userInfo()->create([
            'source_id' => $data['source']??4,
            'status_id' => $data['status']??3,
            'branch_id' => $data['branch']??null,
            'plan_id' => $data['plan']??null,
            'profit' => $data['profit'] ?? '0',
            'fee' => $data['fee'] ?? '0',
        ]);
        $role = Role::find(8);
        $user->assignRole($role);
        
            $manager = Admin::find(145);
            $user->Manager()->create([
                'admin_id'=>(int)$manager->id,
            ]);
            if($manager->broker_id > 0 ){
                $user->broker_id = $manager->broker_id;
                $user->save();
            }
       
        $this->setMessage("Your data has been registered.");
        return $this->sendApiResonse();
    }



}
