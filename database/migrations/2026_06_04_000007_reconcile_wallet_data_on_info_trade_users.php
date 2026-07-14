<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

/**
 * Reconcile wallet columns after wallet feature rollout.
 *
 * Fixes:
 * - Migration double-count (balance was already total, then bonus+mup added again)
 * - Legacy code that updated `balance` without real_deposit / mup / bonus
 *
 * Result: balance = real_deposit + bonus + mup (awaiting_deposit unchanged)
 */
class ReconcileWalletDataOnInfoTradeUsers extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('info_trade_users')) {
            return;
        }

        if (!Schema::hasColumn('info_trade_users', 'real_deposit')) {
            Schema::table('info_trade_users', function (Blueprint $table) {
                $table->decimal('real_deposit', 15, 2)->default(0)->after('balance');
            });
        }

        if (!Schema::hasColumn('info_trade_users', 'mup')) {
            Schema::table('info_trade_users', function (Blueprint $table) {
                $table->decimal('mup', 15, 2)->default(0)->after('bonus');
            });
        }

        if (Schema::hasColumn('info_trade_users', 'fake')) {
            DB::statement('
                UPDATE info_trade_users
                SET mup = CAST(COALESCE(NULLIF(mup, 0), fake, 0) AS DECIMAL(15,2))
                WHERE COALESCE(mup, 0) = 0 AND COALESCE(fake, 0) <> 0
            ');
        }

        // Target main balance: prefer `balance` when set (legacy trading column), else sum of components.
        DB::statement('
            UPDATE info_trade_users
            SET real_deposit = GREATEST(0,
                CASE
                    WHEN CAST(COALESCE(balance, 0) AS DECIMAL(15,2)) > 0.009
                        THEN CAST(COALESCE(balance, 0) AS DECIMAL(15,2))
                             - CAST(COALESCE(bonus, 0) AS DECIMAL(15,2))
                             - CAST(COALESCE(mup, 0) AS DECIMAL(15,2))
                    ELSE CAST(COALESCE(real_deposit, 0) AS DECIMAL(15,2))
                END
            )
        ');

        // If balance was empty but components exist, derive main from components then sync balance.
        DB::statement('
            UPDATE info_trade_users
            SET balance = CAST(
                CAST(COALESCE(real_deposit, 0) AS DECIMAL(15,2))
                + CAST(COALESCE(bonus, 0) AS DECIMAL(15,2))
                + CAST(COALESCE(mup, 0) AS DECIMAL(15,2))
            AS CHAR)
            WHERE CAST(COALESCE(balance, 0) AS DECIMAL(15,2)) <= 0.009
              AND (
                  CAST(COALESCE(real_deposit, 0) AS DECIMAL(15,2))
                  + CAST(COALESCE(bonus, 0) AS DECIMAL(15,2))
                  + CAST(COALESCE(mup, 0) AS DECIMAL(15,2))
              ) > 0.009
        ');

        // Final sync: balance must equal real_deposit + bonus + mup for all rows.
        DB::statement('
            UPDATE info_trade_users
            SET balance = CAST(
                CAST(COALESCE(real_deposit, 0) AS DECIMAL(15,2))
                + CAST(COALESCE(bonus, 0) AS DECIMAL(15,2))
                + CAST(COALESCE(mup, 0) AS DECIMAL(15,2))
            AS CHAR)
        ');
    }

    public function down(): void
    {
        // Data reconciliation is not reversed automatically.
    }
}
