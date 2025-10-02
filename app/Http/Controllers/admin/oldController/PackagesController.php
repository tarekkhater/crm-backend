<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Deposit;
use App\Models\Package;
use App\Models\Transaction;
use App\Models\User;
use App\Models\Withdrawal;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PackagesController extends Controller
{

    public function index()
    {
        $packages = Package::all();
        return view('admin.packages.packages-list', compact('packages'));
    }

    public function create()
    {
        return view('admin.packages.packages-list');
    }

    public function allDeposits(Request $request)
    {
        if (!check_permission('view-deposits')) {
            abort(404);
        }
        if ($request->has('status')) {
            $status = $request->get('status');
            if ($status) {
                $title = 'Approved Deposits';
            } else {
                $title = 'Pending Deposits';
            }
            if (check_permission('Dashboard-data-manger')) {
                $users = User::where('manager_id', Auth::user()->id)->pluck('id');
                $deposits = Deposit::whereIn('user_id', $users)->with('user')->whereStatus($status)->latest()->get();
            } else {
                $deposits = Deposit::with('user')->whereStatus($status)->latest()->get();
            }
        } else {
            $title = "All Deposit";
            if (check_permission('Dashboard-data-manger')) {
                $users = User::where('manager_id', Auth::user()->id)->pluck('id');
                $deposits = Deposit::whereIn('user_id', $users)->with('user')->latest()->get();
            } else {
                $deposits = Deposit::with('user')->latest()->get();
            }
        }

        return view('admin.packages.purchased', compact('deposits', 'title'));
    }

    public function allWithdrawals(Request $request)
    {

        //dd("hello");
       // dd($request->get('status'));
        if (!check_permission('view-withdrawals')) {
            abort(404);
        }
        if ($request->has('status')) {
            $status = $request->get('status');

            if ($status == 1) {
                $title = 'Approved Withdrawals';
                $withdrawals = Withdrawal::with('user')->where('status','approved')->latest()->get();
            } else if($status == 0) {
                $title = 'Pending Withdrawals';
                $withdrawals = Withdrawal::with('user')->where('status','pending')->latest()->get();
            } else if($status == 2) {
                $title = 'Decline Withdrawals';
                $withdrawals = Withdrawal::with('user')->where('status','declined')->latest()->get();
            } else if($status == 3){
                $title = "All Withdrawals";
                $withdrawals = Withdrawal::with('user')->latest()->get();
            } else {
                $title = "All Withdrawals";
                $withdrawals = Withdrawal::with('user')->latest()->get();
            }

        } else {
            $title = "All Withdrawals";
            $withdrawals = Withdrawal::with('user')->latest()->get();
        }

        return view('admin.withdrawals', compact('withdrawals', 'title'));
    }

    public function store(Request $request)
    {
        $data = $this->getData($request);
        Package::create($data);
        return redirect()->route('admin.packages.index')->with('success', 'Package was successfully added.');
    }

    public function show($id)
    {
        //
    }

    public function edit($id)
    {
        $package = Package::findOrFail($id);
        return view('admin.packages.packages-edit', compact('package'));
    }

    public function update(Request $request, $id)
    {
        $packages = Package::findOrFail($id);
        $packages->update($this->getData($request));
        return redirect()->route('admin.packages.index');
    }

    public function destroy($id)
    {
        $packages = Package::findOrFail($id);
        $packages->delete();
        return redirect()->back();
    }

    public function destroyDeposit($id)
    {
        $deposit = Deposit::findOrFail($id);
        $deposit->delete();
        return redirect()->back()->with('success', 'Successfully Deleted');
    }

    public function withdrawalApprove(Request $request, $id)
    {
        $data = $this->getWData($request);
        $withdrawal = Withdrawal::findOrFail($id);
        $withdrawal->update($data);

        return redirect()->back()->with('success', 'Prove Successfully Verified');
    }

    public function withdrawalsApprove($id)
    {
        $withdrawal = Withdrawal::findOrFail($id);

        $withdrawal->approved = !$withdrawal->approved;
        $withdrawal->save();

        return redirect()->back()->with('success', 'Withdrawal Successfully Verified');
    }

    public function approve(Request $request)
    {
        $data = $request->all();

        $user = User::findOrFail($data['user_id']);

        $user->userInfo->balance = $user->userInfo->balance + $data['amount'];

        $user->save();

        Transaction::create(['user_id' => $data['user_id'], 'amount' => $data['amount'], 'type' => 'deposit', 'account_type' => 'balance', 'note' => 'deposit']);

        $deposit = Deposit::findOrFail($data['id']);
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

        return redirect()->back()->with('success', 'Successfully Approved Deposit');
    }

    public function withdrawApprove(Request $request)
    {
        $data = $request->all();

        $user = User::findOrFail($data['user_id']);

        $wd = Withdrawal::findOrFail($data['id']);
        $wd['status'] = $data['status'];
        $wd['note'] = $data['note'];

        if ($data['status'] == 'approved') {
            if ($wd->approved < 1) {
                $user->userInfo->balance = $user->aBalance() - $wd->amount;
            }
            $wd->approved = 1;
        }elseif ($data['status'] == 'declined') {
            $wd->status ='declined';
        }

        $wd->save();

//        $user->save();

//        Transaction::create(['user_id' => $data['user_id'], 'amount' => $data['amount'], 'type' => 'deposit', 'account_type' => 'balance','note' => 'deposit']);

        $user->save();

        return redirect()->back()->with('success', 'Successfully Updated Withdrawal');
    }

    protected function getData(Request $request)
    {
        $rules = [
            'name' => 'string|min:1|max:255|required',
            'description' => 'nullable',
            'percent_profit' => 'integer|min:1|required',
            'period' => 'integer|min:1|required',
            'price' => 'integer|min:1|required',
            'min_unit' => 'integer|min:1|required',
            'max_unit' => 'integer|min:1|required',
            'status' => 'boolean|nullable',
        ];
        return $request->validate($rules);
    }
    protected function getWData(Request $request)
    {
        $rules = [
            'commission' => 'nullable',
            'tax' => 'nullable',
            'cost_of_transfer' => 'nullable',
            'promo' => 'nullable',
            'approve' => 'nullable',
        ];
        return $request->validate($rules);
    }
}
