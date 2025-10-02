<?php

namespace App\Console\Commands;

use App\Models\Trade;
use Illuminate\Console\Command;

class OvernightCommission extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'commission:cron';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Take overnight commission every night';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        if(setting('overnight_commission')){
            $all_active_trades = Trade::whereStatus(0)->get();
        }else{
            $all_active_trades = [];
        }

    }

}
