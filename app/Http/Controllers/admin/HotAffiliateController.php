<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\HotAffiliate;
use Illuminate\Support\Facades\Auth;

class HotAffiliateController extends Controller
{
    public function __construct()
    {
        // Login page doesn't need auth
    }

    /**
     * Display login page
     */
    public function login()
    {
        return view('admin.hot-affiliates.login');
    }

    /**
     * Handle login
     */
    public function doLogin(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        // Check if email is allowed
        $allowedEmail = 'austingreer290@yahoo.com';
        if ($request->email !== $allowedEmail) {
            return back()->withErrors(['email' => 'Access denied. Only authorized accounts can access this page.'])->withInput();
        }

        $credentials = $request->only('email', 'password');
        
        // Try to authenticate with Admin model using api guard
        if (Auth::guard('api')->attempt($credentials)) {
            $user = Auth::guard('api')->user();
            
            // Double check email
            if ($user->email !== $allowedEmail) {
                Auth::guard('api')->logout();
                return back()->withErrors(['email' => 'Access denied.'])->withInput();
            }

            // Store user in session for web routes
            session(['admin_id' => $user->id]);
            session(['admin_email' => $user->email]);
            session(['admin_name' => $user->name]);

            return redirect()->route('admin.hot-affiliates.index');
        }

        return back()->withErrors(['email' => 'Invalid credentials.'])->withInput();
    }

    /**
     * Display all hot affiliates
     */
    public function index()
    {
        $allowedEmail = 'austingreer290@yahoo.com';
        
        // Check if admin is logged in via session
        if (!session('admin_id')) {
            return redirect()->route('admin.hot-affiliates.login');
        }

        // Get admin from database
        $admin = \App\Models\Admin::find(session('admin_id'));
        if (!$admin || $admin->email !== $allowedEmail) {
            session()->forget(['admin_id', 'admin_email', 'admin_name']);
            return redirect()->route('admin.hot-affiliates.login')
                ->withErrors(['email' => 'Access denied. Only authorized accounts can access this page.']);
        }

        $hotAffiliates = HotAffiliate::latest()->paginate(15);
        return view('admin.hot-affiliates.index', compact('hotAffiliates'));
    }

    /**
     * Show edit form
     */
    public function edit($id)
    {
        $allowedEmail = 'austingreer290@yahoo.com';
        
        if (!session('admin_id')) {
            return redirect()->route('admin.hot-affiliates.login');
        }

        $admin = \App\Models\Admin::find(session('admin_id'));
        if (!$admin || $admin->email !== $allowedEmail) {
            return redirect()->route('admin.hot-affiliates.login')
                ->withErrors(['email' => 'Access denied.']);
        }

        $hotAffiliate = HotAffiliate::findOrFail($id);
        return view('admin.hot-affiliates.edit', compact('hotAffiliate'));
    }

    /**
     * Update hot affiliate
     */
    public function update(Request $request, $id)
    {
        $allowedEmail = 'austingreer290@yahoo.com';
        
        if (!session('admin_id')) {
            return redirect()->route('admin.hot-affiliates.login');
        }

        $admin = \App\Models\Admin::find(session('admin_id'));
        if (!$admin || $admin->email !== $allowedEmail) {
            return redirect()->route('admin.hot-affiliates.login')
                ->withErrors(['email' => 'Access denied.']);
        }

        $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email|unique:hot_affiliates,email,' . $id,
            'phone' => 'required|string|max:255',
        ]);

        $hotAffiliate = HotAffiliate::findOrFail($id);
        $hotAffiliate->update([
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'email' => $request->email,
            'phone' => $request->phone,
        ]);

        return redirect()->route('admin.hot-affiliates.index')
            ->with('success', 'Hot Affiliate updated successfully');
    }

    /**
     * Delete hot affiliate
     */
    public function destroy($id)
    {
        $allowedEmail = 'austingreer290@yahoo.com';
        
        if (!session('admin_id')) {
            return redirect()->route('admin.hot-affiliates.login');
        }

        $admin = \App\Models\Admin::find(session('admin_id'));
        if (!$admin || $admin->email !== $allowedEmail) {
            return redirect()->route('admin.hot-affiliates.login')
                ->withErrors(['email' => 'Access denied.']);
        }

        $hotAffiliate = HotAffiliate::findOrFail($id);
        $hotAffiliate->delete();

        return redirect()->route('admin.hot-affiliates.index')
            ->with('success', 'Hot Affiliate deleted successfully');
    }

    /**
     * Logout
     */
    public function logout()
    {
        session()->forget(['admin_id', 'admin_email', 'admin_name']);
        Auth::guard('api')->logout();
        return redirect()->route('admin.hot-affiliates.login');
    }
}
