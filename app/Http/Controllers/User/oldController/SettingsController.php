<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Role;
use App\Models\Setting;
use App\Models\Plan;
use App\Models\ApiToken;
use App\Models\CurrencyPair;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\DB;

class SettingsController extends Controller
{

    public function __construct()
    {
        $this->middleware('RoleMiddleware:view-settings_update-settings')->only(['fees','site_settings',
            'marginCall','withdrawal','payment_methods','cPayment','leadConfig']);
        $this->middleware('RoleMiddleware:view-dev-settings_update-dev-settings')->only(['smtp','crm','mails',
            'modules','index','apis','pages']);
    }

    public function addRoles(){
        $roles = [
            'name' => 'manager', 'display_name' =>  'Manager'
        ];

        foreach ($roles as $role){
            $role = Role::whereName($role)->first();
            if(!$role){
                Role::create([
                    'name' => $role['name'],
                    'display_name' => $role['display_name'],
                    'description' => $role['display_name'],
                ]);
            }
        }

        return 'done';
    }
    public function index(){

        $rows=Setting::where('value','like','%https://ssct.trading-terminal.online%')->get();
         foreach($rows as $row){
            $row->update([
                "value"=>str_replace("https://ssct.trading-terminal.online/"
                ,asset('/'),$row['value'])
            ]);
         }
         $curancys=CurrencyPair::where("image",'like','%https://ssct.trading-terminal.online%')->get();

       foreach($curancys as $curancy){
        $curancy->update([
            "image"=>str_replace("https://ssct.trading-terminal.online/"
            ,asset('/'),$curancy['image'])
        ]);
       }

         return view('admin.settings.home');
    }
    public function activityNotifications(){
        return view('admin.settings.activity_notification_settings');
    }
    public function mails(){
        return view('admin.settings.mails');
    }
    public function notifications(){
        return view('admin.settings.notifications');
    }
    public function smtp(){
        return view('admin.settings.smtp');
    }
    public function marginCall(){
        return view('admin.settings.margin_call');
    }
    public function fees(){
        return view('admin.settings.fees');
    }
    public function pages(){
        return view('admin.settings.pages');
    }
    public function apis(){
        return view('admin.settings.apis');
    }
    public function leadConfig(){
        $lead_count = User::whereWebhook('getresponse')->count();
        return view('admin.settings.lead_config', compact('lead_count'));
    }

    public function crm(){
        return view('admin.settings.crm');
    }
    public function withdrawal(){
        return view('admin.settings.withdrawal');
    }
    public function cPayment(){
        return view('admin.settings.c_payment');
    }
    public function twak_io(){
        return view('admin.settings.twak_io');
    }

    public function testmail(){
        Mail::send('test', [], function($message) {
            $message->to(setting('TEST_EMAIL'))->subject('Testing mails');
        });
        return 'email successfully sent, check '.setting('TEST_EMAIL');
    }

    public function modules(){
        return view('admin.settings.module');
    }
    public function site_settings(){
        //add default plans from admin panel
        $plans = Plan::all();
        return view('admin.settings.site_settings', compact('plans'));
    }

    public function payment_methods(){
        return view('admin.settings.payment_methods');
    }

    public function store(Request $request){
       // dd("hello");
        setting($request->except('_token'))->save();
      //  return redirect()->back()->with('success', 'Settings updated successfully');
        return redirect()->back()
        ->with('success', 'Settings updated successfully');
    }

    public function storesmtp(Request $request){

        setting($request->except('_token'))->save();

        $this->setEnvironmentValue('APP_DEBUG', $request['APP_DEBUG']);
        $this->setEnvironmentValue('MAIL_HOST', $request['MAIL_HOST']);
        $this->setEnvironmentValue('MAIL_PORT', $request['MAIL_PORT']);
        $this->setEnvironmentValue('MAIL_ENCRYPTION', $request['MAIL_ENCRYPTION']);
        $this->setEnvironmentValue('MAIL_USERNAME', $request['MAIL_USERNAME']);
        $this->setEnvironmentValue('MAIL_PASSWORD', $request['MAIL_PASSWORD']);
        $this->setEnvironmentValue('MAIL_FROM_ADDRESS', $request['MAIL_FROM_ADDRESS']);

        return redirect()->route('admin.settings.smtp')->with('success', 'Settings updated successfully');
    }


//    public function setEnvironmentValue($envKey, $envValue)
//    {
//        $envFile = app()->environmentFilePath();
//        $str = file_get_contents($envFile);
//
//        $oldValue = env($envKey);
//
//        $str = str_replace("{$envKey}={$oldValue}", "{$envKey}={$envValue}", $str);
//
//        $fp = fopen($envFile, 'w');
//        fwrite($fp, $str);
//        fclose($fp);
//    }

    public function setEnvironmentValue($key, $value)
    {
        $path = app()->environmentFilePath();

        $escaped = preg_quote('='.env($key), '/');

        file_put_contents($path, preg_replace(
            "/^{$key}{$escaped}/m",
            "{$key}={$value}",
            file_get_contents($path)
        ));
    }
}
