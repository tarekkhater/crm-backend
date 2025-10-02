<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Imports\CemeteryImport;
use App\Imports\LeadImport;
use App\Models\AutoProfitLoss;
use App\Models\Deposit;
use App\Models\Package;
use App\Models\Identity;
use App\Models\Role;
use App\Models\Trade;
use App\Models\Transaction;
use App\Models\User;
use App\Models\Status;
use App\Models\Source;
use App\Models\Note;
use App\Models\Country;
use App\Models\Message;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\traits\UploadTrait;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Hash;
use Storage;
use Carbon\Carbon;

class UsersController extends Controller
{
    use UploadTrait;
    public function __construct()
    {

        $this->middleware('RoleMiddleware:view-leads_create-leads_update-leads_create-source-leads_create-status-leads_import-leads_assign-manager-leads')
            ->only(['leads']);
        $this->middleware('RoleMiddleware:create-leads')
            ->only(['storeLead']);
        $this->middleware('RoleMiddleware:create-status-leads')
            ->only(['storeStatus']);
        $this->middleware('RoleMiddleware:view-customers_create-customers_update-customers_assign-manager-customers_assign-retention-customers')
            ->only(['index']);
        $this->middleware('RoleMiddleware:create-customers')
            ->only(['create']);
        $this->middleware('RoleMiddleware:assign-manager-customers_assign-retention-customers')
            ->only(['updateManager']);
        $this->middleware('RoleMiddleware:set-users-as-free')
            ->only(['set_users_as_free']);
        $this->middleware('RoleMiddleware:set-users-as-archieve')
            ->only(['set_users_as_archeive']);
        $this->middleware('RoleMiddleware:view-active-customers_create-active-customers_update-active-customersm_assign-manager-active-customers_assign-retention-active-customers_set-active-users-as-free_set-active-users-as-archieve')
            ->only(['activ_index']);
        $this->middleware('RoleMiddleware:view-archieve-customers_create-archieve-customers_update-archieve-customers_assign-manager-archieve-customers_assign-retention-archieve-customers_set-archieve-users-as-free_set-archieve-users-as-active')
            ->only(['archieve_index']);
        $this->middleware('RoleMiddleware:view-conversion-agents_create-manager_assign-user-to-manager_update-manager_delete-manager_login-as-manager')
            ->only(['managers']);
        $this->middleware('RoleMiddleware:view-retention-agents_create-retention_update-retention_delete-retention_login-as-retention')
            ->only(['retentions']);
        $this->middleware('RoleMiddleware:view-admins_create-admin_update-admin_delete-admin_login-as-admin')
            ->only(['admins']);
        $this->middleware('RoleMiddleware:view-KYC-Verifications')
            ->only(['Ids']);
        $this->middleware('RoleMiddleware:show-user')
            ->only(['show']);
    }

    public function index(Request $request){
        $title = "";
        $users = "";
        if(check_permission('Dashboard-data-all')){

            $title = 'All Customers';
            $users = User::where('balance', 0)
                ->when(!check_permission('view-active-customers'),function ($query){
                    $query->where('is_active', 0);
                })
                ->search($request)->with('notes')->with('statusrecord')->with('sourcerecord')

                ->latest()->whereRoleIs('user')->paginate(100);

        }
        else{
            $title = 'Assigned Customers';
            $users = User::where('balance', 0)->search($request)->with('notes')->with('statusrecord')->with('sourcerecord')
                ->when(!check_permission('view-active-customers'),function ($query){
                    $query->where('is_active', 0);
                })
                ->whereRoleIs('user')->where('manager_id', Auth::user()->id)->latest()->paginate(100);
       }

        $statuses = Status::all();

        $sources = Source::all();

        $managers = User::whereRoleIs('manager')->latest()->get();
        $retentions = User::whereRoleIs('retention')->latest()->get();

        $users->appends($request->all());
        return view('admin.users.customers', compact('users','title', 'managers', 'retentions', 'statuses','sources'));
    }


    public function activ_index(Request $request){
        $title = "";
        $users = "";
        if(check_permission('Dashboard-data-all')){

            $title = 'All Customers';
            $users = User::where('balance', '>', 0)->where('archive_customer?',0)->search($request)->with('notes')
                ->with('statusrecord')->with('sourcerecord')->whereRoleIs('user')->latest()->paginate(100);
        }
        else{
            $title = 'Assigned Customers';
            $users = User::where('balance', '>', 0)->where('archive_customer?',0)->search($request)->with('notes')
                ->with('statusrecord')->with('sourcerecord')->latest()->whereRoleIs('user')->where('manager_id', Auth::user()->id)
                ->paginate(100);
        }


        $statuses = Status::all();

        $sources = Source::all();

        $managers = User::whereRoleIs('manager')->latest()->get();
        $retentions = User::whereRoleIs('retention')->latest()->get();

        $users->appends($request->all());
        //dd($users);
        return view('admin.users.customers', compact('users','title', 'managers', 'retentions', 'statuses','sources'));
    }

    public function archieve_index(Request $request){
        $title = "";
        $users = "";
        if(check_permission('Dashboard-data-all')){
            $title = 'Assigned Customers';
            $users = User::where('archive_customer?', 1)->search($request)->with('notes')->with('statusrecord')->with('sourcerecord')->whereRoleIs('user')->latest()->paginate(100);
        }
        else{
            $title = 'All Customers';
            $users = User::where('archive_customer?', 1)->search($request)->with('notes')->with('statusrecord')->with('sourcerecord')->latest()->whereRoleIs('user')->where('manager_id', Auth::user()->id)->paginate(100);
        }


        $statuses = Status::all();

        $sources = Source::all();

        $managers = User::whereRoleIs('manager')->latest()->get();
        $retentions = User::whereRoleIs('retention')->latest()->get();

        $users->appends($request->all());
        //dd($users);
        return view('admin.users.archive-customers', compact('users','title', 'managers', 'retentions', 'statuses','sources'));
    }

