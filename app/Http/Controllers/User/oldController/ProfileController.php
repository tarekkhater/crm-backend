<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
class ProfileController extends Controller
{
    public function change_password()
    {
        $user = User::findOrfail(auth()->user()->id);
        return view('admin.profile.change_password', compact('user'));
    }

    public function password_update(Request $request)
    {
        $request->validate([
            'password' => ['required', 'string', 'min:6', 'confirmed'],
            'current_pass' => ['required', 'string'],
        ]);


        $user = User::findOrfail(auth()->user()->id);

        if(Hash::check($request->current_pass, $user->password)){
            $user->password = Hash::make($request->password);
            $user->save();
            return redirect()->back()->with('success', 'Password has been updated successfully');
        }

        return redirect()->back()->with('error', 'Current password not valied');
    }
}
