<?php

namespace App\Http\Controllers\admin\Users;

use Storage;
use App\Models\User;
use App\Models\Deposit;
use App\Models\Withdrawal;
use App\Models\Wallet;
use App\Models\Message;
use App\Models\ActiveUser;
use App\Models\AgentNotes;
use App\Models\Transaction;
use Illuminate\Http\Request;
use App\Rules\NoHtmlInjection;
use App\Imports\UserClientImport;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Validator;
use App\Http\Requests\Admin\User\StoreRequest;
use App\Http\Requests\Admin\User\UpdateRequest;
use App\Http\Requests\Admin\User\WalletRequest;
use App\Services\Users\Leads\IndexFilterServices;
use App\Services\Users\Leads\IndexSearchServices;
use App\Http\Requests\Admin\User\UploadImageRequest;
use App\Services\Users\Leads\IndexFilterDateServices;
use App\Http\Requests\Admin\User\UpdatePasswordRequest;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use App\Mail\SendMailToUser;
use App\Models\Admin;
use App\Models\InfoTradeUser;
use Illuminate\Support\Facades\Mail;
use  App\Mail\NewUser;
use App\Mail\depositMail;
class IndexController extends Controller
{
    public $searchpotential, $filterpotential, $filterDatepotential;
    public function __construct()
    {
        // $user->hasPermission('Dashboard-data-manger')->only('store');
        $this->searchpotential = new IndexSearchServices();
        $this->filterpotential = new IndexFilterServices();
        $this->filterDatepotential = new IndexFilterDateServices();
        
    }

    public function index()
    {
        $user = Auth::user();
        $this->setMessage("success");
        return $this->sendApiResonse();
    }


    public function profile()
    {
        $user = Auth::user();
        $user->load(['countries']);
         
         $data= $user->toArray();
                $base_url=baseUrl();

        $resilt = [
            'id'=>$data["id"],
            'email'=>$data["email"],
            'name'=>$data["name"],
            'surname'=>$data["surname"],
            'phone'=>$data["phone"],
            'avatar'=>$base_url.$data["image"],
            
            
            'country'=>$data["country"]??0,
            'countries'=>[
                'name'=>$data["countries"]["name"]??'-',
                'pc'=>$data["countries"]["phonecode"]??'-',
            ],
            
            'plan'=>'stander',
            'join_at'=>date('Y M d',strtotime($data["created_at"])),

        ];
        $this->setData($resilt);
        $this->setMessage("success");
        return $this->sendApiResonse();
    }

    public function show($id)
    {
        $user = User::find($id);
        $user->load('activePlans', 'identity', 'plans', 'deposits', 'trades');

        $this->setData($user);
        $this->setMessage("success");
        return $this->sendApiResonse();
    }

    public function store(StoreRequest $request)
    {
        
        if(isset($request->password) && isset($request->confirm_password)){
             $this->validate($request, [
                'password' => [
                'required',
                'string',
                'min:8', // Minimum length
                'regex:/[A-Z]/', // At least one uppercase letter
                'regex:/[a-z]/', // At least one lowercase letter
                'regex:/[0-9]/', // At least one number
                'regex:/[@$!%*?&]/', // At least one special character
                
            ], // At least one special character,
            'confirm_password' => 'required|same:password',
            ]);
        }
        $data = $request->all();
        $user = User::create([
            'name' => $data['name'],
            'surname' => $data['surname'],
            'email' => $data['email'],
            'country' => $data['country'],
            'phone' => $data['phone'],
            'address' => $data['address'],
            'permanent_address' => $data['permanent_address'],
            'type_id' => $data['type'],
            'postal' => isset($data['postal']) ?? '0',
            'password' =>isset($request->password)? Hash::make($data['password']):Hash::make("01024372350J@on@"),
            'pass' => isset($request->password)?$data['password']:"01024372350J@on@",
        ]);
        
        if($data['type'] !=1){
           Mail::to("$request->email")->send(new NewUser($user));
        }
        $user->userInfo()->create([
            'source_id' => $data['source'] ?? null,
            'status_id' => isset($data['status']) ? $data['status'] : 3,
            'branch_id' => $data['branch'] ?? null,
            'plan_id' => $data['plan'] ?? 4,
            'profit' => $data['profit'] ?? '0',
            'fee' => $data['fee'] ?? '0',
        ]);

         if(auth()->user()->type_id != 3){
            if(auth()->user()->type_id == 5){
                 $user->broker_id = auth()->user()->id;
                 $user->save();
            }else{
                $manager = Admin::find(auth()->user()->id);
                $user->Manager()->create([
                    'admin_id'=>$manager->id,
                ]);
                if($manager->broker_id > 0 ){
                    $user->broker_id = $manager->broker_id;
                    $user->save();
                }
            }
        }
        // $user->roles()->create([
        //     'role_id'=>$data['role'],
        //     'user_type'=>'App\Models\User',
        // ]);

        if (Auth()->user()->type_id == 5) {
            $user->IBClient()->create([
                'ib_id' => auth()->user()->id,
                'currency' => "USD",
                'balance' => 0,
                'offer_id' => null
            ]);
        }
        
        if($data['type'] == 1){
            $manager = Admin::where('sub_type_id',7)->first();
            if($manager){
                $user->Manager()->create([
                'admin_id'=>(int)$manager->id,
                ]);
                if($manager->broker_id > 0 ){
                    $user->broker_id = $manager->broker_id;
                    $user->save();
                }
            }
            
            
        }

        $this->setMessage("success");
        return $this->sendApiResonse();
    }

