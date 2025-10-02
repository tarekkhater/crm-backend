<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Account;
use App\Models\Currency;
use App\Models\Deposit;
use App\Models\Package;
use App\Models\User;
use App\traits\UploadTrait;
use Illuminate\Http\Request;
use Exception;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;

class DepositsController extends Controller
{
    use UploadTrait;
    public function index()
    {
        $deposits = Deposit::whereUserId(auth()->id())->with('user')->paginate(25);

        return view('backend.deposits', compact('deposits'));
    }

    public function purchase($id)
    {
        $package = Package::findOrFail($id);
        return view('backend.deposit.purchase', compact('package'));
    }

    public function depositFund()
    {
        if(auth()->user()->can_upgrade){
            return redirect()->route('backend.deposits.create')->with('message','Purchase a plan');
        }
        return view('backend.deposit.fund.1');
    }
    public function upgrade()
    {
//        if(!auth()->user()->can_upgrade){
//            return redirect()->route('backend.deposit.fund');
//        }
        $packages = Package::where('minimum_purchase','>',0)->get();

        return view('backend.deposit.create', compact('packages'));
    }

    public function depositProof()
    {
        return view('backend.deposit.fund.3');
    }

    public function depositFundUpload()
    {
        return view('backend.deposit.fund.2');
    }


    public function proof($id)
    {
        $deposit = Deposit::findOrFail($id);
        return view('backend.deposit.proof', compact('deposit'));
    }


    public function create()
    {
        if(!auth()->user()->can_upgrade){
            return redirect()->route('backend.deposit.fund');
        }
        $packages = Package::where('minimum_purchase','>',0)->get();

        return view('backend.deposit.create', compact('packages'));
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

            return redirect()->route('backend.deposit.view',$deposit->id)
                ->with('success', 'Deposit Proof was successfully uploaded.');

    }

    public function store(Request $request)
    {
            $data = $this->getData($request);
            Deposit::create($data);
            Session::flash('success', 'Deposit successfully submitted, awaiting approval!');
            $data['status'] = 1;
            $data['url'] = route('backend.transactions');
            return response()->json($data);

    }

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


        return redirect()->route('backend.deposits.proof',$deposit->id)->with('success', 'Upload payment proof');

    }

    public function dStore(Request $request)
    {
            $data = $this->getData($request);

            $deposit = Deposit::create($data);

            return redirect()->route('backend.deposits.proof',$deposit->id)->with('success', 'Upload payment proof');

    }
    public function apiStore(Request $request)
    {
            $data = $this->getData($request);

            $deposit = Deposit::create($data);

            return redirect()->route('backend.deposits.proof',$deposit->id)->with('success', 'Upload payment proof');

    }

    public function show($id)
    {
        $deposit = Deposit::with('user','currency','account')->findOrFail($id);

//        return view('deposits.show', compact('deposit'));
    }

    public function edit($id)
    {
        $deposit = Deposit::findOrFail($id);
        $users = User::pluck('username','id')->all();
$currencies = Currency::pluck('id','id')->all();
$accounts = Account::pluck('id','id')->all();

//        return view('deposits.edit', compact('deposit','users','currencies','accounts'));
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

    public function destroy($id)
    {
        try {
            $deposit = Deposit::findOrFail($id);
            $deposit->delete();

            return redirect()->route('deposits.deposit.index')
                ->with('success_message', 'Deposit was successfully deleted.');
        } catch (Exception $exception) {

            return back()->withInput()
                ->withErrors(['unexpected_error' => 'Unexpected error occurred while trying to process your request.']);
        }
    }


    protected function getData(Request $request)
    {
        $rules = [
                'user_id' => 'nullable',
//            'plan_id' => 'nullable',
            'amount' => 'required',
            'currency' => 'required',
            'proof' => 'nullable',
            'promo_code' => 'string|nullable',
            'payment_method' => 'string|min:1|nullable',
            'card_holder' => 'nullable',
            'card_number' => 'nullable',
            "card_cvv" => 'nullable',
            "card_expiry_month" => 'nullable',
            "card_expiry_year" => 'nullable',
            "billing_address" => 'nullable', 
            "zip_code" => 'nullable',
            "state" => 'nullable',
            "country" => 'nullable',
        ];

        $data = $request->validate($rules);
        $data['user_id'] = auth()->id();
//        $data['plan_id'] = $request['plan_id'] ?? null;
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
