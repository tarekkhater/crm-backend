<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Events\CurrencyRateUpdated;
use App\Services\CurrencyRateService;

class RunCurrencyBroadcast extends Command
{
    protected $signature = 'currencies:broadcast';
    protected $description = 'Broadcast currency rates in real-time without database pressure';

    private $currencyService;
    private $isRunning = true;

    public function __construct(CurrencyRateService $currencyService)
    {
        parent::__construct();
        $this->currencyService = $currencyService;
    }

    public function handle()
    {
        $delay = 0.01;
        
        $this->info("Starting real-time currency broadcast with {$delay}s delay...");
        $this->info("Loaded  currencies from database (cached)");
        
    
        try {
            $chunkSize = 41;
            $iteration = 0;
            
            while ($this->isRunning) {
                if (PHP_OS_FAMILY === 'Windows' && $this->isWindowsInterrupted()) {
                    $this->shutdown();
                    break;
                }
                
                $startTime = microtime(true);
                
                $broadcastData = $this->currencyService->getAllCurrencyRates();
                
                if (!empty($broadcastData)) {
                    $chunks = array_chunk($broadcastData, $chunkSize);
                    
                    foreach ($chunks as $chunkIndex => $chunk) {
                        broadcast(new CurrencyRateUpdated($chunk));
                    }
                    
                    $executionTime = round((microtime(true) - $startTime) * 1000, 2);
                           $this->info("Broadcasted {$iteration}: " . count($broadcastData) . " rates in " . count($chunks) . " chunks using original Controller logic (took {$executionTime}ms)");
                }
                
                $iteration++;
                
                if ($delay > 0) {
                    usleep($delay * 1000000); 
                }
                
                if ($executionTime > 1000) { 
                    $this->warn("Performance warning: Execution took {$executionTime}ms - consider reducing API calls");
                }
                
                if (function_exists('pcntl_signal_dispatch')) {
                    pcntl_signal_dispatch();
                }
                
                if ($iteration % 20 === 0) {
                    gc_collect_cycles();
                }
            }
            
        } catch (\Exception $e) {
            $this->error("Error in broadcast loop: " . $e->getMessage());
            return 1;
        }
        
        $this->info("Broadcast stopped gracefully");
        return 0;
    }
    
    public function shutdown()
    {
        $this->isRunning = false;
        $this->info("\nShutdown signal received...");
    }
   
    private function isWindowsInterrupted()
    {
        if (PHP_OS_FAMILY !== 'Windows') {
            return false;
        }
        
        $read = [STDIN];
        $write = null;
        $except = null;
        
        if (stream_select($read, $write, $except, 0, 0) > 0) {
            $input = fgets(STDIN);
            return $input === false || trim($input) === '';
        }
        
        return false;
    }
}