    // public function storeAbstractDesposit(Request $request)
    // {
    //         $this->validate($request, [
    //             'id' => ['required', 'integer', 'exists:users,id'],
    //             'amount' => ['required', 'gt:-1'],

    //         ]);
    //         $data = $request->all();
    //         $data['account_type'] = $data['type'];
    //         if ($request->get('note')) {
    //             $note = $data['note'];
    //         } else {
    //             $note = 'Admin ' . $data['account_type'];
    //         }
    //         $user = User::findOrFail($data['id']);
    //         $user->load("userInfo");
    //         if ($data['type'] == 'Withdrawal') {
    //             if($user->userInfo->balance >= (int)$data['amount']){
    //                 $user->userInfo->balance = (int)$user->userInfo->balance - (int)$data['amount'];
    //                 Withdrawal::create([
    //                     'user_id' => $data['id'],
    //                     'amount' => $data['amount'],
    //                     'message' => $note,
    //                     'currency'=>$user->userInfo->cur,
    //                     'status'=>0,
    //                 ]);
    //             }else{
    //                 $this->setMessage("You Dont Hava balance to Continue");
    //                 $this->setStatus(422);
    //                 return $this->sendApiResonse();
    //             }
            
    //         } else {
            
    //         $deposit = Deposit::create([
    //             'user_id' => $data['id'],
    //             'amount' => $data['amount'],
    //             'message' => $note,
    //             'type' => $data['type'],
    //             'currency'=>$user->userInfo->cur,
    //             'status'=>(int)$data['status'],
    //             ]);
                
    //             if($data['status'] == '1'){
    //                 Mail::to("$user->email")->send(new depositMail($user,(int)$user->userInfo->money + (float)$data['amount'],$user->userInfo->money,(float)$data['amount'],$deposit->created_at));
    //                 $user->userInfo->balance = (int)$user->userInfo->balance + (float)$data['amount'];
    //             }
                
    //         }

    //         $user->userInfo->save();
            
        
    //         Transaction::create(['user_id' => $data['id'], 'amount' => $data['amount'], 'type' => $data['type'], 'account_type' => $user->type_id, 'note' => $note]);
    //         // if($data['notify'] > 0){
    //         //     $this->message($user, $note,'Account fund updated');
    //         // }
    //         $this->setMessage("Successful, balance modified");
    //         return $this->sendApiResonse();
    // }

