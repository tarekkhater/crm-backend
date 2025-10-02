<?php

namespace App\Http\Controllers\Api\admin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\PlanController;
use App\Models\Deposit;
use App\Models\InvProfit;
use App\Models\Overnight;
use App\Models\Package;
use App\Models\Transaction;
use App\Models\User;
use App\Models\UserPlan;
use App\Models\Withdrawal;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminDashboardController extends Controller
{
    public function index(){

        if(check_permission('Dashboard-count-customer-manger')){
            $users = User::where('manager_id', Auth::user()->id)->whereRoleIs('user')->count();
            $online_users = User::whereDate('last_seen','>',Carbon::now())->where('manager_id', Auth::user()->id)
                ->whereRoleIs('user')->count();
            $leads = User::where('manager_id', Auth::user()->id)->whereRoleIs('lead')->count();
        }else{
            $users = User::whereRoleIs('user')->count();
            $online_users = User::whereDate('last_seen','>',Carbon::now())->whereRoleIs('user')->count();
            $leads = User::whereRoleIs('lead')->count();
        }

        $a_deposits =  Deposit::whereStatus(1)->count();
        $p_deposits =  Deposit::whereStatus(0)->count();
        $p_withdrawals =  Withdrawal::whereStatus(0)->count();
        $packages =  Package::count();

        $data =
            [
                'users' => $users,
                'online_users' => $online_users,
                'leads' => $leads,
                'a_deposits' => $a_deposits,
                'p_deposits' => $p_deposits,
                'p_withdrawals' => $p_withdrawals,
                'packages' => $packages,
            ];

        return $this->successResponse('Successful', $data);

    }

    public function overnights(){
        $items = Overnight::all();
        return $this->successResponse('Overnights Fetched', $items);
    }

    public function transactions(){
        if(check_permission('Dashboard-data-manger')){
      $users = User::where('manager_id', Auth::user()->id)->pluck('id');
            $items = Transaction::whereIn('user_id', $users)->get();
        }else {
            $items = Transaction::all();
        }
        return view('admin.transactions', compact('items'));
    }

    public function DelTrans($id){
        $item = Transaction::findOrFail($id);
        $item->delete();
        return redirect()->back()->with('success', 'Transactions successfully completed.');
    }

    public function investmentComplete($id){
        $inv = UserPlan::findOrFail($id);
        $inv->status = 2;
        $inv->save();
        return redirect()->back()->with('success', 'Investment successfully completed.');
    }
    public function investmentApprove($id){
        $inv = UserPlan::findOrFail($id);
        $inv->status = 1;
        $inv->save();
        $data['status'] = 1;
        $this->updateInvProfits($data, $id);
        return redirect()->back()->with('success', 'Investment successfully approved.');
    }

    public function investmentUpdate(Request $request){
        $id = (int) $request->id;
        $data = $this->getInvData($request);
        $inv = UserPlan::findOrFail($id);
        $inv->update($data);
        $this->updateInvProfits($data, $inv->id);
        $invProfit = InvProfit::whereUserPlanId($inv->id)->first();
        if($invProfit){
            $pData = $this->getInvProfitData($request);
            $invProfit->update($pData);
        }
        return redirect()->back()->with('success', 'User Investment successfully updated.');
    }


    public function investments(Request $request){
        $plans = Package::select('name','id')->get();
        if($request->has('status')){
            $status = $request->get('status');
            if($status){
                $title = 'Approved Deposits';
            }else{
                $title = 'Pending Deposits';
            }
            $deposits = Deposit::with('user')->whereStatus($status)->latest()->get();
        }else{
            $title = "All Investments";
            $investments = UserPlan::with('user')->latest()->get();
        }

        return view('admin.investments', compact('investments','title','plans'));
    }

    public function updateInvProfits($data, $id){
        $invProfit = InvProfit::whereUserPlanId($id)->first();
        if($invProfit){
            $invProfit->update($data);
        }
    }



    protected function getInvData(Request $request)
    {
        $rules = [
            'start_date' => 'required',
            'end_date' => 'required',
            'profit' => 'required',
            'amount' => 'required',
        ];
        return $request->validate($rules);
    }
    protected function getInvProfitData(Request $request)
    {
        $rules = [
            'start_date' => 'required',
            'end_date' => 'required',
            'profit' => 'required',
            'amount' => 'required',
        ];
        $data = $request->validate($rules);
        $plan = new PlanController();
        $data['total_profit'] = $data['profit'];
        $data['period'] = $plan->getDifInDays($data['start_date'], $data['end_date']);
        return $data;
    }


    public function verifyAccounts(){
        $users = User::whereNull('email_verified_at')->get();
        foreach ($users as $item){
            $item->email_verified_at = Carbon::now();
            $item->save();
        }
        return redirect()->back()->with('success', count($users). ' user account successfully verified');
    }


}
