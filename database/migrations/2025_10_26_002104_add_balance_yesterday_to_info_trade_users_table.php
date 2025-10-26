<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddBalanceYesterdayToInfoTradeUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('info_trade_users', function (Blueprint $table) {
            $table->decimal('balance_yesterday', 15, 2)->default(0)->after('balance')->comment('رصيد امبارح - يتحدث يومياً');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('info_trade_users', function (Blueprint $table) {
            $table->dropColumn('balance_yesterday');
        });
    }
}
