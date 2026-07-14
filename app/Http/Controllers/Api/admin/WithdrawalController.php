<?php

namespace App\Http\Controllers\Api\admin;

use App\Http\Controllers\Controller;
use App\Models\Withdrawal;
use App\Services\Users\UserWalletService;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class WithdrawalController extends Controller
{
    public function respond (Request $request, $withdrawalId) {
        $this->validate($request, [
            'status' => 'required|in:approved,declined',
            'nullable' => 'nullable|string'
        ]);

        try {
            DB::beginTransaction();
            $data = $request->all();
            $withdrawal = Withdrawal::findOrFail($withdrawalId);

            $user = $withdrawal->user;

            $wd = $withdrawal;
            $wd['status'] = $data['status'];
            $wd['note'] = $data['note'];

            if($data['status'] == 'approved'){
                if($wd->approved < 1){
                    $user->load('userInfo');
                    if (!$user->userInfo || !UserWalletService::applyDebit($user->userInfo, (float) $wd->amount)) {
                        DB::rollback();
                        return response()->json(['message' => 'Insufficient balance for withdrawal'], 422);
                    }
                    $user->userInfo->save();
                }
                $wd->approved = 1;
            }

            $wd->save();

            DB::commit();
            return $this->successResponse('Successfully Updated Withdrawal!', $wd);
        } catch (\Throwable $th) {
            DB::rollback();
            return response()->json(['message' => $th->getMessage()], 500);
        }
    }

    public function process(Request $request, $withdrawalId)
    {
        $withdrawal = Withdrawal::findOrFail($withdrawalId);
        $withdrawal->processed = true;
        $withdrawal->save();
        return $this->successResponse('Transfer pending!');
    }

    public function allWithdrawals(Request $request) {
        if($request->has('status')){
            $status = $request->get('status');
            $withdrawals = Withdrawal::with('user')->whereApproved($status)->latest()->get();
        }else{
            $withdrawals = Withdrawal::with('user')->latest()->get();
        }
        return $this->successResponse('Withdrawals Fetched!', $withdrawals);
    }
}
