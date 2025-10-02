<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateDepositsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('deposits', function(Blueprint $table)
        {
            $table->increments('id');
            $table->bigInteger('user_id')->unsigned()->nullable()->index();
            $table->bigInteger('plan_id')->nullable();
            $table->bigInteger('amount')->nullable();
            $table->string('promo_code')->nullable();
            $table->string('proof')->nullable();
            $table->text('message')->nullable();
//            $table->integer('account_id')->unsigned()->nullable()->index();
            $table->boolean('status')->default(false);
            $table->string('payment_method')->default('btc');
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
        Schema::drop('deposits');
    }
}
