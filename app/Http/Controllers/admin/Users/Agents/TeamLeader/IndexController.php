<?php

namespace App\Http\Controllers\admin\Users\Agents\TeamLeader;

use App\Http\Controllers\Controller;
use App\Models\AgentNotes;
use Illuminate\Http\Request;
use App\Models\AgentUser;
use App\Models\TeamleaderNotes;
use App\Models\Admin;
use App\Models\User;
use App\Models\UserManager;
use Carbon\Carbon;
use Exception;
use App\Exports\UsersAgentsExport;
use Illuminate\Support\Facades\Exceptions;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Validator;
use App\Http\Requests\Admin\Agent\StoreRequest;
use App\Http\Requests\Admin\Agent\UpdateRequest;
use App\Services\Users\Agents\IndexSearchServices;
use App\Services\Users\Agents\IndexFilterServices;
use Illuminate\Support\Facades\Mail;
use App\Mail\Files\FileSendUsers;
use Illuminate\Support\Facades\Storage;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\DB;

class IndexController extends Controller
{

    public $searchAgents ,$filterAgent;
    public function __construct()
    {

        $this->searchAgents = new IndexSearchServices();
        $this->filterAgents = new IndexFilterServices();

        
    }
    public function all(Request $request){

        $ids   = getTeamLeaderIds();
        $query = Admin::whereIn('id', $ids)->select(
            'id',
            DB::raw("CONCAT(name, ' ', surname) as email"),
            'country'
        );

        switch ((string) $request->type) {
            case '1':
                // Conversion team leaders
                $users = (clone $query)->where('type_id', 6)->where('sub_type_id', 7)->get();
                break;

            case '7':
                if ((int) auth()->user()->type_id === 6) {
                    // Team leader: self + agents on their desk
                    $users = (clone $query)->whereIn('type_id', [6, 7, 8])->get();
                } elseif (in_array((int) auth()->user()->type_id, [7, 8], true)) {
                    // Agent: only themselves
                    $users = (clone $query)->get();
                } else {
                    // Super admin / desk manager: conversion TLs + conversion agents
                    $users = (clone $query)->where(function ($q) {
                        $q->where(function ($inner) {
                            $inner->where('type_id', 6)->where('sub_type_id', 7);
                        })->orWhere('type_id', 7);
                    })->get();
                }
                break;

            default:
                if ((int) auth()->user()->type_id === 3) {
                    // Admin / desk manager: conversion TLs + conversion agents
                    $users = (clone $query)->where(function ($q) {
                        $q->where(function ($inner) {
                            $inner->where('type_id', 6)->where('sub_type_id', 7);
                        })->orWhere('type_id', 7);
                    })->get();
                } elseif (in_array((int) auth()->user()->type_id, [7, 8], true)) {
                    // Agent: only themselves
                    $users = (clone $query)->get();
                } else {
                    // Retention team leaders
                    $users = (clone $query)->where('type_id', 6)->where('sub_type_id', 8)->get();
                }
                break;
        }

        $this->setData($users);
        $this->setMessage("success");
        return $this->sendApiResonse();
    }
    public function show($id){
        $users = Admin::with(['employee.user','clients.user'])->where('id',$id)->first();
        $this->setData($users);
        $this->setMessage("success");
        return $this->sendApiResonse();
    }

    public function index(Request $request){


        if($request->type ==0){
                $ids = getTeamLeaderIds();
                $users = Admin::whereIn('id',$ids)->where('type_id',6)->where('sub_type_id',7)->with(['broker'])->paginate(15);
                $this->setData($users);

        }else{
           if($request->type ==4){
                $ids = getTeamLeaderIds();
                $users = Admin::whereIn('id',$ids)->where('type_id',3)->where('sub_type_id',4)->with(['broker'])->paginate(15);
                $this->setData($users); 
           }else{
                 $ids = getTeamLeaderIds();
                $users = Admin::whereIn('id',$ids)->where('type_id',6)->where('sub_type_id',8)->with(['broker'])->paginate(15);
                $this->setData($users);
           }
              
            
               
        }

        $this->setMessage("success");
        return $this->sendApiResonse();
    }