    public function online_users(Request $request){
        $title = "";
        $users = "";
        if(check_permission('Dashboard-data-all')){
            $title = 'All Customers';
            $users = User::where('last_seen','>=' , Carbon::now('Europe/London'))
                ->search($request)->with('notes')->with('statusrecord')->with('sourcerecord')
                ->when(!check_permission('view-active-customers'),function ($query){
                    $query->where('is_active', 0);
                })
                ->latest()
                ->whereRoleIs('user')->paginate(100);

        }else{
          $title = 'Assigned Customers';
            $users = User::whereNotNull('session_id')->where('no_of_logins' , '>' , 0)->search($request)->with('notes')
                ->with('statusrecord')->with('sourcerecord')->whereRoleIs('user')->where('manager_id', Auth::user()->id)->latest()->paginate(100);

        }


        $statuses = Status::all();

        $sources = Source::all();

        $managers = User::whereRoleIs('manager')->latest()->get();
        $retentions = User::whereRoleIs('retention')->latest()->get();

        $users->appends($request->all());

        return view('admin.users.customers', compact('users','title', 'managers', 'retentions', 'statuses','sources'));
    }

    public function modifyAutoPNL(Request $request){
        $autopnl = AutoProfitLoss::whereUserId($request->user_id)->first();
        $autopnl->update($request->all());
        return redirect()->back()->with('success','Auto PNL successfully Modified');
    }

    public function admins(){

        $title = 'admin';
        $statuses = Status::all();

        $sources = Source::all();

        if(check_permission('Dashboard-data-all')){
            $users = User::whereRoleIs('admin')->orWhereRoleIs('superadmin')->latest()->paginate(100);
        }else{
            $users = User::whereRoleIs('admin')->paginate(100);
        }
        return view('admin.users.index', compact('users','title','statuses','sources'));
    }

    public function leads(Request $request){

        $title = 'admin';
        if(check_permission('Dashboard-data-all')) {
            $users = User::search($request)->with('notes')->with('statusrecord')->with('sourcerecord')
                ->whereRoleIs('lead')->latest()->paginate(100);
        }else{

            $users = User::search($request)->with('notes')->with('statusrecord')->with('sourcerecord')->whereRoleIs('lead')
                ->where('manager_id', Auth::user()->id)->paginate(100);

        }

        if(count($users) < 1){
            $role = Role::whereName('lead')->first();
            if(!$role){
                $rol = new Role();
                $rol->name = 'lead';
                $rol->display_name = 'Lead'; $rol->description = 'Lead';
                $rol->save();
            }
        }
        $statuses = Status::all();
        $managers = User::whereRoleIs('manager')->latest()->get();
        $countries = Country::get();
        $sources = Source::all();
        $users->appends($request->all());

        return view('admin.users.leads', compact('users','title', 'statuses', 'sources','managers','countries'));
    }

    public function webhooks(Request $request){
        $title = 'admin';
        if(check_permission('Dashboard-data-manger')) {
            $users = User::search($request)->with('notes')->with('statusrecord')->with('sourcerecord')->whereRoleIs('lead')
                ->whereWebhook('getresponse')->where('manager_id', Auth::user()->id)->paginate(100);
        }else{
            $users = User::search($request)->with('notes')->with('statusrecord')->with('sourcerecord')->whereRoleIs('lead')->whereWebhook('getresponse')->latest()->paginate(100);
        }

        if(count($users) < 1){
            $role = Role::whereName('lead')->first();
            if(!$role){
                $rol = new Role();
                $rol->name = 'lead';
                $rol->display_name = 'Lead'; $rol->description = 'Lead';
                $rol->save();
            }
        }
        $statuses = Status::all();
        $managers = User::whereRoleIs('manager')->latest()->get();
        $countries = Country::get();
        $sources = Source::all();
         $users->appends($request->all());
        return view('admin.users.leads', compact('users','title', 'statuses', 'sources','managers','countries'));
    }


    public function testCrm(){
        $data['name'] = 'Hello World';
        $data['phone'] = '0000000000';
        $data['email'] = 'test@ .com';
        $data['country'] = 'canda';

        return $this->addLead($data);
//        return $this->search('Irvan maulana');
    }

     public function gainAccess($id){
        $user = User::find($id);
        $staick=auth()->user()->hasRole(['superadmin']);
        $new_permissin=check_permission_user('Login-static-ip',$user);
        if($new_permissin==true&&$staick==false){
            $ip=setting('static_Ip','192.0.0.1');
            if($ip!=$_SERVER['REMOTE_ADDR']){


                return redirect()->back()->with('failure', "Filled Login From This Ip ");
            }

        }
        Auth::login($user);
        if(Auth::check())
        {

          //  $user = Auth::user();

               $previous_session = $user->session_id;
               $login = $user->no_of_logins;
               if ($login > 0){
                    if ($previous_session)
                        {
                        \Session::getHandler()->destroy($previous_session);
                        Auth::user()->no_of_logins--;
                        }
                }
                //dd("hello");
               Auth::user()->session_id = \Session::getId();
               Auth::user()->no_of_logins++;
               Auth::user()->save();


        }
        return redirect()->route('backend.dashboard');
    }

    public function canFund(Request $request){
        $user = User::find($request['id']);
        $user->can_add_fund = !$user->can_add_fund;
        $user->save();
        return response()->json($user);
    }

//    public function gainAccess(Request $request){
//        //dd($request->input('user_id'));
//       $user = User::find($request->input('user_id'));
//       $id = Auth::user()->id;
//       Auth::login($user);
//       return redirect()->route('backend.gainaccess.tradestation', $id);
//   }


    public function logBack($id){
        Auth::logout();
        $user = User::find($id);
        Auth::login($user);
        return redirect()->route('admin.users.index');
    }
    public function managers(){


        $statuses = Status::all();

        $sources = Source::all();
        $title = 'manager';

            $users = User::whereRoleIs('manager')->paginate(100);


        return view('admin.users.index', compact('users','title','sources','statuses'));
    }

    public function retentions(){


        $statuses = Status::all();

        $sources = Source::all();
        $title = 'retention';
        $users = User::whereRoleIs('retention')->paginate(100);
        return view('admin.users.index', compact('users','title','sources','statuses'));
    }

    public function marketers(){

        $title = 'Marketers';
        $users = User::whereRoleIs('marketers')->paginate(100);
        $statuses = Status::all();

        $sources = Source::all();
        return view('admin.users.index', compact('users','title','sources','statuses'));
    }
    public function autotraders(){
        $title = 'Autotraders';
        $users = User::whereRoleIs('autotrader')->paginate(100);
        return view('admin.users.index', compact('users','title'));
    }

