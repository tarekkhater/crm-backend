<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddMoreFieldsToAssets extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('currency_pairs', function (Blueprint $table) {
            $table->boolean('disabled')->default(0);
            $table->decimal('buy_spread')->default(0);
            $table->decimal('sell_spread')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('currency_pairs', function (Blueprint $table) {
            $table->dropColumn(['disabled','buy_spread','sell_spread']);
        });
    }
}
