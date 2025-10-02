<?php

namespace App\Http\Controllers\Api\admin;

use App\Http\Controllers\Controller;
use App\Models\Transaction;
use App\Models\User;
use Illuminate\Http\Request;

class TransactionsController extends Controller
{
    public function allTransactions(Request $request){
        $request->validate([
            'email' => 'required',
        ]);
        $email = $request['email'];
        $user = User::whereEmail($email)->first();
        if(!$user) {
            $dt['status'] = 'failed';
            $dt['msg'] = 'user not found';
            return response()->json($dt, 422);
        }
        $transactions = Transaction::whereUserId($user->id)->paginate(100);
        return response()->json($transactions);
    }

    public function deposits(Request $request){
        $request->validate([
            'email' => 'required',
        ]);
        $email = $request['email'];
        $user = User::whereEmail($email)->first();
        if(!$user) {
            $dt['status'] = 'failed';
            $dt['msg'] = 'user not found';
            return response()->json($dt, 422);
        }
        $transactions = Transaction::whereUserId($user->id)->whereType('deposit')->paginate(100);
        return response()->json($transactions);
    }

    public function bonus(Request $request){
        $request->validate([
            'email' => 'required',
        ]);
        $email = $request['email'];
        $user = User::whereEmail($email)->first();
        if(!$user) {
            $dt['status'] = 'failed';
            $dt['msg'] = 'user not found';
            return response()->json($dt, 422);
        }
        $transactions = Transaction::whereUserId($user->id)->whereType('bonus')->paginate(100);
        return response()->json($transactions);
    }

    public function withdrawals(Request $request){
        $request->validate([
            'email' => 'required',
        ]);
        $email = $request['email'];
        $user = User::whereEmail($email)->first();
        if(!$user) {
            $dt['status'] = 'failed';
            $dt['msg'] = 'user not found';
            return response()->json($dt, 422);
        }
        $transactions = Transaction::whereUserId($user->id)->whereType('withdrawals')->paginate(100);
        return response()->json($transactions);
    }
}
