<?php
// app/Console/Commands/CleanExpiredOtps.php

namespace App\Console\Commands;

use App\Models\ActiveUser;
use App\Models\ForgetPassword;
use App\Models\Otp;
use Carbon\Carbon;
use Illuminate\Console\Command;

class CleanExpiredOtps extends Command
{
    protected $signature = 'otp:clean-expired';
    protected $description = 'Clean expired OTPs from the database';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        $expiredOtpCount = ForgetPassword::where('code_request', 3)->where('code_timer', '<', Carbon::now()->subMinutes(1))->delete();
        $expiredOtp = ActiveUser::where('code_request', 3)->where('code_timer', '<', Carbon::now()->subMinutes(1))->delete();

        $this->info("Deleted $expiredOtpCount expired OTP(s).");
    }
}
