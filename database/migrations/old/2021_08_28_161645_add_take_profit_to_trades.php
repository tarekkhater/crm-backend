<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddTakeProfitToTrades extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('trades', function (Blueprint $table) {
            $table->decimal('take_profit')->default(100);
            $table->decimal('stop_loss')->default(100);
            $table->boolean('is_stop_loss')->default(0);
            $table->boolean('is_take_profit')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('trades', function (Blueprint $table) {
            $table->dropColumn([
                'take_profit',
                'stop_loss',
                'is_stop_loss',
                'is_take_profit',
            ]);
        });
    }
}
