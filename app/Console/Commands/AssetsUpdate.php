<?php

namespace App\Console\Commands;

use App\Http\Controllers\Controller;
use App\Models\CurrencyPair;
use Illuminate\Console\Command;

class AssetsUpdate extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'update_assets:cron';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update Assets CRON';

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
        $controller = new Controller();
        
              $curs = CurrencyPair::all();
                    foreach ($curs as $cur){
                        $rate = $controller->getCurRate($cur->sym, $cur->base, $cur->type);
                        if($rate > 0 && $cur->rate != $rate){
                            $cur->rate = $rate;
                            $cur->save();
                        }
                    }
           
       
        \Log::info("Update Asset Cron is working fine!");
    }
}
