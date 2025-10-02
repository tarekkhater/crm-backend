<?php

namespace App\Observers;

use App\Models\Trade;
use App\Events\TradeUpdated;

class TradeObserver
{
    public function created(Trade $trade)
    {
        broadcast(new TradeUpdated($trade->user_id, $trade->toArray()));
    }

    public function updated(Trade $trade)
    {
        broadcast(new TradeUpdated($trade->user_id, $trade->toArray()));
    }
}