    public function connectAccount(Request $request){
        $data = $request->all();
        $user = User::findOrFail($data['user_id']);
        $trader = User::findOrFail($data['trader_id']);
        $user->code = null;
        $user->msg = $data['message'];
        $user->trader_request = 'pending';
        $user->manager_id = $trader->id;
        $mani = "Manager : ".ucfirst($trader->first_name);
        $user->account_officer = $mani;
        $user->save();
        if($data['sendmail'] == 1){
            $this->message($user, $data['message'],'Account Connection request');
        }
        return redirect()->back()->with('success','Account connection request successfully sent');
    }

    public function sendMessage($id){
        $user = User::findOrFail($id);
        return view('admin.users.send_message', compact('user'));
    }
    public function sendMsg(Request $request){

        $message = new Message();
        $message->user_id = $request['user_id'];
        $message->subject = $request['subject'];
        $message->message = strip_tags($request['message']);
        $message->save();
        $user = User::findOrFail($request['user_id']);
        $this->message($user, strip_tags($request['message']),$request['subject']);
        return redirect()->back()->with('success', "Message successfully sent to ". $user->name);
    }

    public function activePlans(){
        $users = User::whereRoleIs('user')->get();
        return view('admin.users.active_plans-list', compact('users'));
    }

   public function employers(){
        $title = 'employer';
        $users = User::whereRoleIs('employer')->get();
        return view('admin.users.employer-list', compact('users','title'));
    }

   public function upgradeplan(Request $request){
        $plan = $request->plan;
        $user = User::findOrFail($request['user_id']);
        $user->plan = $plan;
        $user->save();
        return back()->with('success', 'user plan updated successfully');
    }

    public function create(Request $request)
    {
       // dd("hello");

        $forms = ['first_name', 'last_name','email','phone','country'];
        $role = $request->get('r');

        $countries = Country::get();
        $statuses = Status::all();
        $sources = Source::all();
       $roles = Role::whereName($role)->get();
       //dd($roles);
        return view('admin.users.create', compact('roles','role','forms','countries','sources','statuses'));
    }

    public function createArchive(Request $request)
    {
       // dd("hello");

        $forms = ['first_name', 'last_name','email','phone','country'];
        $role = $request->get('r');

        $countries = Country::get();
        $statuses = Status::all();
        $sources = Source::all();
       $roles = Role::whereName($role)->get();
       //dd($roles);
        return view('admin.users.createArchive', compact('roles','role','forms','countries','sources','statuses'));
    }


    public function store(Request $request)
    {


        $this->validate($request, [
            'first_name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'country' => ['required','string'],
//            'phone_code' => ['required','string'],
            'phone' => ['nullable'],
            'address' => ['nullable'],
            'permanent_address' => ['nullable'],
//            'username' => ['required', 'string', 'max:50', 'unique:users'],
            'password' => ['required', 'string', 'min:6', 'confirmed',  'min:6'],             // must be at least 10 characters in length
        ]);

        $data = $request->all();


            DB::beginTransaction();

            if(check_permission('Dashboard-data-manger')){
                $manager_id = auth()->id();
            }else {
                $manager_id = null;
            }

            $user = User::create([
                'first_name' => $data['first_name'],
                'email' => $data['email'],
                'is_active' => true,
                'can_trade' => 1,
                'source' => $data['source'],
//                'username' => $data['username'],
                'phone_code' => $data['phone_code'],
                'country' => $data['country'],
                'phone' => $data['phone'],
                'manager_id' => $manager_id,
                'address' => $data['address'],
                'permanent_address' => $data['permanent_address'],
                'profit' => $data['profit'] ?: 0,
                'fee' => $data['fee'] ?: 0,
                'password' => bcrypt($data['password']),
            ]);

            // assign role to registered user
            $user->attachRole($data['role']);

            // send email verification link to user
//            event(new \Illuminate\Auth\Events\Registered($user));
            DB::commit();


        if(setting('joint_account') == 1){
            $this->createJoint($user, $request->all());
        }

            $email = $data['email'];
        return redirect()->back()->with('success', ucfirst($data['role'])." successfully added");
    }

    public function storeArchieve(Request $request)
    {



        $this->validate($request, [
            'first_name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'country' => ['required','string'],
//            'phone_code' => ['required','string'],
            'phone' => ['nullable'],
            'address' => ['nullable'],
            'permanent_address' => ['nullable'],
//            'username' => ['required', 'string', 'max:50', 'unique:users'],
            'password' => ['required', 'string', 'min:6', 'confirmed',  'min:6'],             // must be at least 10 characters in length
        ]);

        $data = $request->all();


            DB::beginTransaction();

            if(check_permission('Dashboard-data-manger')){
                $manager_id = auth()->id();
            }else {
                $manager_id = null;
            }

            $user = User::create([
                'first_name' => $data['first_name'],
                'email' => $data['email'],
                'is_active' => true,
                'can_trade' => 1,
                'source' => $data['source'],
//                'username' => $data['username'],
                'phone_code' => $data['phone_code'],
                'country' => $data['country'],
                'phone' => $data['phone'],
                'manager_id' => $manager_id,
                'address' => $data['address'],
                'archive_customer?' => 1,
                'permanent_address' => $data['permanent_address'],
                'profit' => $data['profit'] ?: 0,
                'fee' => $data['fee'] ?: 0,
                'password' => bcrypt($data['password']),
            ]);

            // assign role to registered user
            $user->attachRole($data['role']);

            // send email verification link to user
//            event(new \Illuminate\Auth\Events\Registered($user));
            DB::commit();


        if(setting('joint_account') == 1){
            $this->createJoint($user, $request->all());
        }

            $email = $data['email'];
        return redirect()->back()->with('success', ucfirst($data['role'])." successfully added");
    }

//    public function fundAccount(Request $request){
//        $this->validate($request, [
//            'user_id' => ['required', 'integer'],
//            'amount' => ['required'],
//            ]);
//        $data = $request->all();
//        $user = User::findOrFail($data['user_id']);
//        $user->userInfo->balance = $user->userInfo->balance + $data['amount'];
//        $user->save();
//        Transaction::create(['user_id' => $data['user_id'], 'amount' => $data['amount'], 'type' => $data['type'], 'account_type' => $data['account_type'],'note' => $data['note']]);
//        return redirect()->back()->with('success', 'Successful, balance modified');
//    }

