<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

/**
 * Back-fill the balance column to include awaiting_deposit (credit).
 *
 * Before: balance = real_deposit + bonus + mup
 * After:  balance = real_deposit + bonus + mup + awaiting_deposit
 */
return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasColumn('info_trade_users', 'real_deposit')) {
            return;
        }

        DB::statement("
            UPDATE info_trade_users
            SET balance = ROUND(
                COALESCE(real_deposit, 0) +
                COALESCE(bonus, 0) +
                COALESCE(mup, 0) +
                COALESCE(awaiting_deposit, 0),
                2
            )
            WHERE real_deposit IS NOT NULL
        ");
    }

    public function down(): void
    {
        if (! Schema::hasColumn('info_trade_users', 'real_deposit')) {
            return;
        }

        // Roll back: remove credit from balance
        DB::statement("
            UPDATE info_trade_users
            SET balance = ROUND(
                COALESCE(real_deposit, 0) +
                COALESCE(bonus, 0) +
                COALESCE(mup, 0),
                2
            )
            WHERE real_deposit IS NOT NULL
        ");
    }
};
