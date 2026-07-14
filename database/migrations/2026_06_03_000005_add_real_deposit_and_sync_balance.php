<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class AddRealDepositAndSyncBalance extends Migration
{
    public function up(): void
    {
        if (!Schema::hasColumn('info_trade_users', 'real_deposit')) {
            Schema::table('info_trade_users', function (Blueprint $table) {
                $table->decimal('real_deposit', 15, 2)->default(0)->after('balance');
            });
        }

        // Prior logic stored real deposit in `balance`. Copy it, then balance = real + bonus + mup.
        if (Schema::hasColumn('info_trade_users', 'fake') && Schema::hasColumn('info_trade_users', 'mup')) {
            DB::statement('
                UPDATE info_trade_users
                SET mup = COALESCE(NULLIF(mup, 0), fake, 0)
                WHERE COALESCE(mup, 0) = 0 AND COALESCE(fake, 0) <> 0
            ');
        }

        DB::statement('
            UPDATE info_trade_users
            SET real_deposit = CAST(COALESCE(balance, 0) AS DECIMAL(15,2))
        ');

        if (Schema::hasColumn('info_trade_users', 'mup')) {
            DB::statement('
                UPDATE info_trade_users
                SET balance = CAST(
                    CAST(COALESCE(real_deposit, 0) AS DECIMAL(15,2))
                    + CAST(COALESCE(bonus, 0) AS DECIMAL(15,2))
                    + CAST(COALESCE(mup, 0) AS DECIMAL(15,2))
                AS CHAR)
            ');
        } elseif (Schema::hasColumn('info_trade_users', 'fake')) {
            DB::statement('
                UPDATE info_trade_users
                SET balance = CAST(
                    CAST(COALESCE(real_deposit, 0) AS DECIMAL(15,2))
                    + CAST(COALESCE(bonus, 0) AS DECIMAL(15,2))
                    + CAST(COALESCE(fake, 0) AS DECIMAL(15,2))
                AS CHAR)
            ');
        } else {
            DB::statement('
                UPDATE info_trade_users
                SET balance = CAST(
                    CAST(COALESCE(real_deposit, 0) AS DECIMAL(15,2))
                    + CAST(COALESCE(bonus, 0) AS DECIMAL(15,2))
                AS CHAR)
            ');
        }
    }

    public function down(): void
    {
        if (Schema::hasColumn('info_trade_users', 'real_deposit')) {
            Schema::table('info_trade_users', function (Blueprint $table) {
                $table->dropColumn('real_deposit');
            });
        }
    }
}
