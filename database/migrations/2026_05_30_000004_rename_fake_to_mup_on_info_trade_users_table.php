<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        if (!Schema::hasColumn('info_trade_users', 'mup')) {
            Schema::table('info_trade_users', function (Blueprint $table) {
                $table->decimal('mup', 15, 2)->default(0)->after('bonus');
            });
        }

        if (Schema::hasColumn('info_trade_users', 'fake')) {
            DB::statement('UPDATE info_trade_users SET mup = COALESCE(fake, 0)');
        }
    }

    public function down(): void
    {
        if (Schema::hasColumn('info_trade_users', 'mup')) {
            Schema::table('info_trade_users', function (Blueprint $table) {
                $table->dropColumn('mup');
            });
        }
    }
};
