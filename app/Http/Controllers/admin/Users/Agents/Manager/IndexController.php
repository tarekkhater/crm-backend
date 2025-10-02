<?php

namespace App\Http\Controllers\admin\Users\Agents\Manager;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\AgentUser;
use App\Models\Admin;
use App\Models\AgentNotes;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Exceptions;
use Illuminate\Support\Facades\Hash;
class IndexController extends Controller
{

    public function all(){
        $users = Admin::where('type_id',4)->get();
        $this->setData($users);
        $this->setMessage("success");
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
    public function index(Request $request){
        $users = Admin::where('type_id',4)->paginate(15);
        $this->setData($users);
        $this->setMessage("success");
        return $this->sendApiResonse();
    }


    public function show($id){

        $users = Admin::with(['employee.user','clientsmanager.user'])->where('id',$id)->first();
        $this->setData($users);
        $this->setMessage("success");
        return $this->sendApiResonse();
    }


    public function store(Request $request){
        // $this->validate($request, [
        //     'name' => ['required', 'string', 'max:255'],
        //     'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
        //     'branch_id' => ['required', 'numeric', 'exists:branchs,id'],
        //     'password' => ['required', 'string', 'min:6','required_with:confirm_password','same:confirm_password','min:6'],             // must be at least 10 characters in length
        // ]);
        try{
            $data = $request->all();
            $user = Admin::create([
                'name' => $data['name'],
                'email' => $data['email'],
                'branch_id' => $data['source'],
                'type_id' => 4,
                'sub_type_id' => $data['type_id'],
                'password' => Hash::make($data['password']),
                'pass' => $data['password'],
                'image' => 'faild',
            ]);
            $user->roles()->create([
                'role_id'=>$data['role'],
                'user_type'=>'App\Models\Admin',
            ]);

        $this->setMessage("success");
        return $this->sendApiResonse();
        }catch(Exceptions $e){
            return $e;
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
        $pluckId = AgentUser::where('agent_type','1')->pluck('user_id');
        $users = User::whereNotIn('id',$pluckId)->where('type_id',2)->get();
        $this->setData($users);
        $this->setMessage("success");
        return $this->sendApiResonse();
    }

    public function assignTeamLeader(Request $request){
        $request->validate([
            'users'=>'required|array',
            'users.*'=>'required|numeric|exists:users,id',
            'account'=>'required|numeric',
        ]);

        foreach($request->users as $id){
            $user = User::find((int)$id);
            $user->AgentUser()->create([
                'agent_id'=>(int)$request->account,
                'agent_type'=>'1',
                'status_id'=>3,
            ]);
        }


        $this->setMessage("success");
        return $this->sendApiResonse();
    }




    public function getClints($id){
        $user = AgentUser::find($id);
        $user->load(['users']);

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
            'agent_id'=>'required|numeric|exists:users,id',
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
        foreach($request->ids as $value){
            $users = Admin::find($value);
            $users->delete();
        }
        $this->setMessage("success");
        return $this->sendApiResonse();
    }
}
