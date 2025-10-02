<?php

namespace App\Http\Controllers\admin\CRM\Api\Users\Account;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;



class indexController extends Controller
{



    public function updateimage(Request $request){
        $this->validate($request, [
            'id' => ['required', 'numeric','exists:users,id'],
            'file' => ['required', 'mimes:jpg,png'],
        ]);
        try{
            $user = User::find($request->id);
            $data = $request->all();
            
            $user->update([
                'avatar' => $this->uploadImage($data['file'],'users/'),
            ]);
            $this->setMessage("success");
            return $this->sendApiResonse();
        }catch(Exceptions $e){
            return $e;
        }

    }
    
    
     function uploadImage($file,$path)
    {
        
            $fileName = time().'_'.$file->getClientOriginalName();
            $filePath = $file->storeAs('storage/'.$path, $fileName, 'public');
             return $filePath;
            
            
        // $image = explode('base64,',$base64String);
        // $image = end($image);
        // $image = str_replace(' ', '+', $image);
        // $file =  uniqid() . '.png';

        // Storage::disk('public')->put("storage/".$path .$file,base64_decode($image));

        // return $path.$file;
    }

    public function update(Request $request){
        $this->validate($request, [
            'id' => ['sometimes', 'numeric', 'exists:users,id'],
            'name' => ['sometimes', 'string', 'max:255'],
            'surname' => ['sometimes', 'string', 'max:255'],
            'email' => ['sometimes','email'],
            'birth' => ['sometimes','date'],
            'country' => ['sometimes'],
            'phone' => ['sometimes'],
            'address' => ['sometimes'],
            'permanent_address' => ['nullable'],
        ]);
        try{
            $user = User::find($request->id);
            if($request->email != $user->email){
               $this->validate($request, [
                    'email' => ['required', 'email','unique:users,email'],
                ]); 
            }
            $data = $request->all();
            
            $user->update([
                    'name' => $data['name'],
                    'surname' => $data['surname'],
                    'email' => $data['email'],
                    // 'phone_code' => $data['phone_code'],
                    'country' => $data['country'],
                    'phone' => $data['phone'],
                    'address' => $data['address'],
                    'permanent_address' => $data['permanent_address'],
                    'birth'=>$data['birth'],
                ]);


            $this->setMessage("success");
            return $this->sendApiResonse();
        }catch(Exceptions $e){
            return $e;
        }

    }
    
    
  




    

    


}
