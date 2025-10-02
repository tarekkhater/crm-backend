<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\PlanController;
use App\Models\Deposit;
use App\Models\InvProfit;
use App\Models\Overnight;
use App\Models\Package;
use App\Models\Permission;
use App\Models\Transaction;
use App\Models\User;
use App\Models\UserPlan;
use App\Models\Withdrawal;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Cache;

class AdminDashboardController extends Controller
{

    public function __construct()
    {
        $this->middleware('RoleMiddleware:view-settings_update-settings')->only('overnights');
        $this->middleware('RoleMiddleware:verify-all-emails')->only('verifyAccounts');
        $this->middleware('RoleMiddleware:view-transaction')->only('transactions');
        $this->middleware('RoleMiddleware:delete-transaction')->only('DelTrans');
        $array=[
            [
                "Dashboard-data-all","Dashboard data all",'Dashboard data all'
            ],
            [
                "Dashboard-data-manger","Dashboard data manger",'Dashboard data manger'
            ],
            [
                "count-customer","count customer",'count customer'
            ],  [
                "online-users","online-users",'online-users'
            ],
            [
                "view-withdrawals","view withdrawals",'view withdrawals'
            ],  [
                "view-deposits","view deposits",'view deposits'
            ], [
                "view-KYC-Verifications","view KYC Verifications",'view KYC Verifications'
            ],[
                "view-Plans","view Plans",'view Plans'
            ],
            [
                "login-manger","login manger",'login manger'
            ],
            [
                "show-user","Profile access",'Profile access'
            ],
            [
                "show-balance","show Trades",'show Trade'
            ], [
                "add-balance","add balance",'add balance'
            ],
//            [
//            "view-packages","view packages",'view packages'
//        ],
//            [
//            "create-settings","create settings",'create settings'
//        ],
//            [
//            "customer-assignUsers","customer assignUsers",'customer assignUsers'
//        ],
            [
                "Login-static-ip","Login static Ip",'Login From Ip'
            ],
            [
                "edite-Users","Edit customers Date",'Edit customers Date'
            ],
            [
                "edite-conversion-manager","edite conversion manager",'edite conversion manager'
            ],  [
                "only-active","show only Active customer ",'show only Active customer'
            ],


        ];
        foreach ($array as $rows){

            Permission::updateorcreate(['name'=>$rows[0]],[
                'display_name'=>$rows[1],
                'description'=>$rows[2]
            ]);


        }

    }
    public  $route_pref='admin';
    public  $old_route_pref='new_backend';
    public function index(){


        //  dd(auth()->user()->id);
        if(check_permission('Dashboard-data-all')){
            $users = User::whereRoleIs('user')
                ->when(!check_permission('view-active-customers'),function ($query){
                    $query->where('balance', 0);
                })
                ->count();

            $last_user=User::whereRoleIs('user')->where('created_at','>=',date('Y-m-1'))
                ->when(!check_permission('view-active-customers'),function ($query){
                    $query->where('balance', 0);
                })
                ->count();

            //$online_users = User::whereDate('last_seen','>=',Carbon::now('Europe/London')->toDateTimeString())->whereRoleIs('user')->count();
            $online_users = User::where('last_seen','>=' , Carbon::now('Europe/London'))
                ->when(!check_permission('view-active-customers'),function ($query){
                    $query->where('is_active', 0);
                })
                ->whereRoleIs('user')->count();

            //  dd($online_users);
            //dd(Carbon::parse(Carbon::now('Europe/London'))->toDateTimeString());
            $leads = User::whereRoleIs('lead')->count();
        }
        else if(check_permission('Dashboard-data-manger')){
            $users = User::where('manager_id', Auth::user()->id)->whereRoleIs('user')
                ->when(!check_permission('view-active-customers'),function ($query){

                    $query->where('balance', 0);
                })
                ->when(check_permission('only-active'),function ($query){
                    $query->where('balance', '>', 0)->where('archive_customer?',0);
                })
                ->count();

            $last_user=User::whereRoleIs('user')
                ->when(!check_permission('view-active-customers'),function ($query){
                    $query->where('balance', 0);
                })
                ->where('manager_id', Auth::user()->id)->where('created_at','>=',date('Y-m-1'))->count();
            $online_users = User::where('last_seen','>=' , Carbon::now('Europe/London'))
                ->when(!check_permission('view-active-customers'),function ($query){
                    $query->where('balance', 0);
                })
                ->when(check_permission('only-active'),function ($query){
                    $query->where('balance', '>',0);
                })
                ->where('manager_id', Auth::user()->id)->whereRoleIs('user')->count();
            $leads = User::where('manager_id', Auth::user()->id)->whereRoleIs('lead')->count();
        }
        else{
            $users = User::whereRoleIs('user')->count();
            $last_user=User::whereRoleIs('user')->where('created_at','>=',date('Y-m-1'))->count();
            $online_users = User::where('last_seen','>=' , Carbon::now('Europe/London'))->whereRoleIs('user')->count();
            $leads = User::whereRoleIs('lead')->count();
        }

        $a_deposits =  Deposit::whereStatus(1)->count();
        $p_deposits =  Deposit::whereStatus(0)->count();
        $p_withdrawals =  Withdrawal::where('status','pending')->count();
        $packages =  Package::count();
        $deposit_daya =  Deposit::whereStatus(1)->where('created_at','like',date('Y-m-d'))->sum('amount');
        $withdrawals_day=  Withdrawal::where('status','approved')->where('created_at','like',date('Y-m-d'))->sum('amount');
        $dates=[];
        $begin = new \DateTime( date('Y-m-d',strtotime('-10 days')));
        $end   = new \DateTime(date("Y-m-d"));
        $key=0;
        for($i = $begin; $i <= $end; $i->modify('+1 day')){

            $format=date_format($i,'Y-m-d');
            $user_register=User::where('created_at','like',$format)->count();
            $deposit_daye =  Deposit::where('created_at','like',$format)->sum('amount');
            $withdrawals_dae=  Withdrawal::where('created_at','like',$format)->sum('amount');

            $dates[$key]['user']=$user_register;
            $dates[$key]['deposit_daye']=$deposit_daye;
            $dates[$key]['withdrawals_dae']=$withdrawals_dae;
            $dates[$key]['date']=$format;
            $key++;

        }

        $data =
            [
                'dates'=>$dates,
                'withdrawals_day'=>$withdrawals_day,
                'deposit_daya'=>$deposit_daya,
                'last_user'=>$last_user,
                'users' => $users,
                'online_users' => $online_users,
                'leads' => $leads,
                'a_deposits' => $a_deposits,
                'p_deposits' => $p_deposits,
                'p_withdrawals' => $p_withdrawals,
                'packages' => $packages,
            ];


        return view($this->route_pref.'.index',$data);
    }

    public function overnights(){
        $items = Overnight::all();
        return view($this->route_pref.'.overnights', compact('items'));
    }

    public function transactions(){

        if(check_permission('Dashboard-data-manger')){
            $users = User::where('manager_id', Auth::user()->id)->pluck('id');
            $items = Transaction::with('user')->whereIn('user_id', $users)->get();
        }else {
            $items = Transaction::with('user')->get();
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
