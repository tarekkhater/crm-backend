<?php 
namespace App\Http\Controllers\Integrations;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Lead;

class LeadController extends Controller
{
    public function store(Request $request)
    {
        $data = $request->validate([
            'lead_id' => 'required|integer|unique:leads,lead_id',
            'first_name' => 'nullable|string',
            'last_name' => 'nullable|string',
            'phone' => 'nullable|string',
            'email' => 'nullable|email',
            'registration_date' => 'nullable|date',
            'last_comment_date' => 'nullable|date',
            'last_comment' => 'nullable|string',
            'stage_manager' => 'nullable|string',
            'total_deposit' => 'nullable|numeric',
            'amount' => 'nullable|numeric',
            'balance' => 'nullable|numeric',
            'country' => 'nullable|string',
            'account_type' => 'nullable|in:Customer,Depositor',
            'lead_status' => 'nullable|string',
            'reten_status' => 'nullable|string',
            'ftd_date' => 'nullable|date',
            'deposit_count' => 'nullable|integer',
            'lead_source' => 'nullable|string',
        ]);

        // Create the lead
        $lead = Lead::create($data);
        
        
        return response()->json([
            'success' => true,
            'lead' => $lead
        ], 201);
    }
    
    public function storeFTD(Request $request)
    {
        $data = $request->validate([
            'lead_id' => 'required|integer|unique:leads,lead_id',
            'first_name' => 'nullable|string',
            'last_name' => 'nullable|string',
            'phone' => 'nullable|string',
            'email' => 'nullable|email',
            'registration_date' => 'nullable|date',
            'last_comment_date' => 'nullable|date',
            'last_comment' => 'nullable|string',
            'stage_manager' => 'nullable|string',
            'total_deposit' => 'nullable|numeric',
            'amount' => 'nullable|numeric',
            'balance' => 'nullable|numeric',
            'country' => 'nullable|string',
            'account_type' => 'nullable|in:Customer,Depositor',
            'lead_status' => 'nullable|string',
            'reten_status' => 'nullable|string',
            'ftd_date' => 'nullable|date',
            'deposit_count' => 'nullable|integer',
            'lead_source' => 'nullable|string',
            
        ]);

        $data['type'] = "ftd";
        // Create the lead
        $lead = Lead::create($data);
        
        
        return response()->json([
            'success' => true,
            'lead' => $lead
        ], 201);
    }
}