    public function fundAccount(Request $request){
        $this->validate($request, [
            'user_id' => ['required', 'integer'],
            'amount' => ['required'],
            'source' => ['required'],
            ]);
        $data = $request->all();
        $data['account_type'] = $data['type'];
        if($request->get('note')){
            $note = $data['note'];
        }else{
            $note = 'Admin '. $data['account_type'];
        }
        $user = User::findOrFail($data['user_id']);
        if($data['type'] == 'deposit'){
            $user->userInfo->balance = (float)$user->aBalance() + (float)$data['amount'];
        }elseif($data['type'] == 'bonus'){
            $user->userInfo->bonus = (float)$user->userInfo->bonus + (float)$data['amount'];
        }else{
            $user->userInfo->balance = (int)$user->aBalance() - (int)$data['amount'];
        }

        $user->save();
        Transaction::create(['user_id' => $data['user_id'], 'source' => $data['source'], 'amount' => $data['amount'], 'type' => $data['type'], 'account_type' => $data['account_type'],'note' => $note]);
        if($data['notify'] > 0){
            $this->message($user, $note,'Account fund updated');
        }

        return redirect()->back()->with('success', 'Successful, balance modified');
    }
    public function updateWithdrawable(Request $request){
        $this->validate($request, [
            'user_id' => ['required', 'integer'],
            'withdrawable' => ['required'],
            ]);
        $data = $request->all();
        $user = User::findOrFail($data['user_id']);
        $user->userInfo->withdrawable = $data['withdrawable'];
        $user->save();
        return redirect()->back()->with('success', 'Successful, Withdrawable balance modified');
    }

    public function fundBonus(Request $request){
        $this->validate($request, [
            'user_id' => ['required', 'integer'],
            'amount' => ['required'],
            ]);
        $data = $request->all();
        $user = User::findOrFail($data['user_id']);
        if($data['type'] == 'credit'){
            $user->userInfo->bonus = (int)$user->userInfo->bonus + (int)$data['amount'];
        }else{
            $user->userInfo->bonus = (int)$user->userInfo->bonus - (int)$data['amount'];
        }
        $user->save();

        if($request->get('note')){
            $note = $data['note'];
        }else{
            $note = 'Admin '. $data['account_type']. ' '.$data['type'];
        }

        Transaction::create(['user_id' => $data['user_id'], 'amount' => $data['amount'], 'type' => $data['type'], 'account_type' => $data['account_type'],'note' => $note]);
        return redirect()->back()->with('success', 'Successful, Bonus modified');
    }

    public function notifications(Request $request){
        $this->validate($request, [
            'user_id' => ['required', 'integer'],
            'admin_notifications' => ['required'],
        ]);

        $data = $request->all();
        $user = User::findOrFail($data['user_id']);
        $user->admin_notifications = $data['admin_notifications'];
        $user->save();

        return redirect()->back()->with('success', 'Successful, Notifications Updated!');
    }

    public function toggleTrade($id){
        $user = User::findOrFail($id);
        if(!$user->can_trade){
            if(setting('suspend_trade_mail')){
                $this->message($user, 'Your account has been activated for withdraw, you can login to your dashboard and make your withdrawal request','Account Activated For Withdrawal');
            }
        }
        $user->can_trade = !$user->can_trade;
        $user->save();
        return redirect()->back()->with('success', 'Successful, User Data Updated');
    }

    public function toggleAllowTrade($id){
        $user = User::findOrFail($id);
        if(!$user->allow_trade){
            if(setting('suspend_trade_mail')){
                $this->message($user, 'Your account has been activated for withdraw, you can login to your dashboard and make your withdrawal request','Account Activated For Withdrawal');
            }
        }
        $user->allow_trade = !$user->allow_trade;
        $user->save();
        return redirect()->back()->with('success', 'Successful, User Data Updated');
    }

    public function toggleActive($id){
        $user = User::findOrFail($id);
        if(!$user->is_active){
            if(setting('user_activated_mail')){
                $this->message($user, 'Congratulations, Your account has been activated, you can login to your dashboard and make your first deposit, if you have not done that yet request','Account Activated');
            }
        }
        $user->is_active = !$user->is_active;
        $user->save();
        return redirect()->back()->with('success', 'Successful, User Data Updated');
    }
    public function toggleWithdraw($id){
        $user = User::findOrFail($id);
        if(!$user->can_withdraw){
            if(setting('enable_withdraw_mail')){
                $this->message($user, 'Your account has been activated for withdraw, you can login to your dashboard and make your withdrawal request','Account Activated For Withdrawal');
            }
        }
        $user->can_withdraw = !$user->can_withdraw;
        $user->save();
        return redirect()->back()->with('success', 'Successful, User Data Updated');
    }
    public function toggleUpgrade($id){
        $user = User::findOrFail($id);
        if(!$user->can_upgrade){
            if(setting('plan_upgrade_mail')){
                $this->message($user, 'Your account has been activated for upgrade, you can login to your dashboard to upgrade your account','Account Activated For Upgrade');
            }
        }
        $user->can_upgrade = !$user->can_upgrade;
        $user->save();
        return redirect()->back()->with('success', 'Successful, User Data Updated');
    }

    public function IdActivate(Request $request, $id){
        $request->validate([
            'message' => 'required|string'
        ]);

        try {
            DB::beginTransaction();
            $id = Identity::findOrFail($id);
            $id->status =  1;
            $id->save();

            $user = User::find($id->user->id);
            // send mail to user
            $this->message($user, $request->message, 'Account Activation Denied!');
            DB::commit();
            return redirect()->back()->with('success', 'Successful, Id activated');
        } catch (\Throwable $th) {
            DB::rollback();
            return redirect()->back()->with('failure', $th->getMessage());
        }
    }

    public function IdDisapprove(Request $request, $id) {
        $request->validate([
            'message' => 'required|string'
        ]);
        try {
            DB::beginTransaction();
            $id = Identity::findOrFail($id);
            $id->status =  -1;
            $id->save();

            $user = User::find($id->user->id);
            // send mail to user
            $this->message($user, $request->message, 'Account Activation Denied!');
            DB::commit();
            return redirect()->back()->with('success', 'Successful, Id disapproved');
        } catch (\Throwable $th) {
            DB::rollback();
            return redirect()->back()->with('failure', $th->getMessage());
        }
    }

