<?php

namespace App\Http\Controllers\User\finance\Deposits;

use App\Http\Controllers\Controller;
use App\Models\Account;
use App\Models\Currency;
use App\Models\Deposit;
use App\Models\Package;
use App\Models\Payment;
use App\Models\ProccessPayment;
use App\Models\WireAccount;
use App\Models\User;
use App\traits\UploadTrait;
use Illuminate\Http\Request;
use Exception;
use App\Models\Transaction;
use Illuminate\Support\Str;
use App\Http\Requests\User\Deposits\BankRequest;
use App\Http\Requests\User\Deposits\CreditRequest;
use App\Http\Requests\User\Deposits\USDTRequest;
class IndexController extends Controller
{
    use UploadTrait;


        public function index(Request $request){
            $deposites = Deposit::with(['user','plan','account'])->whereUserId(AuthApi()->id)->latest()->paginate(20);
            $this->setMessage("success");
            $this->setData($deposites);
            return $this->sendApiResonse();
        }




     public function storeXH(Request $request)
    {

            $data = $this->getData($request);
//            $package = Package::findOrFail($data['plan_id']);

        $proof = $request->file('proof');

        // Make a image name based on user name and current timestamp
        $name = Str::slug(auth()->user()->first_name.'_proof_'.time());

        // Define folder path
        $folder = '/uploads/proofs/';
        // Make a file path where image will be stored [ folder path + file name + file extension]
        $f_filePath = $folder . $name. '.' . $proof->getClientOriginalExtension();

        // Upload image
        $this->uploadOne($proof, $folder, 'public', $name);

        $data['proof'] = $f_filePath;

            $deposit = Deposit::create($data);

            $this->setMessage("success");
            return $this->sendApiResonse();
            return redirect()->route('backend.deposit.view',$deposit->id)
                ->with('success', 'Deposit Proof was successfully uploaded.');

    }

    // public function store(Request $request)
    // {
    //         $data = $this->getData($request);
    //         Deposit::create($data);
    //         Session::flash('success', 'Deposit successfully submitted, awaiting approval!');
    //         $data['status'] = 1;
    //         $data['url'] = route('backend.transactions');
    //         return response()->json($data);

    // }

    public function depositStore(Request $request)
    {
            $data = $this->getDData($request);


        $package = Package::findOrFail($data['plan_id']);
        if($package->minimum_purchase > $data['amount']){
            return redirect()->back()->withInput()->with('failure', 'Minimum deposit for this package is '.$package->minimum_purchase);
        }
        if($package->maximum_purchase < $data['amount']){
            return redirect()->back()->withInput()->with('failure', 'Maximum deposit for this package is '.$package->maximum_purchase);
        }

        $deposit = Deposit::create($data);



        $this->setMessage("success");
        return $this->sendApiResonse();
    }

    public function dStore(BankRequest $request)
    {
            $data = $this->getData($request);
             $user = AuthApi()->id;
               $wire =  WireAccount::create([
                    'user_id'=>$user,
                    'account_name'=>$request->firstName??0,
                    'account_number'=>$request->iban??0,
                    'bank_country'=>$request->country??0,
                    'bank_currency'=>$request->currency??0,
                    // 'bank_name'=>$request->bank_address??0,
                    'bank_branch'=>$request->bank_country??0,
                    'bank_address'=>$request->bank_address??0,
                    'sort_code'=>$request->sort_code??0,
                    'swift_code'=>$request->swift_code??0,
                    'iban_number'=>$request->iban??0,
                    'account_label'=>$request->firstName.' '.$request->lastName??0,
                ]);
            $data['payment_id']= $wire->id;
            $deposit = Deposit::create([
                'user_id'=>$user,
                "payment_id"=>$wire->id,
                "payment_method"=>"bank",
                "amount"=>$request->amount,
            ]);

            Transaction::create([
                'user_id'=>$user,
                'type'=>'deposit',
                'source'=>'bank',
                'amount'=>$request->amount,
                'account_type'=>'deposit',
                'note'=>'deposit',
            ]);

            $this->setMessage("success");
            return $this->sendApiResonse();
    }
    public function apiStore(CreditRequest $request)
    {
            $data = $this->getData($request);
            $user = auth()->user()->id;
            $payment = Payment::create([
                'user_id'=>$user,
                'name'=>$request->name,
                'iban'=>$request->card_number,
                'date'=>$request->expiry_date,
                'cvc'=>$request->cvc,
                'amount'=>$request->amount,
                'zib_code'=>$request->zip_code,
            ]);
            $data['payment_id']= $payment->id;
            $data['amount']= $request->amount;
            $data['payment_method']="credit";
            $deposit = Deposit::create($data);

            Transaction::create([
                'user_id'=>$user,
                'type'=>'credit',
                'source'=>'bank',
                'amount'=>$request->amount,
                'account_type'=>'deposit',
                'note'=>'deposit',
            ]);

            $this->setMessage("success");
            return $this->sendApiResonse();
    }


     public function crypto(USDTRequest $request){
       $user = AuthApi()->id;
        // $request->validate([

        // ]);
        $wire = ProccessPayment::create([
            'user_id'=>$user,
            'type'=>$request->type,
            'name'=>$request->name,
            'value'=>$request->address,
            'amount'=>$request->amount,
        ]);
        $withdrawal = Deposit::create([
            'user_id'=>$user,
            "payment_id"=>$wire->id,
            "currency"=>$request->type,
            "payment_method"=>"wallet",
            "amount"=>$request->amount,
        ]);

        $wire->deposit_id = $withdrawal->id;
        $wire->save();
        Transaction::create([
            'user_id'=>$user,
                'type'=>'wallet',
                'source'=>'bank',
                'amount'=>$request->amount,
                'account_type'=>'deposit',
                'note'=>'deposit',
            ]);
        $this->setMessage("success");
        return $this->sendApiResonse();
    }



    public function show($id)
    {
        $deposit = Deposit::with('user','currency','account')->findOrFail($id);

    }



    public function update($id, Request $request)
    {

            $data = $this->getUData($request);

            $deposit = Deposit::findOrFail($id);

            $proof = $request->file('proof');

        // Make a image name based on user name and current timestamp
        $name = Str::slug(auth()->user()->first_name.'_proof_'.time());

        // Define folder path
        $folder = '/uploads/proofs/';
        // Make a file path where image will be stored [ folder path + file name + file extension]
        $f_filePath = $folder . $name. '.' . $proof->getClientOriginalExtension();

        // Upload image
        $this->uploadOne($proof, $folder, 'public', $name);

        $data['proof'] = $f_filePath;

        $deposit->update($data);

            return redirect()->route('backend.deposit.view',$id)
                ->with('success', 'Deposit Proof was successfully uploaded.');
    }




    protected function getData(Request $request)
    {
        $data['user_id'] = auth()->id();
        $data['payment_method'] = $data['payment_method'] ?? 'Bitcoin';
        return $data;
    }
    protected function getDData(Request $request)
    {
        $rules = [
                'user_id' => 'nullable',
            'plan_id' => 'nullable',
            'amount' => 'required',
            'proof' => 'nullable',
            'promo_code' => 'string|nullable',
            'payment_method' => 'string|min:1|nullable',
        ];

        $data = $request->validate($rules);
        $data['user_id'] = auth()->id();
        $data['plan_id'] = $request['plan_id'] ?? null;
        $data['payment_method'] = $data['payment_method'] ?? 'Bitcoin';
        return $data;
    }
    protected function getUData(Request $request)
    {
        $rules = [
            'proof' => 'required',
        ];

        $data = $request->validate($rules);

        return $data;
    }

}
