<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAutoProfitLossesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('auto_profit_losses', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('user_id')->unsigned();
            $table->boolean('status')->default(0);
            $table->boolean('pnl')->default(1);
            $table->boolean('record')->default(0);
            $table->string('fixed')->default('fixed');
            $table->string('interval_type')->default('day');
            $table->integer('interval')->default(1);
            $table->decimal('amount',12,2)->default(0);
            $table->dateTime('last_updated')->default(now());
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('auto_profit_losses');
    }
}