    public function Ids(){

        $ids = Identity::with('user')->latest()->get();
        return view('admin.ids', compact('ids'));
    }

    public function loginLogs($id){
        $user = User::findOrFail($id);
        $details = User::findOrFail($id)->authentications;

        return view('admin.logins', compact('details','user'));
    }
    public function myLoginLogs(){

        $details = Auth::user()->authentications;

        return view('admin.my_logins', compact('details'));
    }

    public function allLoginLogs(){

      /*  $details = [];
        $users = User::latest()->get();
        foreach($users as $user)
        {

            dd($user->authentications);
        }*/

        if (check_permission('Dashboard-data-manger')) {
        $users = User::whereRoleIs('user')->where('manager_id', Auth::user()->id)->get();
            \DB::table('authentication_log')->where('authenticatable_id','>',0)->delete();
        foreach($users as $user) {
        $details[] = \DB::table('authentication_log')
        ->leftjoin('users','authentication_log.authenticatable_id','users.id')
        ->select('authentication_log.*','users.first_name','users.last_name')
        ->whereNotNull('authentication_log.authenticatable_id')
        ->whereNotNull('users.first_name')
        ->whereNotNull('users.last_name')
        ->where('authenticatable_id',$user->id)
        ->paginate(10);
        }
        } else {
        $details = \DB::table('authentication_log')
        ->leftjoin('users','authentication_log.authenticatable_id','users.id')
        ->select('authentication_log.*','users.first_name','users.last_name')
        ->whereNotNull('authentication_log.authenticatable_id')
        ->whereNotNull('users.first_name')
        ->whereNotNull('users.last_name')
        ->paginate(10);
        }


        if (check_permission('Dashboard-data-manger')) {
        $details = $details[0];
        }



        return view('admin.my_logins', compact('details'));
    }

    public function edit(User $user)
    {



        $admin_roles = Role::whereNotIn('name', ['superadmin', 'customer'])->pluck('name');
        $countries = Country::get();
            $country = Country::where('nicename',$user->country)->first();
            if($country){
                if(file_exists(public_path().'/flags/'.strtolower($country->iso).'.png')){
                    $user->iso = asset('/flags').'/'.strtolower($country->iso).'.png';
                }else{
                    $user->iso = asset('/flags').'/'.$country->iso.'.png';
                }
            }
            $roles=Role::get();
            $roles_id=$user->roles()->get()->pluck('id')->toArray();

        return view('admin.users.edit', compact('roles_id','roles','user', 'admin_roles','countries'));
    }


    public function show(Request $request, $username)
    {


        if (check_permission('Dashboard-data-all')) {
            $user = User::whereFirstName($username)->orWhere('id',$username)->firstOrFail();

        }else {
           $user = User::whereFirstName($username)->orWhere('id',$username)->where('manager_id', Auth::user()->id)->firstOrFail();

        }

        $id = $user->id;
        $autoPNL = $this->getAutoPNL($user->id);
        $country = Country::where('nicename',$user->country)->first();
        if($country) {
            if (file_exists(public_path() . '/flags/' . strtolower($country->iso) . '.png')) {
                $user->iso = asset('/flags') . '/' . strtolower($country->iso) . '.png';
            } else {
                $user->iso = asset('/flags') . '/' . $country->iso . '.png';
            }
        }
        $deposits = Deposit::whereUserId($user->id)->get();
        $trades = Trade::whereUserId($user->id)->get();

        $p_trade = Trade::whereUserId($id)->whereStatus(0)->get();

        $trans = Transaction::whereUserId($user->id)->get();
        $details = User::findOrFail($user->id)->authentications;
        //dd($details);
        $roles=Role::
        wherenotin('name',['lead','user'])->get();
        $roles_id=$user->roles()->get()->pluck('id')->toArray();

        if($request->has('asset')){
            $c_type = $request->get('asset');
        }else {
            $c_type = 'crypto';
        }

        return view('admin.users.show', compact('roles','roles_id','user','c_type','p_trade','deposits','trades','trans','details','autoPNL'));
    }

    public function getAutoPNL($user_id){
        $autopnl = AutoProfitLoss::whereUserId($user_id)->first();
        if($autopnl){
            return $autopnl;
        }else{
            $autopnl = new AutoProfitLoss();
            $autopnl->user_id = $user_id;
            $autopnl->save();
            $new_autopnl = AutoProfitLoss::whereUserId($user_id)->first();
            return $new_autopnl;
        }
    }

    public function transactions($id){
        $transactions = Transaction::whereUserId($id)->latest()->get();
        return view('admin.user.transactions', compact('transactions'));
    }

    // User Transactions
    public function edit_transactions(Transaction $transaction){
        return view('admin.users.edit-transaction', compact('transaction'));
    }

    public function update_transactions(Request $request, Transaction $transaction){
        $this->validate($request, [
            'type' => 'required|string',
            'account_type' => 'required|string',
            'amount' => 'required|numeric',
            'note' => 'nullable',
            'created_at' => 'required|date|before:now',
        ]);


        $user = User::findOrFail($transaction->user_id);


        if($transaction->type == 'deposit'){
            $user->userInfo->balance = (float)$user->aBalance() - (float)$transaction->amount;
        }elseif($transaction->type == 'bonus'){
            $user->userInfo->bonus = (float)$user->userInfo->bonus - (float)$transaction->amount;
        }else{
            $user->userInfo->balance = (int)$user->aBalance() + (int)$transaction->amount;
        }

        $user->save();

        try {
            $transaction->update($request->all());
            if($request['type'] == 'deposit'){
                $user->userInfo->balance = (float)$user->aBalance() + (float)$request['amount'];
            }elseif($request['type'] == 'bonus'){
                $user->userInfo->bonus = (float)$user->userInfo->bonus + (float)$request['amount'];
            }else{
                $user->userInfo->balance = (int)$user->aBalance() - (int)$request['amount'];
            }
            $user->save();
            return redirect()->route('admin.users.show', $transaction->user)->with('success', 'Transaction Updated Successfully!');
        } catch (\Throwable $th) {
            return redirect()->back()->with('failure', $th->getMessage());
        }
    }

