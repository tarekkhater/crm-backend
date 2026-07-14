<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Events\CurrencyRateUpdated;
use App\Services\CurrencyRateService;
use App\Models\Position;
use App\Models\CurrencyPair;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class RunCurrencyBroadcast extends Command
{
    protected $signature = 'currencies:broadcast';
    protected $description = 'Broadcast currency rates in real-time without database pressure';

    private $currencyService;
    private $isRunning = true;
    private $lastBroadcastData = [];
    private $lastStocksBroadcast = 0;
    private $lastFullSnapshot = 0;
    private $lastRateDbSync = 0;

    /** Open positions with SL/TP cached in memory */
    private $cachedPositions = [];
    private $lastPositionsReload = 0;

    // Reload positions from DB every 5s, sync DB rates every 30s
    private const POSITIONS_RELOAD_INTERVAL = 2;
    private const RATE_DB_SYNC_INTERVAL = 30;

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
        $delay = 0.8;
        $stocksInterval = 15;
        $snapshotInterval = 60;

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

                $allData = $this->currencyService->getAllCurrencyRates();

                // Build ex_sym => rate map from fresh API data
                $priceMap = [];
                foreach ($allData as $asset) {
                    if (isset($asset['name']) && ($asset['rate'] ?? 0) > 0) {
                        $priceMap[$asset['name']] = (float) $asset['rate'];
                    }
                }

                // Sync rates to DB every 30s so the cron fallback stays fresh
                if ($currentTime - $this->lastRateDbSync >= self::RATE_DB_SYNC_INTERVAL) {
                    $this->syncRatesToDb($priceMap);
                    $this->lastRateDbSync = $currentTime;
                }

                // Reload open positions from DB every 5s
                if ($currentTime - $this->lastPositionsReload >= self::POSITIONS_RELOAD_INTERVAL) {
                    $this->reloadOpenPositions();
                    $this->lastPositionsReload = $currentTime;
                }

                // Check SL/TP on EVERY iteration (~0.8s) using in-memory positions
                if (!empty($this->cachedPositions) && !empty($priceMap)) {
                    $closed = $this->checkCachedPositionsSLTP($priceMap);
                    if ($closed > 0) {
                        $this->warn("[SL/TP] Auto-closed {$closed} position(s)");
                        // Force reload next iteration to pick up any new open positions
                        $this->lastPositionsReload = 0;
                    }
                }

                $isSnapshotTime = ($currentTime - $this->lastFullSnapshot >= $snapshotInterval);
                
                if ($isSnapshotTime) {
                    $chunks = array_chunk($allData, $chunkSize);
                    
                    foreach ($chunks as $chunk) {
                        broadcast(new CurrencyRateUpdated($chunk));
                    }
                    
                    foreach ($allData as $asset) {
                        $type = $asset['type'] ?? 'other';
                        $key = "{$type}_{$asset['id']}";
                        $this->lastBroadcastData[$key] = $asset;
                    }
                    
                    $this->lastFullSnapshot = $currentTime;
                    $executionTime = round((microtime(true) - $startTime) * 1000, 2);
                    $this->warn("[{$iteration}] 📸 FULL SNAPSHOT: " . count($allData) . " assets (for new clients) (took {$executionTime}ms)");
                    
                } else {
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
                    
                    $changedOthers = $this->getChangedAssets($otherAssets);
                    $changedAssets = array_merge($changedAssets, $changedOthers);
                    
                    if ($currentTime - $this->lastStocksBroadcast >= $stocksInterval) {
                        $changedStocks = $this->getChangedAssets($stocks);
                        $changedAssets = array_merge($changedAssets, $changedStocks);
                        $this->lastStocksBroadcast = $currentTime;
                    }

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
     * Load (or refresh) open positions with SL/TP into memory.
     * Only hits the DB every POSITIONS_RELOAD_INTERVAL seconds.
     */
    private function reloadOpenPositions(): void
    {
        $this->cachedPositions = Position::query()
            ->whereNull('close_at')
            ->where(function ($q) {
                $q->where('stop_loss', '>', 0)
                  ->orWhere('take_profit', '>', 0);
            })
            ->with(['user.userInfo'])
            ->get()
            ->keyBy('id')
            ->all();
    }

    /**
     * Check every cached open position against the fresh price map.
     * Runs on EVERY broadcast iteration (~0.8s) — no DB read.
     */
    private function checkCachedPositionsSLTP(array $priceMap): int
    {
        $closed = 0;

        foreach ($this->cachedPositions as $id => $position) {
            // Skip already-closed (shouldn't happen, but guard anyway)
            if ($position->close_at !== null) {
                unset($this->cachedPositions[$id]);
                continue;
            }

            $freshPrice = $priceMap[$position->symbol] ?? null;
            if (!$freshPrice || $freshPrice <= 0) continue;

            $profit = $position->calculateFloatingProfit($freshPrice);

            if ($position->checkAndCloseByPnL($profit, $freshPrice)) {
                unset($this->cachedPositions[$id]);
                $closed++;
                $exitPrice = $position->quotedExitPrice($freshPrice);
                Log::info("SL/TP auto-close: position #{$id} | symbol={$position->symbol} | mid={$freshPrice} | exit={$exitPrice} | profit={$profit}");
            }
        }

        return $closed;
    }

    /**
     * Sync live prices to currency_pairs.rate in DB so the cron fallback
     * always has fresh data (runs every RATE_DB_SYNC_INTERVAL seconds).
     */
    private function syncRatesToDb(array $priceMap): void
    {
        if (empty($priceMap)) return;

        $chunks = array_chunk($priceMap, 100, true);
        foreach ($chunks as $chunk) {
            $symbols = array_keys($chunk);
            $cases = 'CASE ex_sym';
            $bindings = [];
            foreach ($chunk as $exSym => $rate) {
                $cases .= ' WHEN ? THEN ?';
                $bindings[] = $exSym;
                $bindings[] = $rate;
            }
            $cases .= ' END';
            $placeholders = implode(',', array_fill(0, count($symbols), '?'));
            $bindings = array_merge($bindings, $symbols);

            DB::statement(
                "UPDATE currency_pairs SET rate = {$cases} WHERE ex_sym IN ({$placeholders})",
                $bindings
            );
        }
    }

    /**
     * نجيب الـ assets اللي اتغيرت فقط
     */
    private function getChangedAssets(array $assets): array
    {
        $changedAssets = [];
        
        foreach ($assets as $asset) {
            $assetId = $asset['id'];
            $type = $asset['type'] ?? 'other';
            $key = "{$type}_{$assetId}";
            
            if (!isset($this->lastBroadcastData[$key])) {
                $changedAssets[] = $asset;
                $this->lastBroadcastData[$key] = $asset;
                continue;
            }
            
            $oldAsset = $this->lastBroadcastData[$key];
            
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
