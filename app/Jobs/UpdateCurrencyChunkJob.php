<?php

namespace App\Jobs;

use App\Services\CurrencyRateService;
use App\Events\CurrencyRateUpdated;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class UpdateCurrencyChunkJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public array $currenciesChunk;

    /**
     * Create a new job instance.
     */
    public function __construct(array $currenciesChunk)
    {
        $this->currenciesChunk = $currenciesChunk;
    }

    /**
     * Execute the job.
     */
    public function handle(CurrencyRateService $currencyService)
    {
        $rates = $currencyService->getAllCurrencyRatesForChunk($this->currenciesChunk);

        if (!empty($rates)) {
            broadcast(new CurrencyRateUpdated($rates));
        }
    }
}