    public function destroy_one_transaction(Transaction $transaction) {
        try {
            $user = User::findOrFail($transaction->user_id);


            if($transaction->type == 'deposit'){
                $user->userInfo->balance = (float)$user->aBalance() - (float)$transaction->amount;
            }elseif($transaction->type == 'bonus'){
                $user->userInfo->bonus = (float)$user->userInfo->bonus - (float)$transaction->amount;
            }else{
                $user->userInfo->balance = (int)$user->aBalance() + (int)$transaction->amount;
            }

            $user->save();
            $transaction->delete();
            return redirect()->back()->with('success', 'Transaction Deleted Successfully.');
        }
        catch (\Throwable $th) {
            return redirect()->back()->with('failure', $th->getMessage());
        }
    }
     public function change_role_user(Request  $request){
         try {
             $user=User::find($request['user_id']);
             DB::table('role_user')->where('user_id',$user['id'])->delete();
             if(isset($request['permission']))
                 foreach ($request['permission'] as $role){
                     $name=Role::find($role);
                     $user->attachRole($name);

                 }
             return redirect()->back()->with('success', 'Success Update Role User.');

         } catch (\Throwable $th) {
             return redirect()->back()->with('failure', $th->getMessage());
         }

     }

    public function destroy_transactions(Request $request) {
        try {
            Transaction::whereIn('id', $request->delete_transaction_ids)->delete();
            return redirect()->back()->with('success', count($request->delete_transaction_ids) . ' Transactions Deleted!');
        } catch (\Throwable $th) {
            return redirect()->back()->with('failure', $th->getMessage());
        }
    }

    public function destroyTrades(Request $request) {

        // $request->validate([
        //     'delete_trade_ids' => 'required',
        // ]);

        try {
            Trade::whereIn('id', $request->delete_trade_ids)->delete();
            return redirect()->back()->with('success', count($request->delete_trade_ids) . ' Trades Deleted!');
        } catch (\Throwable $th) {
            return redirect()->back()->with('failure', $th->getMessage());
        }
    }

    public function destroyTrade($id) {
        try {
            Trade::findOrFail($id)->delete();
            return redirect()->back()->with('success', 'Trade Deleted!');
        } catch (\Throwable $th) {
            return redirect()->back()->with('failure', $th->getMessage());
        }
    }

    // handle file upload
    public function storeFile(Request $request, $fileName)
    {
        try {
            $proof = $request->file('proof');
            // Define folder path
            $folder = '/uploads/proofs/';
            // Make a file path where image will be stored [ folder path + file name + file extension]
            $f_filePath = $folder . $fileName. '.' . $proof->getClientOriginalExtension();
            // Upload image
            $this->uploadOne($proof, $folder, 'public', $fileName);
            return $f_filePath;

        } catch (\Throwable $th) {

            return redirect()->back()->with('failure', $th->getMessage())->withInput();
        }
    }

    // User Deposit
    public function create_deposits (User $user) {
        $packages = Package::where('status', 1)->get();
        return view('admin.users.deposits.create', compact('user', 'packages'));
    }

    public function store_deposit(Request $request, User $user) {
        $request->validate([
            'user_id' => 'nullable',
            'plan_id' => 'required|exists:packages,id',
            'amount' => 'required',
            'proof' => 'required|image',
            'promo_code' => 'string|nullable',
            'payment_method' => 'string|min:1|nullable',
        ]);

        try {
            $data = $request->all();
            $data['user_id'] = $user->id;
            $data['payment_method'] = $data['payment_method'] ?? 'Bitcoin';

            $fileName = Str::slug($user->first_name.'_proof_'.time());
            $returnedFilePath = $this->storeFile($request, $fileName);

            if ($returnedFilePath) {
                $data['proof'] = $returnedFilePath;
                Deposit::create($data);
                return redirect()->back()->with('success', 'Deposit Created');
            }
        } catch (\Throwable $th) {
            Storage::delete('public/uploads/proofs/' . $fileName); // delete the file
            return redirect()->back()->with('failure', $th->getMessage())->withInput();
        }
    }

    public function edit_deposits(Deposit $deposit){
        $packages = Package::where('status', 1)->get();
        return view('admin.users.deposits.edit', compact('deposit', 'packages'));
    }

    public function update_deposits(Request $request, Deposit $deposit){
        $this->validate($request, [
            'plan_id' => 'required|exists:packages,id',
            'amount' => 'required|numeric',
            'proof' => 'nullable|image',
            'message' => 'nullable',
            'created_at' => 'required|date|before:now',
        ]);
        try {

            $data = $request->except('proof');

            if ($request->hasFile('proof')) {
                $fileName = Str::slug($deposit->user->first_name.'_proof_'.time());
                $returnedFilePath = $this->storeFile($request, $fileName);

                if ($returnedFilePath) {
                    $data['proof'] = $returnedFilePath;
                }
            }
            $oldFileName = array_reverse(explode('/', $deposit->proof))[0];
            $deposit->update($data);

            if ($request->hasFile('proof')) {
                Storage::delete('public/uploads/proofs/' . $oldFileName);
            }
            return redirect()->route('admin.users.show', $deposit->user)->with('success', 'Deposit Updated Successfully!');
        } catch (\Throwable $th) {
            if ($request->hasFile('proof')) {
                Storage::delete('public/uploads/proofs/' . $fileName); // delete the file
            }
            return redirect()->back()->with('failure', $th->getMessage())->withInput();
        }
    }

    public function update(Request $request, User $user)
    {

        $this->validate($request, [
            'first_name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email,' . $user->id],
            'password' => ['nullable', 'string', 'min:8', 'confirmed'],
            'phone' => ['required', 'numeric'],
            'phone_code' => ['required','string'],
            'country' => ['required','string'],
            'address' => ['nullable'],
            'permanent_address' => ['nullable'],
            // 'username' => ['required', 'string', 'max:50', 'unique:profiles'],
        ]);

