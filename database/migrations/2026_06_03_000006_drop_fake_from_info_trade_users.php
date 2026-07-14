<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class DropFakeFromInfoTradeUsers extends Migration
{
    public function up(): void
    {
        if (Schema::hasColumn('info_trade_users', 'fake')) {
            Schema::table('info_trade_users', function (Blueprint $table) {
                $table->dropColumn('fake');
            });
        }
    }

    public function down(): void
    {
        if (!Schema::hasColumn('info_trade_users', 'fake')) {
            Schema::table('info_trade_users', function (Blueprint $table) {
                $table->decimal('fake', 15, 2)->default(0)->after('bonus');
            });
        }
    }
}