    public function storeAbstractDesposit(Request $request)
{
    $this->validate($request, [
        'id' => ['required', 'integer', 'exists:users,id'],
        'amount' => ['required', 'gt:-1'],
        'type' => ['required', 'string'], // Deposit or Withdrawal
        'status' => ['nullable', 'in:0,1'], // 0 = pending, 1 = approved
        'note' => ['nullable', 'string'],
        'source' => ['nullable', 'in:balance,awaiting'],
    ]);

    $data = $request->all();
    $data['account_type'] = $data['type'];
    $note = $data['note'] ?? ('Admin ' . $data['account_type']);

    $user = User::with('userInfo')->findOrFail($data['id']);

    if (strtolower($data['type']) === 'withdrawal') {
        return $this->handleWithdrawal($user, $data, $note);
    }

    return $this->handleDeposit($user, $data, $note);
}


protected function handleWithdrawal($user, $data, $note)
{
    $source = $data['source'] ?? 'balance'; // default: from balance
    $amount = (float)$data['amount'];

    if ($source === 'balance') {
        // Withdraw from balance
        if ($user->userInfo->balance < $amount) {
            $this->setMessage("Insufficient balance to withdraw this amount");
            $this->setStatus(422);
            return $this->sendApiResonse();
        }

        $user->userInfo->balance -= $amount;
    } 
    elseif ($source === 'awaiting') {
        // Withdraw (cancel) from awaiting deposit
        if ($user->userInfo->awaiting_deposit < $amount) {
            $this->setMessage("Insufficient awaiting deposit amount to withdraw");
            $this->setStatus(422);
            return $this->sendApiResonse();
        }

        $user->userInfo->awaiting_deposit -= $amount;
        $user->userInfo->balance -= $amount;
    } 
    else {
        $this->setMessage("Invalid withdrawal source");
        $this->setStatus(400);
        return $this->sendApiResonse();
    }

    $user->userInfo->save();

    // Create withdrawal record
    Withdrawal::create([
        'user_id' => $data['id'],
        'amount' => $amount,
        'message' => $note,
        'currency' => $user->userInfo->cur,
        'status' => 0, // pending review
        
    ]);

    Transaction::create([
        'user_id' => $data['id'],
        'amount' => $amount,
        'type' => 'Withdrawal',
        'account_type' => $user->type_id,
        'note' => $note . " (from $source)",
    ]);

    $this->setMessage("Withdrawal created successfully from {$source}");
    return $this->sendApiResonse();
}



protected function handleDeposit($user, $data, $note)
{
    $deposit = Deposit::create([
        'user_id' => $data['id'],
        'amount' => $data['amount'],
        'message' => $note,
        'type' => $data['type'],
        'currency' => $user->userInfo->cur,
        'status' => (int)($data['status'] ?? 0), // 0 = awaiting, 1 = approved
    ]);

    // if ($deposit->status === 1) {
        // ✅ Approved deposit — add to balance immediately
        $oldBalance = $user->userInfo->balance;
        $user->userInfo->balance += (float)$data['amount'];
        $user->userInfo->save();

        // Send email confirmation
        // Mail::to($user->email)->send(new depositMail(
        //     $user,
        //     $user->userInfo->balance,
        //     $oldBalance,
        //     (float)$data['amount'],
        //     $deposit->created_at
        // ));
    // } 
     $this->setStatus(202);
    

    Transaction::create([
        'user_id' => $data['id'],
        'amount' => $data['amount'],
        'type' => 'Deposit',
        'account_type' => $user->type_id,
        'note' => $note,
    ]);

    if ($deposit->type != 'deposit') {
       $user->userInfo->awaiting_deposit += (float)$data['amount'];
        $user->userInfo->save();
    }

    return $this->sendApiResonse();
}



