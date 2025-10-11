<?php

namespace App\Http\Controllers\Integrations;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User as Lead;
    use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use DB;
use App\Models\Source;
use App\Models\Admin;
use Tymon\JWTAuth\Facades\JWTAuth;
class AffilatorController extends Controller
{
    // Simulate login with Admin


    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|string',
        ]);
    
        // Find admin by email
        $admin = Admin::where('email', $request->email)->first();
    
        // Check if admin exists and password is correct
        if (!$admin || !Hash::check($request->password, $admin->password)) {
            return response()->json(['message' => 'Invalid credentials'], 401);
        }
        
        
        // Generate Sanctum token
        $raw = $admin->id . '|' . $admin->email . '|' . now()->timestamp;
        $token = md5($raw);
        $admin->token_affilator = $token;
        $admin->save();
        return response()->json([
            'message' => 'Logged in successfully',
            'admin_id' => $admin->id,
            'token' => $token
        ]);
    }


    // Save new Lead
    public function lead(Request $request)
    {
        $validated = $request->validate([
            'name'    => 'required|string',
            'surname' => 'required|string',
            'email'   => 'required|email|unique:users,email,NULL,id,deleted_at,NULL',
            'Phone'   => 'required|string|unique:users,phone,NULL,id,deleted_at,NULL',
            'Country' => 'required|string|max:2',
            'Source'  => 'nullable|string',
            'campaign'  => 'nullable|string',
            'ID'      => 'required|integer',
            'Token'   => 'required|string',
        ]);
        
            $adminId = $validated['ID'];
            $token   = $validated['Token'];

   
    

    // Fetch token record from DB
    $tokenRecord = DB::table('admins')
        ->where('token_affilator', $token)
        ->first();

    if (!$tokenRecord) {
        return response()->json(['message' => 'Invalid token or ID'], 403);
    }

    // Optional: verify admin exists and is admin
    $admin = Admin::find($adminId);
    if (!$admin) {
        return response()->json(['message' => 'Unauthorized admin'], 403);
    }
    
        $country = DB::table('countries')
             ->where('iso', 'like',$validated['Country'])
             ->first();
        if (!$country) {
            $country = DB::table('countries')
                        ->where('name', 'like', '%' . $validated['Country'] . '%')
                        ->first();
        }

        $lead = Lead::create([
         
            'name'    => $validated['name'],
            'surname' => $validated['surname'],
            'email'   => $validated['email'],
            'phone'   => $validated['Phone'],
            'country' => $country->id,
            'source'  => $validated['Source'] ?? 'unknown',
            'campaign'  => $validated['campaign'] ?? 'unknown',
            'type_id'=>2,
            'created_by' =>$admin->id,
        ]);
        
        $lead->userInfo()->create([
                    'source_id' => $admin->source_id,
                    'status_id' =>3,
                    'branch_id' => null,
                    'plan_id' => 4,
                    'profit' =>  '0',
                    'fee' => '0',
        ]);
$token = JWTAuth::fromUser($lead);
            $autoLoginUrl = "https://trade.quantumprime.app/login?token=$token";
        return response()->json([
            'message' => 'Lead saved successfully',
            'autologin'=>$autoLoginUrl,
            'lead_id' => $lead->id,
        ]);
    }

    // Get all leads
    public function GetLeads(Request $request)
    {
        $token = $request->token;
         $tokenRecord = DB::table('admins')
        ->where('token_affilator', $token)
        ->first();

    if (!$tokenRecord) {
        return response()->json(['message' => 'Invalid token or ID'], 403);
    }
    $adminId = $request->id;
    // Optional: verify admin exists and is admin
    $admin = Admin::find($adminId);
    if (!$admin) {
        return response()->json(['message' => 'Unauthorized admin'], 403);
    }
    
    $from = $request->date_from; // expected format: 'YYYY-MM-DD'
    $to = $request->date_to;     // expected format: 'YYYY-MM-DD'

$query = Lead::with(['userInfo.status', 'countries'])
    ->where('created_by', $request->id);

// Apply created_at filter if provided
if ($from && $to) {
    $query->whereBetween('created_at', [$from, $to]);
} elseif ($from) {
    $query->whereDate('created_at', '>=', $from);
} elseif ($to) {
    $query->whereDate('created_at', '<=', $to);
}



$leads = $query->latest()->get();


        // $leads = Lead::with(['userInfo.status','countries'])->where('created_by',$request->id)->latest()->get();
        $data = [];
        foreach ($leads as $lead){
            $token = JWTAuth::fromUser($lead);
            $autoLoginUrl = "https://trade.quantumprime.app/login?token=$token";
            $data[]= [
                "lead_id"=> $lead->id,
                "name"=> $lead->name,
                "surname"=> $lead->surname,
                "email"=> $lead->email,
                "phone"=> $lead->phone,
                'autologin_url' => $autoLoginUrl,
                "country"=> $lead->countries?$lead->countries->name : "no have country",
                "source"=> $lead->userInfo->source->name ?? 'no status'?? $lead->source,
                "status"=> $lead->userInfo->status->name ?? 'no status',
                "campaign"=> $lead->campaign ?? 'no campaign',
            
            ];
        }

        return response()->json([
            'count' => $leads->count(),
            'leads' => $data
        ]);
    }

    // Placeholder for GetDeposits
    public function affilator(Request $request)
    {
        // Sample response — replace with real deposit logic
        return response()->json([
            'deposits' => [
                ['lead_id' => 1, 'amount' => 200, 'currency' => 'USD'],
                ['lead_id' => 2, 'amount' => 150, 'currency' => 'EUR'],
            ]
        ]);
    }
}
