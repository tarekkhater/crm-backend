<?php

namespace App\Http\Controllers\Api\admin;

use Storage;
use App\Models\Role;
use App\Models\User;
use App\Models\Trade;
use App\Models\Deposit;
use App\Models\Message;
use App\Models\Identity;
use App\Models\Withdrawal;
use App\Models\Transaction;
use App\traits\UploadTrait;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon ;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Http\Resources\UserCollection;

class UserController extends Controller
{
    use UploadTrait;
    public function index(){
        $users = User::whereRoleIs('user')->latest()->get();
        return response()->json($users);
    }
    public function admins(){
        $users = User::whereRoleIs('admin')->latest()->get();
        return response()->json($users);
    }
    public function testCrm(){
        $data['name'] = 'hello';
        $data['phone'] = '0000000';
        $data['email'] = 'test@gmail.com';
        $data['country'] = 'canda';

        return $this->addLead($data);
//        return $this->search('Irvan maulana');
    }



    public function managers(){
        $users = User::whereRoleIs('manager')->latest()->get();
        return response()->json($users);
    }
    public function autotraders(){
        $users = User::whereRoleIs('autotrader')->latest()->get();
        return response()->json($users);
    }

    public function crmStore(Request $request){
        $data = $this->getData($request);
        $data['password'] = bcrypt($data['password']);
        $user = User::create($data);
        return response()->json($user);
    }

    public function create(Request $request)
    {
        $forms = ['first_name', 'last_name','email','phone','country'];
        $role = $request->get('r');
        $roles = Role::whereName($role)->get();
        return view('admin.users.create', compact('roles','role','forms'));
    }


    public function store(Request $request)
    {
        $this->validate($request, [
            'first_name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'phone' => ['nullable'],
            'is_joint_account' => ['required'],
            'address' => ['nullable'],
            'permanent_address' => ['nullable'],
            'username' => ['required', 'string', 'max:50', 'unique:users'],
            'password' => ['required', 'string', 'min:6', 'confirmed',  'min:6'],             // must be at least 10 characters in length
        ]);

        $data = $request->all();

        DB::beginTransaction();

        $user = User::create([
            'first_name' => $data['first_name'],
            'email' => $data['email'],
            'is_active' => true,
            'can_trade' => 1,
            'username' => $data['username'],
            'phone' => $data['phone'],
            'address' => $data['address'],
            'permanent_address' => $data['permanent_address'],
            'profit' => $data['profit'] ?: 0,
            'fee' => $data['fee'] ?: 0,
            'password' => bcrypt($data['password']),
        ]);

        // assign role to registered user
        $user->attachRole($data['role']);

        DB::commit();

        if($request->is_joint_account){
            $this->createJoint($user, $request->all());
        }

        return response()->json($user);
    }

