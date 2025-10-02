<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;

class CurrencyRateUpdated implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets;

    public $currencies;

    public function __construct($currencies)
    {
        $this->currencies = $currencies;
    }

    public function broadcastOn()
    {
        return new Channel('currencies');
    }

    public function broadcastWith()
    {
        return [
            'data' => $this->currencies,
        ];
    }

    /**
     * Only broadcast if currencies array is not empty
     */
    public function broadcastWhen()
    {
        return !empty($this->currencies) && count($this->currencies) > 0;
    }

    public function broadcastAs()
    {
        return 'CurrencyRateUpdated';
    }
}