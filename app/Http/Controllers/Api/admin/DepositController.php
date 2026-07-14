<?php

namespace App\Http\Controllers\Api\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use App\Models\Deposit;
use App\Models\Package;
use App\Models\Transaction;
use App\Services\Users\UserWalletService;

class DepositController extends Controller
{
    public function approve(Request $request, $depositId)
    {
        try {
            DB::beginTransaction();
            $data = $request->all();
            $deposit = Deposit::findOrFail($depositId);
            $user = $deposit->user;
            $user->load('userInfo');
            UserWalletService::ensureSynced($user->userInfo);
            UserWalletService::applyCreditToMain($user->userInfo, (float) $data['amount']);
            $user->userInfo->save();

            Transaction::create(['user_id' => $user->id, 'amount' => $data['amount'], 'type' => 'deposit', 'account_type' => 'balance','note' => 'deposit']);

            // $deposit = Deposit::findOrFail($data['id']);
            if ($deposit->plan_id) {
                $package = Package::whereId($deposit->plan_id)->first();
                if ($package) {
                    $user->plan = $package->name;
                    $user->can_upgrade = false;
                }
            }
            $deposit->status = true;
            $user->save();

            $deposit->save();
            DB::commit();
            return $this->successResponse('Successfully Approved Deposit!', $deposit);
        } catch (\Throwable $th) {
            DB::rollback();
            return $this->errorResponse($th->getMessage(), 500);
        }
    }
}