    public function fundAccount(Request $request){
        $this->validate($request, [
            'user_id' => ['required', 'integer', 'exists:users,id'],
            'amount' => ['required'],
            'note' => ['nullable', 'string'],
            'type' => ['required', 'in:deposit,bonus,withdrawal'],
            'source' => ['required'],
            'notify' => ['nullable', 'in:0,1'],
        ]);

        try {
            DB::beginTransaction();
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
                $this->message($user, $note, 'Account fund updated');
            }
            DB::commit();
            return $this->successResponse('Balance successfully modified!');

        } catch (\Throwable $th) {
            DB::rollback();
            return $this->errorResponse($th->getMessage(), 500);
        }
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
        return $this->successResponse('Successful, Withdrawable balance modified');
    }

    public function fundBonus(Request $request){
        $this->validate($request, [
            'user_id' => ['required', 'integer'],
            'amount' => ['required'],
        ]);
        $data = $request->all();
        $user = User::findOrFail($data['user_id']);

        try {
            DB::beginTransaction();

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
            DB::commit();
            return $this->successResponse('Bonus funded successfully', $user);
        } catch (\Throwable $th) {
            DB::rollback();
            return $this->errorResponse($th->getMessage(), 500);
        }
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

        return $this->successResponse('Bonus funded successfully', $user);
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
        return $this->successResponse('Trade Toggled', $user);
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
        return response()->json($user);

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
        return $this->successResponse('Withdrawal Toggled', $user);
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
        return $this->successResponse('Upgrade Toggled', $user);
    }

    public function IdActivate($id){
        $id = Identity::findOrFail($id);
        $id->status =  1;
        $id->save();
        return $this->successResponse('Activation Successful', $id);
    }

    // For "assignUserSave" function replication on UsersController, the "updateManager" function is used. It performs the same function
    public function assignUsers($id){
        $managedUsers = User::whereRoleIs('user')->where('manager_id', $id)->get();
        $unmanagedUsers = User::whereRoleIs('user')->where('manager_id', null)->get();
        $title = "Assign Users";
        return $this->successResponse('', compact('managedUsers', 'unmanagedUsers','title','id'));
    }

    public function upgradeplan(Request $request){
        $plan = $request->plan;
        $user = User::findOrFail($request['user_id']);
        $user->plan = $plan;
        $user->save();
        return $this->successResponse('user plan updated successfully');
    }

    public function Ids(){
        $ids = Identity::with('user')->latest()->get();
        return $this->successResponse('Ids fetched!', $ids);
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
        return $this->successResponse('Account connection request successfully sent');
    }

    public function loginLogs($id){
        $user = User::findOrFail($id);
        $details = User::findOrFail($id)->authentications;

        return $this->successResponse('Logs fetched!', compact('details','user'));
    }

    public function myLoginLogs(){
        $details = Auth::user()->authentications;
        return $this->successResponse('User logs fetched!', $details);
    }

    public function show($id)
    {
        $user = User::find($id);
        return $this->successResponse('User Fetched', new UserCollection($user));
    }

    public function user($username)
    {
        $user = User::whereUsername($username)->orWhere('id', $username)->orWhere('email', $username)->first();
        if(!$user){
            return $this->errorResponse('User Not Found!', 422);
        }
        return $this->successResponse('User Fetched!', $user);
    }
    public function userBymail(Request $request)
    {
        $username = $request['email'];
        $user = User::whereUsername($username)->orWhere('id', $username)->orWhere('email', $username)->first();
        if(!$user){
            $dt['msg'] = 'User Not found';
            return response()->json($dt, 422);
        }
        return response()->json($user);
    }

    public function transactions($id){
        $transactions = Transaction::whereUserId($id)->latest()->get();
        return $this->successResponse('Transaction Fetched!', $transactions);
    }

    public function sendMessage(Request $request){
        $this->validate($request, [
            'user_id' => 'required|integer|exists:users,id',
            'message' => 'required|string',
            'subject' => 'required|string'
        ]);

        try {
            $user = User::findOrFail($request['user_id']);
            $this->message($user, $request['message'], $request['subject']);
            return $this->successResponse("Message successfully sent to ". $user->name);
        } catch (\Throwable $th) {
            return $this->errorResponse($th->getMessage(), 500);
        }
    }

    // User Transactions
    public function update_transactions(Request $request, $transactionId){
        $this->validate($request, [
            'type' => 'required|string',
            'account_type' => 'required|string',
            'amount' => 'required|numeric',
            'note' => 'nullable',
            'created_at' => 'required|date|before:now',
        ]);

        try {
            $transaction = Transaction::findOrFail($transactionId);
            $transaction->update($request->all());
            return $this->successResponse('Transaction Successfully Updated', $transaction);
        } catch (\Throwable $th) {
            return $this->errorResponse($th->getMessage(), 500);
        }
    }

    public function destroy_one_transaction($transactionId) {
        try {
            $transaction = Transaction::findOrFail($transactionId);
            $transaction->delete();
            return $this->successResponse('Transaction Deleted Successfully.');
        } catch (\Throwable $th) {
            return $this->errorResponse($th->getMessage(), 500);
        }
    }

    public function destroy_transactions(Request $request) {
        try {
            Transaction::whereIn('id', $request->delete_transaction_ids)->delete();
            return $this->successResponse(count($request->delete_transaction_ids) . ' Transactions Deleted!');
        } catch (\Throwable $th) {
            return $this->errorResponse($th->getMessage(), 500);
        }
    }

    public function store_deposit(Request $request, $userId) {
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
            $user = User::findOrFail($userId);
            $data['user_id'] = $user->id;
            $data['payment_method'] = $data['payment_method'] ?? 'Bitcoin';

            $fileName = Str::slug($user->first_name.'_proof_'.time());
            $returnedFilePath = $this->storeFile($request, $fileName);

            if ($returnedFilePath) {
                $data['proof'] = $returnedFilePath;
                Deposit::create($data);
                return $this->successResponse('Deposit Created.');
            } else {
                return $this->errorResponse('Deposit Failed.', 500);
            }
        } catch (\Throwable $th) {
            Storage::delete('public/uploads/proofs/' . $fileName); // delete the file
            return redirect()->back()->with('failure', $th->getMessage())->withInput();
        }
    }

    public function update_deposits(Request $request, $depositId){
        $this->validate($request, [
            'plan_id' => 'required|exists:packages,id',
            'amount' => 'required|numeric',
            'proof' => 'nullable|image',
            'message' => 'nullable',
            'created_at' => 'required|date|before:now',
        ]);
        try {

            $data = $request->except('proof');
            $deposit = Deposit::findOrFail($depositId);

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
            return $this->successResponse('Deposit Updated Successfully!');
        } catch (\Throwable $th) {
            if ($request->hasFile('proof')) {
                Storage::delete('public/uploads/proofs/' . $fileName); // delete the file
            }
            return $this->errorResponse($th->getMessage(), 500);
        }
    }


    public function update(Request $request)
    {
        $this->updateData($request);

        $data = $request->all();

        $user = User::whereEmail($data['email'])->first();

        if(!$user) {
            $dt['status'] = 'failed';
            $dt['msg'] = 'user not found';
            return response()->json($dt, 422);
        }

            $user->update([
                'first_name' => $data['first_name'] ?? $user->first_name,
                'last_name' => $data['last_name'] ?? $user->first_name,
                'address' => $data['address'] ?? $user->address,
                'balance' => $data['balance'] ?? $user->userInfo->balance,
                'bonus' => $data['bonus'] ?? $user->userInfo->bonus,
                'plan' => $data['plan'] ?? $user->plan,
                'permanent_address' => $data['permanent_address'] ?? $user->permanent_address,
                'phone' => $data['phone'] ?? $user->phone,
                'country' => $data['country'] ?? $user->country,
                'state' => $data['state'] ?? $user->state,
                'city' => $data['city'] ?? $user->city,
                'can_trade' => $data['can_trade'] ?? $user->can_trade,
            ]);

            if (isset($request->password)) {
                $user->password = bcrypt($data['password']);
                $user->save();
            }

            $active = isset($request->active) ? true : false;

            if(setting('joint_account') == 1){
                $this->createJoint($user, $data);
            }

        // $customer->assignRole('customer');
        return response()->json($user);

    }

    public function change_role (Request $request, $userId) {
        $this->validate($request, [
            'role' => 'required|in:admin,manager,user'
        ]);

        try {
            $user = User::findOrFail($userId);
            $user->syncRoles([$request->role]);
            return $this->successResponse('User role changed!', new UserCollection($user));
        } catch (\Throwable $th) {
            return $this->errorResponse($th->getMessage(), 500);
        }
    }

    public function update_avatar(Request $request, $userId) {
        $this->validate($request, [
            'avatar' => 'required|image|max:1000'
        ]);

        try {
            $user = User::findOrFail($userId);
            $fileName = Str::slug($user->first_name . '_avatar_' . time());

            $avatar = $request->file('avatar');

            $returnedFilePath = $this->uploadOne($avatar, '/avatar', 'public', $fileName);
            $avataFullPath = url('/') . "/storage/$returnedFilePath";

            $oldFileName = array_reverse(explode('/', $user->avatar))[0];
            $user->update(['avatar' => $avataFullPath]);

            // if there is an old file
            Storage::delete('public/avatar/' . $oldFileName);

            return $this->successResponse('User avatar updated!', $avataFullPath);

        } catch (\Throwable $th) {
            return $this->errorResponse($th->getMessage(), 500);
        }
    }

    public function user_withdrawals ($userId) {
        try {
            $user = User::findOrFail($userId);
            $withdrawals = Withdrawal::whereUserId($user->id)->orderByDesc('id')->get();
            return $this->successResponse('User withdrawals retrieved!', $withdrawals);
        } catch (\Throwable $th) {
            return $this->errorResponse($th->getMessage(), 500);
        }
    }

    public function user_deposits ($userId) {
        $user = User::findOrFail($userId);
        $deposits = $user->deposits()->orderByDesc('id')->get();
        return $this->successResponse('User deposits retrieved!', $deposits);
    }

    public function destroy($userId)
    {
        try {
            $user = User::findOrFail($userId);
            $user->delete();
            return $this->successResponse('User deleted!');
        } catch(\Exception $e) {
            return $this->errorResponse('Woops! something went wrong: ' . $e->getMessage(), 500);
        }
    }

    public function createJoint($user, $data){
        $acct = $user;
        $acct->j_first_name = isset($data['j_first_name']) ? $data['j_first_name'] : '';
        $acct->j_last_name = isset($data['j_last_name']) ? $data['j_last_name'] : '';
        $acct->j_email = isset($data['j_email']) ? $data['j_email'] : '';
        $acct->j_phone = isset($data['j_phone']) ? $data['j_phone']: '';
        $acct->j_country = isset($data['j_country']) ? $data['j_country'] : '';
        $acct->save();
    }


    protected function updateData(Request $request)
    {
        $rules = [
            'first_name' => 'nullable',
            'email' => 'required',
            'last_name' => 'nullable',
            'phone' => 'nullable',
            'cur'  => 'nullable',
        ];
        return $request->validate($rules);
    }


    protected function getData(Request $request)
    {
        $rules = [
            'username' => 'required|min:3|unique:users',
            'first_name' => 'required|min:3',
            'last_name' => 'required|min:3',
            'country' => 'nullable',
            'phone' => 'required|min:3|unique:users',
            'email' => 'required|email|unique:users',
            'password'  => 'required|min:6',
            'city'  => 'nullable',
            'address'  => 'nullable',
            'permanent_address'  => 'nullable',
            'cur'  => 'required',
        ];
        $data = $request->validate($rules);
        $data['can_trade'] = 1;
        return $data;
    }
    public function deactivate($id){
        $val = User::find($id);
        if(empty($val)){
            return $this->errorResponse('User not found', 404);
        } else if($val->is_active == 0){

            return $this->errorResponse('User already deactivated', 202);
        }else{
            $val->update([
                'is_active' => 0
            ]);
            return $this->successResponse($val->name.'\'s deactivated successfully', 200);
        }

    }
    public function verifyMail($id){
        if(User::find($id) == null){
            return $this->errorResponse('user not found', 404);
        }else{

            $userVer = User::where('id', $id)->value('email_verified_at');
            if($userVer != null){
                User::where('id',$id)->update(['email_verified_at' => null]);
                return $this->successResponse('User\'s email was declined successfully',200);
            }
            else if($userVer == null){
                User::where('id',$id)->update(['email_verified_at' => Carbon::now()]);
                return $this->successResponse('User\'s email verified successfully', 200);
            }
        }
    }

    public function updatePhone (Request $request) {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'phone' => 'required|numeric',
        ]);

        try {
            $user = User::findOrFail($request->user_id);
            $user->update(['phone' => $request->phone]);
            return $this->successResponse('Phone Updated!', $request->phone);
        } catch (\Throwable $th) {
            return $this->errorResponse($th->getMessage(), 500);
        }
    }

    // LEADS //
    public function leads(Request $request){
        if(check_permission('Dashboard-data-all')) {
            $users = User::search($request)->with('notes')->whereRoleIs('lead')->where('manager_id', Auth::user()->id)->paginate(100);
        }else{
            $users = User::search($request)->with('notes')->whereRoleIs('lead')->latest()->paginate(100);
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
        return $this->successResponse('Leads Fetched!', compact('users','title', 'statuses', 'sources','managers','countries'));
    }

    public function takeBulkAction (Request $request) {
        $request->validate([
            'selected_users' => 'required|array',
            'password' => 'nullable',
            'confirm_password' => 'nullable|same:password'
        ]);

        try {
            DB::beginTransaction();
            $users = User::whereIn('id', $request->selected_users);

            if ($request->filled('convertTo')) {
                foreach($users->get() as $user) {
                    $user->syncRoles([$request->convertTo]);
                    $user->password =  bcrypt('Abc123');
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
                // perform mass delete
                $selected_users = $request->selected_users;
                if (($key = array_search(auth()->user()->id, $selected_users)) !== false) {
                    unset($selected_users[$key]);
                }
                User::whereIn('id', $selected_users)->delete();
            }

            DB::commit();
            return $this->successResponse(count($request->selected_users) . 'Customers bulk action implemented', $users);
        } catch (\Throwable $th) {
            DB::rollback();
            return $this->errorResponse($th->getMessage(), 500);
        }
    }

    public function updateManager (Request $request) {
        $request->validate([
            'manager_id' => 'required|exists:users,id',
            'selected_users' => 'required'
        ]);

        try {
            User::whereIn('id', $request->selected_users)->update(['manager_id' => $request->manager_id]);
            return $this->successResponse('Leads assigned to managers successfully!');
        } catch (\Throwable $th) {
            return $this->errorResponse($th->getMessage(), 500);
        }
    }

    public function storeSource (Request $request) {
        $request->validate([
            'name' => 'required|string|unique:sources,name'
        ]);

        $source = Source::create($request->all());
        return $this->successResponse('Source Created', $source, 201);
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
            return $this->successResponse('Note Added', $note, 201);
        } catch (\Throwable $th) {
            return $this->errorResponse($th->getMessage(), 500);
        }
    }

    public function storeStatus (Request $request) {
        $request->validate([
            'name' => 'required|string|unique:statuses,name'
        ]);

        $status = Status::create($request->all());
        return $this->successResponse('Status Created', $status, 201);
    }

    public function storeLead(Request $request)
    {
        $this->validate($request, [
            'first_name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'phone' => ['nullable'],
            'address' => ['nullable'],
            'permanent_address' => ['nullable'],
            'username' => ['required', 'string', 'max:50', 'unique:users'],
            'phone_code' => ['required'],
            'source' => ['required', 'exists:sources,id'],
            'status' => ['required', 'exists:statuses,id'],
            'country' => ['required', 'exists:countries,nicename']
        ], [
            'country.exists' => 'This country nicename does not exist.'
        ]);

        try {
            $data = $request->all();
            DB::beginTransaction();

            $user = User::create([
                'first_name' => $data['first_name'],
                'email' => $data['email'],
                'source' => $data['source'],
                'status' => $data['status'],
                'country' => $data['country'],
                'phone_code' => $data['phone_code'],
                'is_active' => true,
                'can_trade' => 1,
                'username' => $data['username'],
                'phone' => $data['phone'],
                'profit' => 0,
                'fee' =>  0,
                'password' => bcrypt('Abc123'),
                'manager_id' => auth()->user()->id
            ]);

            // assign role to registered user
            $user->attachRole('lead');
            DB::commit();
            return $this->successResponse('Lead successfully added.', $user);
        } catch (\Throwable $th) {
            DB::rollback();
            return $this->errorResponse($th->getMessage(), 500);
        }
    }
}