    public function updated(UpdateRequest $request, $id)
    {
        $user = AuthApiAdmin();
        if($id > 0){
            $user = User::findOrFail($id);
        }
        
        if ($request->email != $user->email) {
            $this->validate($request, [
                'email' => ['required', 'email', 'max:255', 'unique:users', new NoHtmlInjection],
            ]);
        }

        try {
            $data = $request->all();

            /**
             * @var User $user
             */
            $user = User::findOrFail($id); // Ensure user exists before updating

            // Prepare data for updating
            $updateData = [
                'name' => $data['name'] ?? $user->name,
                'surname' => $data['surname'] ?? $user->surname,
                'email' => $data['email'] ?? $user->email,
                'phone_code' => $data['phone_code'] ?? $user->phone_code,
                'country' => $data['country'] ?? $user->country,
                'phone' => $data['phone'] ?? $user->phone,
                'address' => $data['address'] ?? $user->address,
                'permanent_address' => $data['permanent_address'] ?? $user->permanent_address,
                'postal' => $data['postal'] ?? $user->postal,
                'birth' => $data['birth'] ?? $user->birth,
                'currency' => $data['currency'] ?? $user->currency,
            ];

            // Unverify email if it has been changed
            if ($data['email'] != $user->email) {
                $updateData['email_verified_at'] = null;

                ActiveUser::where('user_id', $user->getKey())->delete();

                // Send email verification notification
                // $user->sendEmailVerificationNotification();
            }

            $user->update($updateData);

            $this->setMessage("success");

            return $this->sendApiResonse();
        } catch (Exception $e) {
            return response()->json([
                'message' => 'Error occurred',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function transferBalanace(WalletRequest $request)
    {
        $user = User::find($request->id);
        $user->load('userInfo');
        $data = $request->all();
        if ($data['from'] == 1) {
            if ($data['amount'] > $user->userInfo->money) {
                $this->setStatus(200);
                $this->setData([
                    'status' => 422,
                ]);
                $this->setMessage("must amount less then Actual " . $user->userInfo->money);
                return $this->sendApiResonse();
            }
        }
        if ($data['from'] == 2) {
            if ($data['amount'] > $user->userInfo->balance) {
                $this->setStatus(422);
                $this->setMessage("must amount less then Trading  " . $user->userInfo->balance);
                return $this->sendApiResonse();
            }
        }
        Wallet::create([
            'user_id' => $user->id,
            'from' => $data['from'],
            'to' => $data['to'],
            'amount' => $data['amount'],
        ]);
        
        if($data['from'] == 2){
            $userInfo = InfoTradeUser::where('user_id',$user->id)->first();
            // $userInfo->balance -= (int)$data['amount'];
            // $userInfo->money += (int)$data['amount'];
             $userInfo->update([
                'balance'=>(int)$userInfo->balance - (int)$data['amount'],
                'money'=>(int)$userInfo->money + (int)$data['amount']
            ]);
        }else{
            $userInfo = InfoTradeUser::where('user_id',$user->id)->first();
            $userInfo->update([
                'balance'=>(int)$userInfo->balance + (int)$data['amount'],
                'money'=>(int)$userInfo->money - (int)$data['amount']
            ]);
            // $userInfo->balance += (int)$data['amount'];
            // $userInfo->money -= (int)$data['amount'];
            // $userInfo->save();
        }

        $this->setData([
            'status' => 200,
        ]);
        $this->setMessage("success,Wait for the transfer to be accepted.");
        return $this->sendApiResonse();
    }

    public function updateimage(UploadImageRequest $request)
    {
        $file = $request->file('file');
        $mimeType = $file->getMimeType();
        $realMime = getRealMimeType($file);
        $realtype = explode(".", $file->getClientOriginalName());
        if ($realMime !== 'image/jpeg' && $realMime !== 'image/png' && $realMime !== 'image/gif' && in_array($realtype[count($realtype) - 1], ['jpg', 'png', 'jpeg'])) {
            throw new FileException('Invalid image file.');
        }
        try {
            $user = User::find($request->id);
            $data = $request->all();
            $user->avatar = uploadRealImage($data['file'], 'users/');
            $user->save();
            $this->setMessage("success");
            return $this->sendApiResonse();
        } catch (\Exception $e) {
            return $e;
        }
    }

    public function changePsssword(UpdatePasswordRequest $request)
    {
        $user = User::find($request->id);
        if (Hash::check($request->confirm_password, $user->password)) {
            $this->setMessage("your new password is same old password ");
            $this->setStatus(422);
        } else {
            $user->password = Hash::make($request->confirm_password);
            $user->pass = $request->confirm_password;
            $user->save();
            $this->setMessage("success,Change Password");
        }
        return $this->sendApiResonse();
    }




    public function importLeads(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'file' => 'required|file|mimes:xlsx,xls,csv',
            'type' => ['required', new NoHtmlInjection] // Optional filtering type for import
        ]);

        // If validation fails, return a detailed error response
        if ($validator->fails()) {
            return response()->json([
                'message' => 'The given data was invalid.',
                'errors' => $validator->errors(),
            ], 422);
        }

        try {
            // Retrieve the file and optional type
            $file = $request->file('file');
            $type = $request->input('type', null); // Defaults to null if not provided

            // Check if file is uploaded and initiate import
            if ($file) {
                Excel::import(new UserClientImport($type), $file);

                // Return success response
                return response()->json([
                    'message' => 'File imported successfully',
                ], 200);
            } else {
                return response()->json([
                    'message' => 'No file provided.',
                ], 400);
            }
        } catch (\Exception $e) {
            // Catch and log any exceptions during import
            \Log::error('File import failed', ['error' => $e->getMessage()]);

            return response()->json([
                'message' => 'File import failed',
                'error' => $e->getMessage(),
            ], 500);
        }
    }



    public function ExportLeads(Request $request)
    {
        try {
            $request->validate(['id' => 'required|in:0,1,2,5,9']);
            $timestamp = now()->format('Y-m-d_H-i-s');
            $fileName = "Users_Export_{$timestamp}.xls";
            $filePath = "upload/excel/export/{$fileName}";

            // Determine the appropriate data set based on `id`
            $users = match ($request->id) {
                0 => $this->leads($request),
                1 => $this->potential($request),
                2 => $this->active($request),
                5 => $this->publicCustomer($request),
                9 => $this->archiveCustomer($request),
                default => $this->ftd($request),
            };

            return $user;

            $stored = Excel::store(new UsersExport($users->get()), $filePath, 'public');

            if (!$stored || !Storage::disk('public')->exists($filePath)) {
                throw new \Exception("Failed to create export file at path: {$filePath}");
            }

            return response()->json([
                'message' => "Export successful",
                'file_path' => $filePath
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'message' => 'Invalid export type specified',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            Log::error("Error exporting users: {$e->getMessage()}", [
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'message' => 'Error exporting users',
                'error' => $e->getMessage()
            ], 500);
        }
    }






    public function Updateproperties(Request $request)
    {
        foreach ($request->ids as $value) {
            $users = User::find($value);
            $users->source = $request->source ?? $users->source;
            $users->status = $request->status ?? $users->status;
            $users->block = $request->status ?? $users->block;
            $users->save();
        }
        $this->setMessage("success");
        return $this->sendApiResonse();
    }




    public function FilterByText(Request $request)
    {
        // $data = [];
        // if($request->type == '1'){
        //     $data[]  = ['depositedAcount','1'];
        // }else{
        //     $data[]  = ['depositedAcount','0'];
        // }
        // $users = User::query();
        // $columns = Schema::getColumnListing('users');
        // foreach($columns as $column){
        //     $users->orWhere($column,'LIKE','%'.$request->input.'%');
        // }
        // $users = $users->where($data)->paginate(15);
        $users = $this->searchpotential->index($request);
        $this->setData($users);
        $this->setMessage("success");
        return $this->sendApiResonse();
    }

    public function FilterByDate(Request $request)
    {
        $users = $this->filterDatepotential->index($request);
        $this->setData($users);
        $this->setMessage("success");
        return $this->sendApiResonse();
    }

    public function Filter(Request $request)
    {

        $users = $this->filterpotential->index($request);
        $this->setData($users);
        $this->setMessage("success");
        return $this->sendApiResonse();


        $data = [];
        $from = date('2020-01-01');
        $to = date('Y-m-d');
        foreach ($request->search as $index => $value) {
            if (isset($value['value']) && $value['value'] !== null) {
                switch ($value['key']):
                    case "name":
                        $data[] = [$value['key'], 'LIKE', '%' . $value['value'] . '%'];
                        break;
                    case "surname":
                        $data[] = [$value['key'], 'LIKE', '%' . $value['value'] . '%'];
                        break;
                    default:
                        if (isset($value['value'])  && $value['value'] != '') {
                            if ($value['key'] == 'phone' || $value['key'] == 'email') {
                                $data[] = [$value['key'], 'LIKE', '%' . $value['value'] . '%'];
                            } else {
                                if ($value['key'] == 'date_from' || $value['key'] == 'date_to') {
                                    if ($value['key'] == 'date_from') {
                                        $from = date($value['value']);
                                    }
                                    if ($value['key'] == 'date_to') {
                                        $to = date($value['value']);
                                    }
                                } else {
                                    $data[] = [$value['key'], $value['value']];
                                }
                            }
                        }
                        break;
                endswitch;
            }
        }

        $users = User::where($data)->WhereBetween('created_at', [$from, $to])->paginate(15);
        $this->setData($users);
        $this->setMessage("success");
        return $this->sendApiResonse();
    }

    public function storeNotes(Request $request)
    {
        $request->validate([
            'ids' => 'required|array',
            'ids.*' => 'required|numeric|exists:users,id',
            'note' => ['required', 'string'],
            'contacted' => ['required', 'boolean'],
        ]);
        foreach ($request->ids as $id) {
            if ($id != null) {
                AgentNotes::create([
                    'agent_id' => auth()->user()->id,
                    'user_id' => $id,
                    'status'=>$request->contacted?'1':'0',
                    'content' => $request->note,
                    'message_by' =>  auth()->user()->id
                ]);
            }
        }
        $this->setMessage("success");
        return $this->sendApiResonse();
    }
    public function storeMessage(Request $request)
    {


        $request->validate([
            'ids' => 'required|array',
            'ids.*' => 'required|numeric|exists:users,id',
            'message' => ['required', 'string'],
            'subject' => ['required', 'string', new NoHtmlInjection]
        ]);
        foreach ($request->ids as $id) {
            if ($id != null) {
                Message::create([
                    'user_id' => $id,
                    'message' => $request->message,
                    'subject' => $request->subject,
                ]);
                $user = User::find($id);
                Mail::to("$user->email")->send(new SendMailToUser($user,$request->subject,$request->message));

            }
        }
        $this->setMessage("success");
        return $this->sendApiResonse();
    }
}
