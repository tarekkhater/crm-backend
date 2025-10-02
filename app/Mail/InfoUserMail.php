<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use App\Models\User;
class InfoUserMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public $data;

    public function __construct()
    {
        // $this->data = $data;
    }

    public function build()
    {
        $user = User::with(['userInfo.currency'])->where('email','amrg7088@gmail.com')->first();
        $result = [
            'id'=>$user->id,
            'name'=>$user->name,
            'surname'=>$user->surname,
             'country'=>$user->countries->name,
             'currency'=>$user->userInfo->currency?$user->userInfo->currency->name:"-",
             'phone'=>'(+'.$user->countries->phonecode.')'.$user->phone,
                
        ];
        return $this->from(env('MAIL_FROM_ADDRESS'), env('MAIL_FROM_NAME'))
                    ->subject('Report(BBS)')
                    ->view('emails.tableinfo',compact('result'));
    }

}