    public function ExportAgents(Request $request){
            $request->validate(['type' => 'required|in:0,1,2,3,4']);
            $timestamp = now()->format('Y-m-d_H-i-s');
            $fileName = "Agents_Export_{$timestamp}.xls";
            $filePath = "upload/excel/export/Agents/{$fileName}";
            $users = new UsersAgentsExport($request->type);
            $stored = Excel::store($users, $filePath, 'public');
            if (!$stored || !\Storage::disk('public')->exists($filePath)) {
                throw new \Exception("Failed to create export file at path: {$filePath}");
            }
            $user = auth()->user();
            Mail::to("$user->email")->send(new FileSendUsers($users,$filePath));
            $this->setMessage("success،File");
            Storage::disk('public')->delete($filePath);
            return $this->sendApiResonse();
    }
    public function ExportLeads(Request $request)
    {
        // Validate the 'type' parameter from the request
        $request->validate(['type' => 'required|in:0,1,2,5,9']);

        // Generate a timestamp for the filename
        $timestamp = now()->format('Y-m-d_H-i-s');
        $fileName = "Users_Export_{$timestamp}.xlsx"; // Use xlsx extension

        // Define the path for storing the file
        $filePath = 'public/excel/export/' . $fileName; // Correct the file path

        try {
            // Get the filtered users by 'type' using the method defined in the Export class
            $stored = Excel::store(new UsersClientExport($request->type), $filePath);

            // Check if the file was successfully stored
            if (!$stored || !\Storage::disk('public')->exists($filePath)) {
                throw new \Exception("Failed to create export file at path: {$filePath}");
            }

            // Return the success message and file path
            return response()->json([
                'message' => 'Export successful',
                'file_path' => \Storage::url($filePath) // Return the public URL
            ], 200);

        } catch (\Exception $e) {
            // Handle exceptions and errors
            return response()->json([
                'message' => 'Failed to export data',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
    public function assignAgent(Request $request){
        $request->validate([
            'users'=>'required|array',
            'users.*'=>'required|numeric|exists:users,id',
            'account'=>'required|numeric',
        ]);

        foreach($request->users as $id){
            $user = User::find((int)$id);
            $user->AgentUser()->create([
                'agent_id'=>(int)$request->account,
                'agent_type'=>'0',
                'status_id'=>3,
            ]);
        }


        $this->setMessage("success");
        return $this->sendApiResonse();
    }

    public function store(StoreRequest $request){
        if($request->type_id == 7 || $request->type_id == 8){
              $rules = [
                    'manager_id' => 'required|numeric|exists:admins,id,type_id,6',
                ];
            $validator = Validator::make($request->all(), $rules);
            if ($validator->fails()) {
                return response()->json([
                    'message' => 'Validation error',
                    'errors' => $validator->errors()
                ], 422);
            }   
        }
        if($request->type_id == 6 ){
              $rules = [
                   'broker_id'=> ['required', 'numeric', 'exists:admins,id,type_id,5,broker_id,0']
                ];
            $validator = Validator::make($request->all(), $rules);
            if ($validator->fails()) {
                return response()->json([
                    'message' => 'Validation error',
                    'errors' => $validator->errors()
                ], 422);
            }   
        }
        
        
        
        try{
            $data = $request->all();
            $user = Admin::create([
                'name' => $data['name'],
               'surname' => $data['surname'],
                'email' => $data['email'],
                'phone' => $data['phone'],
                'country' => $data['country'],
                'type_id' => $data['type_id'],
                'sub_type_id' => isset($data['sub_type_id'])?$data['sub_type_id']:null,
                'password' => Hash::make($data['password']),
                'pass' => $data['password'],
                'desk_id' => $data['desk']??null,
                'image' => 'faild',
                'email_verified_at'=>date("Y-m-d H-i-s"),
                            // $manager = Admin::find($data['manager_id']);
                            // 'branch_id' => $manager->branch_id,
                            // 'sub_type_id' => $manager->sub_type_id,

            ]);
            $findrole = Role::find($data['role']);
            $role = Role::create([
                'name' => $data['email'],
                'guard_name' => 'api',
                'display_name' =>$data['email'],
                'description' => $data['email'],
                'type'=>$findrole->name
            ]);
            
            foreach($findrole->permissions as $permission){
                $gpermission = Permission::find($permission->id);
                $role->givePermissionTo($gpermission->name);
            }
            $user->assignRole($role);
            if(isset($data['manager_id'])){
                $user->usermanager()->create([
                'admin_id'=>$data['manager_id'],
                'type'=>'0',
            ]);
                $user->manager_id = $data['manager_id'];
                $user->broker_id = Admin::find($data['manager_id'])->broker_id;
                $user->save();
            }
             if (isset($data['broker_id'])) {
                $user->broker_id = $data['broker_id'];
                $user->save();
            }

        $this->setMessage("success");
        return $this->sendApiResonse();
        }catch(Exception $e){
            return $e;
        }

    }

    public function update(UpdateRequest $request, $id)
    {
        $user = Admin::findOrFail($id);
        if($user->email != $request->email){
            $rules = [
            'email' => 'unique:admins,email',
            ];
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validation error',
                'errors' => $validator->errors()
            ], 422);
        }   
        }
        
         if(isset($request->password)){
            $rules = [
              'password'=>[
                'required',
                'string',
                'min:8', // Minimum length
                'regex:/[A-Z]/', // At least one uppercase letter
                'regex:/[a-z]/', // At least one lowercase letter
                'regex:/[0-9]/', // At least one number
                'regex:/[@$!%*?&]/', // At least one special character
             ], // At least one special character,
            ];
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validation error',
                'errors' => $validator->errors()
            ], 422);
        }   
        }
        
        
        
      
         if($request->type_id == 7 || $request->type_id == 8){
              $rules = [
                    'manager_id' => 'required|numeric|exists:admins,id,type_id,6',
                ];
            $validator = Validator::make($request->all(), $rules);
            if ($validator->fails()) {
                return response()->json([
                    'message' => 'Validation error',
                    'errors' => $validator->errors()
                ], 422);
            }   
        }

        try {
            $data = $request->all();

            // Find the admin to update

            // Prepare the fields to update conditionally
            $updateData = [
                'name' => $data['name'] ?? $user->name,
                'surname' => $data['surname'] ?? $user->surname,
                'email' => $data['email'] ?? $user->email,
                'phone' => $data['phone'] ?? $user->phone,
                'country' => $data['country'] ?? $user->country,
                'desk_id' => $data['desk']??$user->desk_id,
                'password' => isset($data['password']) ? Hash::make($data['password']) : $user->password,
                'pass' => $data['password'] ?? $user->pass,
            ];

            // Update user data
            $user->update($updateData);

            // Handle manager association if `manager_id` is provided
            if (isset($data['manager_id'])) {
                $user->usermanager()->updateOrCreate(
                    ['admin_id' => $data['manager_id']],
                    ['type' => '0']
                );
                $user->manager_id = $data['manager_id'];
                $user->save();
            }
            
            if (isset($data['broker_id'])) {
                $user->broker_id = $data['broker_id'];
                $user->save();
            }

            // Set success message and response
            $this->setMessage("success");
            return $this->sendApiResonse();

        } catch (\Exception $e) {
            // Handle exception and return error response
            return response()->json([
                'message' => 'Update failed',
                'error' => $e->getMessage()
            ], 500);
        }
    }


    public function Blocked(Request $request){
        $user = Admin::find($request->id);
        $user->block = $user->block == '1'?'0':'1';
        $user->save();
        $this->setMessage("success");
        return $this->sendApiResonse();
    }

    public function customers(){
        $assignedIds = AgentUser::where('agent_type','1')->pluck('user_id');
        $users = User::whereIn('id', getUsersIds())
                     ->whereNotIn('id', $assignedIds)
                     ->with(['countries'])
                     ->where('type_id', 2)
                     ->get();
        $this->setData($users);
        $this->setMessage("success");
        return $this->sendApiResonse();
    }



    public function getClints($id){
        $user = AgentUser::find($id);
        $user->load(['users','users.countries']);

        $this->setData($user);
        $this->setMessage("success");
        return $this->sendApiResonse();
    }

    public function recoredNotice(Request $request){
        $request->validate([
            'user_id'=>'required|numeric|exists:users,id',
            'agent_id'=>'required|numeric|exists:agent_users,id',
            'content'=>'required|string|min:6',
        ]);
        $notes = AgentNotes::create([
            'user_id'=>$request->user_id,
            'agent_id'=>$request->agent_id,
            'content'=>$request->content,
        ]);
        $data = AgentNotes::where('agent_id',$request->agent_id)->get();
        $this->setData($data);
        $this->setMessage("success");
        return $this->sendApiResonse();
    }

    public function recoredNoticeAgent(Request $request){
        $request->validate([
            'id'=>'required|numeric|exists:admins,id',
            'content'=>'required|string|min:6',
        ]);
        $admin = Admin::find($request->id);
        if($admin && $admin->manager_id != null){
             $notes = TeamleaderNotes::create([
            'team_id'=>$admin->manager_id,
            'agent_id'=>$request->id,
            'message'=>$request->content,
        ]);
        $data = TeamleaderNotes::where('agent_id',$request->agent_id)->get();
        $this->setData($data);
        $this->setMessage("success");
        }
        $this->setStatus(422);
       $this->setMessage("error");
        return $this->sendApiResonse();
    }


    public function Verify(Request $request){
        foreach($request->ids as $value){
            Admin::where('id',$value)->update(['email_verified_at' => Carbon::now()]);
        }
        $this->setMessage("Admin\'s email verified successfully");
        return $this->sendApiResonse();
    }
    public function assignUsers(Request $request){
        $request->validate([
            'user_id'=>'required|array',
            'user_id.*'=>'required|numeric|exists:users,id',
            'agent_id'=>'required|numeric|exists:admins,id',
        ]);
        foreach($request->user_id as $value){
            $users = AgentUser::create([
                'user_id'=>$value,
                'agent_id'=>$request->agent_id,
                'status'=>'1',
            ]);
        }
        $this->setMessage("success");
        return $this->sendApiResonse();
    }


    public function DeleteClient(Request $request){
        foreach($request->ids as $value){
            $users = AgentUser::find($value);
            $users->delete();
        }
        $this->setMessage("success");
        return $this->sendApiResonse();
    }
    public function destroy(Request $request){
        $request->validate([
            'ids'=>'required|array',
            'ids.*'=>'required|numeric|exists:admins,id',
        ]);

        foreach($request->ids as $value){
            $users = Admin::find($value);
            $users->delete();
        }
        $this->setMessage("success");
        return $this->sendApiResonse();
    }


    public function FilterByText(Request $request)
    {
        // Start a query on the Admin model (or your relevant model)
        // $query = Admin::query();

        // // Apply filters conditionally using the `when` method
        // $query->when($request->filled('email'), function ($q) use ($request) {
        //     $q->where('email', 'LIKE', '%' . $request->input('email') . '%');
        // });

        // $query->when($request->filled('country'), function ($q) use ($request) {
        //     $q->where('country', 'LIKE', '%' . $request->input('country') . '%');
        // });

        // $query->when($request->filled('phone'), function ($q) use ($request) {
        //     $q->where('phone', 'LIKE', '%' . $request->input('phone') . '%');
        // });

        // $query->when($request->filled('amount'), function ($q) use ($request) {
        //     $q->where('amount', 'LIKE', '%' . $request->input('amount') . '%');
        // });

        // $query->when($request->filled('currency'), function ($q) use ($request) {
        //     $q->where('currency', 'LIKE', '%' . $request->input('currency') . '%');
        // });

        // Get the filtered results with pagination
        $users = $this->searchAgents->index($request);

        // Set data and message for API response
        $this->setData($users);
        $this->setMessage("success");

        // Return the response
        return $this->sendApiResonse();
    }


    public function Filter(Request $request){

         $users = $this->filterAgents->index($request);
        $this->setData($users);
        $this->setMessage("success");
        return $this->sendApiResonse();
    }
}
