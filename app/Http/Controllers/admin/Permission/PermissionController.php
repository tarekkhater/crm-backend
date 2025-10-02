<?php

namespace App\Http\Controllers\admin\Permission;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\Permission\PermissionServices;
use App\Http\Resources\Admin\Permissions\PermissionAllResource;
use App\Models\Permission;
use Illuminate\Support\Facades\Auth;
use App\Http\Resources\Admin\Permissions\PermissionResource;
class PermissionController extends Controller
{
    public $Permission;
    public function __construct() {
        $this->Permission = new PermissionServices();
    }
   
    public function PermissionsUser()
    {
        // Use Laravel's built-in method to get the authenticated admin
        $user = Auth::guard('api')->user(); // Get the authenticated user

        // Check if the user is authenticated
        if (!$user) {
            $this->setStatus(401); // Unauthorized
            $this->setMessage("Unauthorized access");
            return $this->sendApiResonse();
        }

        // Ensure roles and permissions are loaded
        
        // Set data and return the API response
         
        $this->setData(new PermissionResource($user->getAllPermissions()));
        $this->setMessage("success");
        return $this->sendApiResonse();
    }
    
    public function AllPermissionsUser()
    {
        // Use Laravel's built-in method to get the authenticated admin
        $user = Auth::guard('api')->user(); // Get the authenticated user

        // Check if the user is authenticated
        if (!$user) {
            $this->setStatus(401); // Unauthorized
            $this->setMessage("Unauthorized access");
            return $this->sendApiResonse();
        }

        // Ensure roles and permissions are loaded
        
        // Set data and return the API response
         
        $this->setData(new PermissionAllResource($user->getAllPermissions()));
        $this->setMessage("success");
        return $this->sendApiResonse();
    }


    public function index(Request $requet){
        return $this->Permission->all($requet);
        $this->setMessage("jssjfhsfdkj");
        $this->setData($this->Permission->all($requet));
        return $this->sendApiResonse();
    }
    
    public function indexagent(Request $requet){
         return $this->Permission->allAgent($requet);
    }

    public function show($id){
        $this->setMessage("jssjfhsfdkj");
        $this->setData($this->Permission->show($id));
        return $this->sendApiResonse();
    }


    public function store(request $requet){
        $this->Permission->store($requet);
        return $this->sendApiResonse();
    }

    public function update(Request $requet,$id){
        $id = $id;
        $this->Permission->update($requet,$id);
        return $this->sendApiResonse();
    }


    public function destroy(Request $requet){
        $this->Permission->destroy($requet);
        return $this->sendApiResonse();
    }

}
