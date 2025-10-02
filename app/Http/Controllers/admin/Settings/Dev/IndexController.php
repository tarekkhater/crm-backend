<?php

namespace App\Http\Controllers\admin\Settings\Dev;

use Mail;
use App\Mail\TestMail;
use App\Models\Setting;
use Illuminate\Http\Request;
use App\Models\cryptoPayment;
use App\Models\PaymentGateWay;
use App\Rules\NoHtmlInjection;
use App\Http\Controllers\Controller;
use App\Http\Requests\UpdateDevSettingsRequest;
use App\Http\Requests\Admin\Settings\cryptomethodsRequest;
use App\Http\Requests\Admin\Settings\updatepaymentRequest;

class IndexController extends Controller
{

    public function fees()
    {
        $settings = Setting::select('id', 'key', 'value')->where('page', 'fees')->get();
        $this->setData($settings);
        $this->setMessage("success");
        return $this->sendApiResonse();
    }

    // public function site()
    // {
    //     $settings = Setting::select('id', 'key', 'value')->where('page', 'site')->get();
    //     $this->setData($settings);
    //     $this->setMessage("success");
    //     return $this->sendApiResonse();
    // }

    // public function api()
    // {
    //     $settings = Setting::select('id', 'key', 'value')->where('page', 'api')->get();
    //     $this->setData($settings);
    //     $this->setMessage("success");
    //     return $this->sendApiResonse();
    // }

    // public function payment()
    // {
    //     $settings = Setting::select('id', 'key', 'value')->where('page', 'payment')->get();
    //     $this->setData($settings);
    //     $this->setMessage("success");
    //     return $this->sendApiResonse();
    // }

    public function messages()
    {
        $settings = Setting::select('id', 'key', 'value')->where('page', 'mail')->get();
        $this->setData($settings);
        $this->setMessage("success");
        return $this->sendApiResonse();
    }

    // public function withdrawal()
    // {
    //     $settings = Setting::select('id', 'key', 'value')->where('page', 'withdrawal')->get();
    //     $this->setData($settings);
    //     $this->setMessage("success");
    //     return $this->sendApiResonse();
    // }

    // public function page()
    // {
    //     $settings = Setting::select('id', 'key', 'value')->where('page', 'page')->get();
    //     $this->setData($settings);
    //     $this->setMessage("success");
    //     return $this->sendApiResonse();
    // }

    // public function margin()
    // {
    //     $settings = Setting::select('id', 'key', 'value')->where('page', 'margin')->get();
    //     $this->setData($settings);
    //     $this->setMessage("success");
    //     return $this->sendApiResonse();
    // }


    // public function crm()
    // {
    //     $settings = Setting::select('id', 'key', 'value')->where('page', 'crm')->get();
    //     $this->setData($settings);
    //     $this->setMessage("success");
    //     return $this->sendApiResonse();
    // }



    // public function module()
    // {
    //     $settings = Setting::select('id', 'key', 'value')->where('page', 'module')->get();
    //     $this->setData($settings);
    //     $this->setMessage("success");
    //     return $this->sendApiResonse();
    // }

    public function mailsmtp()
    {
        $settings = Setting::select('id', 'key', 'value')->where('page', 'smtp')->get();
        $this->setData($settings);
        $this->setMessage("success");
        return $this->sendApiResonse();
    }

    public function lead()
    {
        $settings = Setting::select('id', 'key', 'value')->where('page', 'lead')->get();
        $this->setData($settings);
        $this->setMessage("success");
        return $this->sendApiResonse();
    }

    public function paymentmethods()
    {
        $settings = PaymentGateWay::select('id', 'name', 'status', 'min', 'max')->get();
        $this->setData($settings);
        $this->setMessage("success");
        return $this->sendApiResonse();
    }


    public function cryptomethods()
    {
        $settings = cryptoPayment::select('id', 'name', 'wallet', 'barcode')->get();
        $data = [];
        foreach ($settings as $setting) {
            $data[] = [
                'id' => $setting->id,
                'name' => $setting->name,
                'wallet' => $setting->wallet,
                'barcode' => baseUrl() . $setting->barcode
            ];
        }
        $this->setData($data);
        $this->setMessage("success");
        return $this->sendApiResonse();
    }


    public function createcryptomethods(cryptomethodsRequest $request)
    {

        cryptoPayment::create([
            'name' => $request->name,
            'wallet' => $request->wallet,
            'symbol' => $request->symbol,
            'barcode' => $this->uploadImage($request->barcode, 'crypto'),
        ]);
        $this->setMessage("success");
        return $this->sendApiResonse();
    }

    public function updatepayment(updatepaymentRequest $request)
    {
        PaymentGateWay::where('id', $request->id)->update([
            'name' => $request->name,
            'status' => $request->status,
            'min' => $request->min,
            'max' => $request->max,

        ]);
        $this->setMessage("success");
        return $this->sendApiResonse();
    }

    public function deletecrypto(Request $request)
    {
        $request->validate([
            'id' => "required|numeric|exists:crypto_payments,id"
        ]);
        cryptoPayment::where('id', $request->id)->delete();
        $this->setMessage("success");
        return $this->sendApiResonse();
    }


    public function update(UpdateDevSettingsRequest $request)
    {
        //  uploadImage(, 'settings')
        if (count($request->data) > 0) {
            foreach ($request->data as $value) {
                if($value['id'] == 6 || $value['id'] == 7 || $value['id']==101){
                     
                        Setting::where('id', $value['id'])->update([
                            'value' => uploadImage($value['value'], "settings") 
                            ]);
                }else{
                   
                    Setting::where('id', $value['id'])->update([
                    'value' => $value['value']
                    ]);
                }
                
            }
        }


        $this->setMessage("success");
        return $this->sendApiResonse();
    }

    public function updatedefault()
    {
        $settings = Setting::get();
        foreach ($settings as $setting) {
            $setting->value = $setting->defualt;
            $setting->save();
        }
        $this->setMessage("success");
        return $this->sendApiResonse();
    }

    public function TestEmailSmtp()
    {
        Mail::to(Setting::where('key', 'TEST_EMAIL')->first()->value)->send(new TestMail(Setting::where('key', 'TEST_EMAIL')->first()->value));
        $this->setMessage("success");
        return $this->sendApiResonse();
    }


    function uploadImage($file, $path)
    {

        $fileName = time() . '_' . $file->getClientOriginalName();
        $filePath = $file->storeAs('storage/' . $path, $fileName, 'public');
        return $filePath;
    }
}
