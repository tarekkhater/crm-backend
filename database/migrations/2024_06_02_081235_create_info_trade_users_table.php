<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInfoTradeUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('info_trade_users', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('user_id');
            $table->bigInteger('plan_id')->nullable();
            $table->bigInteger('branch_id')->nullable();
            $table->bigInteger('status_id')->nullable();
            $table->bigInteger('source_id')->nullable();
            $table->string('balance')->default('0.00');
            $table->string('pnl')->default('0.00');
            $table->string('bonus')->default('0.00');
            $table->string('dob')->default('0.00');
            $table->string('profit')->default('0.00');
            $table->string('fee')->default('0.00');
            $table->string('withdrawable')->default('0.00');
            $table->string('cur')->nullable();
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
        Schema::dropIfExists('info_trade_users');
    }
}