        try {
            DB::beginTransaction();

        $data = $request->all();

          $user->update([
                'first_name' => $data['first_name'],
                'last_name' => $data['last_name'],
//                'address' => $data['address']??'',
//                'permanent_address' => $data['permanent_address'],
                'email' => $data['email'],
                'phone' => $data['phone'],
                'phone_code' => $data['phone_code'],
                'country' => $data['country']
                // 'delivery_category_id' => $data['delivery_category_id']
            ]);

            if (isset($request->password)) {
                $user->password = bcrypt($data['password']);
                $user->save();
            }

            $active = isset($request->active) ? true : false;

            if(setting('joint_account') == 1){
                $this->createJoint($user, $data);
            }

//            $user->syncRoles([$data['role']]);
            DB::commit();

            // $customer->assignRole('customer');

            return redirect()->back()->with('success', 'Admin Account has been updated');
        } catch(\Exception $e) {

            DB::rollback();
            return back()->with('failure', 'Woops! something went wrong: ' . $e->getMessage());

        }
    }

    public function destroy(User $user)
    {
        try {
            $user->delete();
            // $customer->profile->delete(); // delete corresponding user profile

        } catch(\Exception $e) {

            return route('admin.users.index')->with('failure', 'Woops! something went wrong: '.$e->getMessage());

        }

        return route('admin.users.index')->with('success', 'User account deleted.');
    }
    public function delete($id)
    {
        try {
            $user = User::findOrFail($id);
            if($id == auth()->id()){
                return redirect()->route('admin.users.index')->with('failure', 'Sorry you cant delete your own account');
            }else {
                $user->delete();
                return redirect()->route('admin.users.index')->with('success', 'User successfully deleted');
            }


        } catch(\Exception $e) {

            return back()->with('failure', 'Woops! something went wrong: '.$e->getMessage());

        }

        return back()->with('success', 'User account deleted.');
    }

    // public function deleteMultipleUsers(Request $request) {
    //     $request->validate([
    //         'selected_users' => 'required|array',
    //     ]);

    //     try {
    //         DB::beginTransaction();
    //         $selected_users = $request->selected_users;

    //         // user cannot delete himself
    //         if (($key = array_search(auth()->user()->id, $selected_users)) !== false) {
    //             unset($selected_users[$key]);
    //         }
    //         $users = User::whereIn('id', $selected_users)->get();
    //         foreach($users as $user) {
    //             $user->delete();
    //         }
    //         DB::commit();
    //         Session::flash('success', count($selected_users) . ' User(s) deleted successfully');
    //         return response()->json('success', 200);
    //     } catch (\Throwable $th) {
    //         DB::rollback();
    //         Session::flash('failure', $th->getMessage());
    //         return response()->json(['status' => 'error', 'message' => $th->getMessage()], 500);
    //     }
    // }

    public function createJoint($user, $data){
        $acct = $user;
        $acct->j_first_name = isset($data['j_first_name']) ? $data['j_first_name'] : '';
        $acct->j_last_name = isset($data['j_last_name']) ? $data['j_last_name'] : '';
        $acct->j_email = isset($data['j_email']) ? $data['j_email'] : '';
        $acct->j_phone = isset($data['j_phone']) ? $data['j_phone']: '';
        $acct->j_country = isset($data['j_country']) ? $data['j_country'] : '';
        $acct->save();
    }
    public function assignUsers($id){
        $managedUsers = User::whereRoleIs('user')->where('manager_id', $id)->get();
        $unmanagedUsers = User::whereRoleIs('user')->where('manager_id', null)->get();
        $title = "Assign Users";
        return view('admin.users.assignUsers', compact('managedUsers', 'unmanagedUsers','title','id'));
    }
    public function assignUsersSave(Request $request, User $user){
        // dd($request->input('id'));
        $ids = request()->input('id');
        foreach($ids as $id){
            $user->where('id', $id)->update(['manager_id' => $request->input('managerID')]);
        }
        // $managedUsers = User::where('manager_id', $id)->get();
        return redirect()->back()->with('success', 'Assigned Successfully');
    }

    public function storeStatus (Request $request) {
        $request->validate([
            'name' => 'required|string|unique:statuses,name'
        ]);

        $status = Status::create($request->all());
        Session::flash('success','Status Created!');
        return response()->json($status);
    }

    public function updateStatus (Request $request) {
        $request->validate([
            'status_id' => 'required|exists:statuses,id',
            'selected_users' => 'required'
        ]);

        try {
            User::whereIn('id', $request->selected_users)->update(['status' => $request->status_id]);
            Session::flash('success','Status updated successfully');
            return response()->json(['status' => 'success'], 200);
        } catch (\Throwable $th) {
            Session::flash('failure', $th->getMessage());
            return response()->json(['status' => 'error', 'message' => $th->getMessage()], 500);
        }
    }

    public function setStatuss ($status, $user) {
        try {
            User::findOrFail($user)->update(['status' => $status]);
            return back()->with('success', 'Status successfully updated.');
        } catch (\Throwable $th) {
            return back()->with('failure', 'Woops! something went wrong: ' . $th->getMessage());
        }
    }

    public function setSource ($source, $user) {
        try {
            User::findOrFail($user)->update(['source' => $source]);
            return back()->with('success', 'Source successfully updated.');
        } catch (\Throwable $th) {
            return back()->with('failure', 'Woops! something went wrong: ' . $th->getMessage());
        }
    }

    public function updateManager (Request $request) {
        $request->validate([
            'manager_id' => 'required|exists:users,id',
            'selected_users' => 'required'
        ]);

        try {
            User::whereIn('id', $request->selected_users)->update(['manager_id' => $request->manager_id]);
            Session::flash('success','Leads Assigned to managers successfully');
            return response()->json(['status' => 'success'], 200);
        } catch (\Throwable $th) {
            Session::flash('failure', $th->getMessage());
            return response()->json(['status' => 'error', 'message' => $th->getMessage()], 500);
        }
    }

    public function set_users_as_free (Request $request) {
        $request->validate([
            'selected_users' => 'required'
        ]);

        try {
             User::whereIn('id', $request->selected_users)->update(['manager_id' =>0]);
            // foreach ($users as $user) {
            //     $user->manager_id = '';
            //     $user->save();
            // }

            Session::flash('success','Set User Free successfully');
            return response()->json(['status' => 'success'], 200);
        } catch (\Throwable $th) {
            Session::flash('failure', $th->getMessage());
            return response()->json(['status' => 'error', 'message' => $th->getMessage()], 500);
        }
    }

    public function set_users_as_active (Request $request) {
        $request->validate([
            'selected_users' => 'required'
        ]);

        try {
            User::whereIn('id', $request->selected_users)->update(['archive_customer?' =>0]);
            // foreach ($users as $user) {
            //     $user->manager_id = '';
            //     $user->save();
            // }

            Session::flash('success','Set Users Active successfully');
            return response()->json(['status' => 'success'], 200);
        } catch (\Throwable $th) {
            Session::flash('failure', $th->getMessage());
            return response()->json(['status' => 'error', 'message' => $th->getMessage()], 500);
        }
    }

    public function set_users_as_archeive (Request $request) {
        $request->validate([
            'selected_users' => 'required'
        ]);

        try {
             User::whereIn('id', $request->selected_users)->update(['archive_customer?' =>1]);
            // foreach ($users as $user) {
            //     $user->manager_id = '';
            //     $user->save();
            // }

            Session::flash('success','Set User Archieved successfully');
            return response()->json(['status' => 'success'], 200);
        } catch (\Throwable $th) {
            Session::flash('failure', $th->getMessage());
            return response()->json(['status' => 'error', 'message' => $th->getMessage()], 500);
        }
    }

    public function storeSource (Request $request) {
        $request->validate([
            'name' => 'required|string|unique:sources,name'
        ]);

        $source = Source::create($request->all());
        Session::flash('success','Source Created!');
        return response()->json($source);
    }

    public function addNotes(Request $request) {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'content' => 'required',
            'contacted_at' => 'nullable|date'
        ]);

        try {
            $request->merge(['writer_id' => auth()->user()->id]);
            $note = Note::create($request->all());
            return response()->json($note, 201);
        } catch (\Throwable $th) {
            return response()->json(['status' => 'error', 'message' => $th->getMessage()], 500);
        }
    }

    public function takeBulkAction (Request $request) {
        $request->validate([
            'selected_users' => 'required|array',
            'password' => 'nullable',
            'confirm_password' => 'nullable|same:password'
        ]);

        // try {
            DB::beginTransaction();
            $users = User::whereIn('id', $request->selected_users);

            if ($request->filled('convertTo')) {
                foreach($users->get() as $user) {
                    $user->syncRoles([$request->convertTo]);
                    $user->new_password=1;
                    $user->password =  bcrypt(setting('new_password')??'Abc123');
//                    $user->manager_id = auth()->user()->id;
                    $user->save();
                }
            }

            if ($request->filled('source')) {
                $users->update(['source' => $request->source]); // update source id
            }

            if ($request->filled('status')) {
                $users->update(['status' => $request->status]); // update status id
            }

            if ($request->filled('password')) {
                $users->update(['password' => bcrypt($request->password)]); // update password
            }

            if ($request->delete) {
                // validate admin password
                if (!Hash::check($request->admin_password, auth()->user()->password)) {
                    // invalid password
                    return response()->json(['errors' => ['admin_password' => ['Invalid Admin Password']]], 422);
                }

                // perform mass delete
                $selected_users = $request->selected_users;
                if (($key = array_search(auth()->user()->id, $selected_users)) !== false) {
                    unset($selected_users[$key]);
                }
                User::whereIn('id', $selected_users)->delete();
            }

            DB::commit();
            if (!$request->filled('direct'))
                Session::flash('success', count($request->selected_users) . ' Customers bulk action implemented');

            return response()->json($users->with('notes')->with('statusrecord')->with('sourcerecord')->get(), 200);
        // } catch (\Throwable $th) {
        //     DB::rollback();
        //     Session::flash('failure', $th->getMessage());
        //     return response()->json(['status' => 'error', 'message' => $th->getMessage()], 500);
        // }
    }

    public function leadConvert(Request $request)
    {
        $request->validate([
            'selected_users' => 'required|array',
            'password' => 'required|string',
            'confirm' => 'same:password'
        ]);

        try {
            DB::beginTransaction();
            $users = User::whereIn('id', $request->selected_users)->get();
            foreach($users as $user) {
                $user->update(['password' => bcrypt($request->password)]);
                $user->syncRoles(['user']);
            }
            DB::commit();
            Session::flash('success', count($request->selected_users) . ' Lead(s) sucessfully converted to customer');
            return response()->json($users, 200);
        } catch (\Throwable $th) {
            DB::rollback();
            // Session::flash('failure', $th->getMessage());
            return response()->json(['status' => 'error', 'message' => $th->getMessage()], 500);
        }
    }

    public function updatePhone (Request $request) {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'phone' => 'required|numeric',
        ]);

        $user = User::findOrFail($request->user_id);
        $user->update(['phone' => $request->phone]);
        return response()->json($user);
    }

    public function storeLead(Request $request)
    {

        $this->validate($request, [
            'first_name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'phone' => ['nullable'],
            'address' => ['nullable'],
            'permanent_address' => ['nullable'],
            'source' => ['required', 'exists:sources,id'],
            'status' => ['required', 'exists:statuses,id'],
//            'username' => ['required', 'string', 'max:50', 'unique:users'],
        ]);

        $data = $request->all();

        $user = User::create([
            'first_name' => $data['first_name'],
            'email' => $data['email'],
            'source' => $data['source'],
            'status' => $data['status'],
            'country' => $data['country'],
            'phone_code' => $data['phone_code'],
            'is_active' => true,
            'can_trade' => 1,
//            'username' => $data['username'],
            'phone' => $data['phone'],
            'profit' =>  0,
            'fee' =>  0,
            'password' => bcrypt('Abc123'),
        ]);

        // assign role to registered user
        $user->attachRole('lead');

        Session::flash('success','Lead sucessfully added');

        return response()->json($user);
    }


    public function importLeads(Request $request){
        $request->validate([
            'import_file' => 'required|mimes:csv,txt'
        ]);
        Excel::import(new LeadImport(), request()->file('import_file'));

        return back()->with('success', 'Lead was successfully imported.');
    }

    // public function sendMessage(Request $request){
    //     $this->validate($request, [
    //         'user_id' => 'required|integer|exists:users,id',
    //         'message' => 'required|string',
    //         'subject' => 'required|string'
    //     ]);

    //     try {
    //         $user = User::findOrFail($request['user_id']);
    //         $this->message($user, $request['message'], $request['subject']);
    //         return $this->successResponse("Message successfully sent to ". $user->name);
    //     } catch (\Throwable $th) {
    //         return $this->errorResponse($th->getMessage(), 500);
    //     }
    // }

    // public function send_message_page($id)
    // {

    //     dd('dd');

    //     $user_id = $id;

    //     return view('admin.users.send_message', compact('user_id'));
    // }


}
