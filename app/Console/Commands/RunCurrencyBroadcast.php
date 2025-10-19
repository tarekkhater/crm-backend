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
    private $lastBroadcastData = [];  // نخزن آخر قيم تم بثها
    private $lastStocksBroadcast = 0; // آخر مرة بعتنا stocks
    private $lastFullSnapshot = 0;    // آخر مرة بعتنا snapshot كامل

    public function __construct(CurrencyRateService $currencyService)
    {
        parent::__construct();
        $this->currencyService = $currencyService;
    }

    // public function handle()
    // {
    //     $delay = 0.01;
        
    //     $this->info("Starting real-time currency broadcast with {$delay}s delay...");
    //     $this->info("Loaded  currencies from database (cached)");
        
    
    //     try {
    //         $chunkSize = 41;
    //         $iteration = 0;
            
    //         while ($this->isRunning) {
    //             if (PHP_OS_FAMILY === 'Windows' && $this->isWindowsInterrupted()) {
    //                 $this->shutdown();
    //                 break;
    //             }
                
    //             $startTime = microtime(true);
                
    //             $broadcastData = $this->currencyService->getAllCurrencyRates();
                
    //             if (!empty($broadcastData)) {
    //                 $chunks = array_chunk($broadcastData, $chunkSize);
                    
    //                 foreach ($chunks as $chunkIndex => $chunk) {
    //                     broadcast(new CurrencyRateUpdated($chunk));
    //                 }
                    
    //                 $executionTime = round((microtime(true) - $startTime) * 1000, 2);
    //                       $this->info("Broadcasted {$iteration}: " . count($broadcastData) . " rates in " . count($chunks) . " chunks using original Controller logic (took {$executionTime}ms)");
    //             }
                
    //             $iteration++;
                
    //             if ($delay > 0) {
    //                 usleep($delay * 1000000); 
    //             }
                
    //             if ($executionTime > 1000) { 
    //                 $this->warn("Performance warning: Execution took {$executionTime}ms - consider reducing API calls");
    //             }
                
    //             if (function_exists('pcntl_signal_dispatch')) {
    //                 pcntl_signal_dispatch();
    //             }
                
    //             if ($iteration % 20 === 0) {
    //                 gc_collect_cycles();
    //             }
    //         }
            
    //     } catch (\Exception $e) {
    //         $this->error("Error in broadcast loop: " . $e->getMessage());
    //         return 1;
    //     }
        
    //     $this->info("Broadcast stopped gracefully");
    //     return 0;
    // }
    
    public function handle()
    {
        $delay = 0.8; // تأخير بين كل check
        $stocksInterval = 15; // نبعت stocks كل 15 ثانية
        $snapshotInterval = 60; // نبعت snapshot كامل كل 60 ثانية

        $this->info("Starting intelligent currency broadcast...");
        $this->info("→ Crypto/Forex/Indices/Commodities: every {$delay}s (only changed)");
        $this->info("→ Stocks: every {$stocksInterval}s (only changed)");
        $this->info("→ Full snapshot: every {$snapshotInterval}s (for new clients)");

        try {
            $chunkSize = 50;
            $iteration = 0;

            while ($this->isRunning) {
                if (PHP_OS_FAMILY === 'Windows' && $this->isWindowsInterrupted()) {
                    $this->shutdown();
                    break;
                }

                $startTime = microtime(true);
                $currentTime = time();

                // نجيب كل الداتا
                $allData = $this->currencyService->getAllCurrencyRates();
                
                // نشوف لو حان وقت full snapshot (كل دقيقة)
                $isSnapshotTime = ($currentTime - $this->lastFullSnapshot >= $snapshotInterval);
                
                if ($isSnapshotTime) {
                    // نبعت كل حاجة عشان الـ clients الجدد
                    $chunks = array_chunk($allData, $chunkSize);
                    
                    foreach ($chunks as $chunk) {
                        broadcast(new CurrencyRateUpdated($chunk));
                    }
                    
                    // نحدث الـ cache
                    foreach ($allData as $asset) {
                        $type = $asset['type'] ?? 'other';
                        $key = "{$type}_{$asset['id']}";
                        $this->lastBroadcastData[$key] = $asset;
                    }
                    
                    $this->lastFullSnapshot = $currentTime;
                    $executionTime = round((microtime(true) - $startTime) * 1000, 2);
                    $this->warn("[{$iteration}] 📸 FULL SNAPSHOT: " . count($allData) . " assets (for new clients) (took {$executionTime}ms)");
                    
                } else {
                    // البث العادي: بس اللي اتغير
                    $stocks = [];
                    $otherAssets = [];
                    
                    foreach ($allData as $asset) {
                        if (isset($asset['type']) && $asset['type'] === 'stocks') {
                            $stocks[] = $asset;
                        } else {
                            $otherAssets[] = $asset;
                        }
                    }

                    $changedAssets = [];
                    
                    // نشوف الـ assets اللي اتغيرت (غير stocks)
                    $changedOthers = $this->getChangedAssets($otherAssets, 'other');
                    $changedAssets = array_merge($changedAssets, $changedOthers);
                    
                    // نشوف لو وقت نبعت stocks
                    if ($currentTime - $this->lastStocksBroadcast >= $stocksInterval) {
                        $changedStocks = $this->getChangedAssets($stocks, 'stocks');
                        $changedAssets = array_merge($changedAssets, $changedStocks);
                        $this->lastStocksBroadcast = $currentTime;
                    }

                    // نبعت بس اللي اتغير
                    if (!empty($changedAssets)) {
                        $chunks = array_chunk($changedAssets, $chunkSize);

                        foreach ($chunks as $chunk) {
                            broadcast(new CurrencyRateUpdated($chunk));
                        }

                        $executionTime = round((microtime(true) - $startTime) * 1000, 2);
                        
                        $stocksCount = count(array_filter($changedAssets, function($a) {
                            return isset($a['type']) && $a['type'] === 'stocks';
                        }));
                        $othersCount = count($changedAssets) - $stocksCount;
                        
                        $this->info("[{$iteration}] ✓ Broadcasted: {$othersCount} assets + {$stocksCount} stocks (took {$executionTime}ms)");
                    } else {
                        $this->line("[{$iteration}] ○ No changes detected");
                    }
                }

                $iteration++;

                if ($delay > 0) {
                    sleep($delay);
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
    
    /**
     * نجيب الـ assets اللي اتغيرت فقط
     */
    private function getChangedAssets($assets, $type)
    {
        $changedAssets = [];
        
        foreach ($assets as $asset) {
            $assetId = $asset['id'];
            $key = "{$type}_{$assetId}";
            
            // لو أول مرة نشوف الـ asset ده
            if (!isset($this->lastBroadcastData[$key])) {
                $changedAssets[] = $asset;
                $this->lastBroadcastData[$key] = $asset;
                continue;
            }
            
            $oldAsset = $this->lastBroadcastData[$key];
            
            // نقارن الأسعار
            if ($asset['current_price'] != $oldAsset['current_price'] ||
                $asset['buy_p'] != $oldAsset['buy_p'] ||
                $asset['sell_p'] != $oldAsset['sell_p']) {
                $changedAssets[] = $asset;
                $this->lastBroadcastData[$key] = $asset;
            }
        }
        
        return $changedAssets;
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
