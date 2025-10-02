<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;
use App\Mail\AdminReceivesWithdrawalMail;
use App\Models\User;

class SendDailyReport extends Command
{
    protected $signature = 'report:send';

    protected $description = 'Send daily report to all users.';

    public function handle()
    {
        $users = User::where('email','amrgamal9949@yahoo.com')->first();

        foreach ($users as $user) {
            Mail::to("amrgamal9949@yahoo.com")->send(new AdminReceivesWithdrawalMail($users,"200",'طلب للسحب من الرصيد','تم تقديم طلب للسحب من الرصيد عن طريق حساب بنكي'));
            $this->info('Report sent to: ' . "amrgamal9949@yahoo.com");
        }
    }
}
