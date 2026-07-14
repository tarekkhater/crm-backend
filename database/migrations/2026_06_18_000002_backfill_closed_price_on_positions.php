<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Fill closed_price for historical closed positions where it is NULL.
        // Reverse-engineer the closing price from net_profit:
        //   priceDiff = net_profit / (lot * amount)
        //   BUY  → closed_price = opening_price + priceDiff
        //   SELL → closed_price = opening_price - priceDiff
        DB::statement("
            UPDATE positions
            SET closed_price = CASE
                WHEN LOWER(direction) = 'buy'
                    THEN opening_price + (net_profit / NULLIF(lot * amount, 0))
                WHEN LOWER(direction) = 'sell'
                    THEN opening_price - (net_profit / NULLIF(lot * amount, 0))
                ELSE opening_price
            END
            WHERE close_at IS NOT NULL
              AND closed_price IS NULL
              AND lot > 0
              AND amount > 0
        ");
    }

    public function down(): void
    {
        // Cannot reliably reverse a data backfill; leave closed_price as-is.
    }
};
