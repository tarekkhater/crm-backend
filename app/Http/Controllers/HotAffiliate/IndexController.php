<?php

namespace App\Http\Controllers\HotAffiliate;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\HotAffiliate;
use Illuminate\Support\Facades\Validator;

class IndexController extends Controller
{
    /**
     * عرض جميع السجلات
     */
    public function index(Request $request)
    {
        $hotAffiliates = HotAffiliate::latest()->paginate(15);
        
        $this->setMessage("success");
        $this->setData($hotAffiliates);
        return $this->sendApiResonse();
    }

    /**
     * عرض سجل محدد
     */
    public function show($id)
    {
        $hotAffiliate = HotAffiliate::find($id);
        
        if (!$hotAffiliate) {
            $this->setStatus(404);
            $this->setMessage("Hot Affiliate not found");
            return $this->sendApiResonse();
        }

        $this->setMessage("success");
        $this->setData($hotAffiliate);
        return $this->sendApiResonse();
    }

    /**
     * إنشاء سجل جديد
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email',
            'phone' => 'required|string|max:255',
        ]);

        if ($validator->fails()) {
            $this->setStatus(422);
            $this->setMessage("Validation failed");
            $this->setData(['errors' => $validator->errors()]);
            return $this->sendApiResonse();
        }

        // Get user IP
        $userIp = $request->ip();

        // Check if email already exists
        $existingAffiliate = HotAffiliate::where('email', $request->email)->first();

        if ($existingAffiliate) {
            // Increment count
            $existingAffiliate->increment('count');
            $existingAffiliate->user_ip = $userIp;
            $existingAffiliate->save();

            $this->setMessage("Hot Affiliate updated successfully (count increased)");
            $this->setData($existingAffiliate);
            return $this->sendApiResonse();
        }

        // Create new record
        $hotAffiliate = HotAffiliate::create([
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'email' => $request->email,
            'phone' => $request->phone,
            'count' => 1,
            'user_ip' => $userIp,
        ]);

        $this->setMessage("Hot Affiliate created successfully");
        $this->setData($hotAffiliate);
        return $this->sendApiResonse();
    }

    /**
     * تحديث سجل موجود
     */
    public function update(Request $request, $id)
    {
        $hotAffiliate = HotAffiliate::find($id);
        
        if (!$hotAffiliate) {
            $this->setStatus(404);
            $this->setMessage("Hot Affiliate not found");
            return $this->sendApiResonse();
        }

        $validator = Validator::make($request->all(), [
            'first_name' => 'sometimes|required|string|max:255',
            'last_name' => 'sometimes|required|string|max:255',
            'email' => 'sometimes|required|email|unique:hot_affiliates,email,' . $id,
            'phone' => 'sometimes|required|string|max:255',
        ]);

        if ($validator->fails()) {
            $this->setStatus(422);
            $this->setMessage("Validation failed");
            $this->setData(['errors' => $validator->errors()]);
            return $this->sendApiResonse();
        }

        $hotAffiliate->update($request->only(['first_name', 'last_name', 'email', 'phone']));

        $this->setMessage("Hot Affiliate updated successfully");
        $this->setData($hotAffiliate);
        return $this->sendApiResonse();
    }

    /**
     * حذف سجل
     */
    public function destroy($id)
    {
        $hotAffiliate = HotAffiliate::find($id);
        
        if (!$hotAffiliate) {
            $this->setStatus(404);
            $this->setMessage("Hot Affiliate not found");
            return $this->sendApiResonse();
        }

        $hotAffiliate->delete();

        $this->setMessage("Hot Affiliate deleted successfully");
        return $this->sendApiResonse();
    }
}

