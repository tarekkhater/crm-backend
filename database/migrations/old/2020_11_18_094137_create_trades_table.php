<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTradesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('trades', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('deposit_id')->nullable();
            $table->bigInteger('user_id');
            $table->string('trade_type')->default('buy');
            $table->string('currency_pair');
            $table->integer('duration')->default(1);
            $table->date('close_at')->nullable();
            $table->integer('profit')->nullable();
            $table->boolean('autoclose')->default(0);
//            $table->boolean('is_win')->default(true);
            $table->decimal('traded_amount', 18, 8)->nullable();
            $table->decimal('opening_price', 18, 8)->nullable();
            $table->decimal('closing_price', 18, 8)->nullable();
            $table->tinyInteger('result')->default(0)->comment('win : 1 lose : 2 Draw : 3');
            $table->tinyInteger('status')->default(0)->comment('Running : 0 Complete : 1');
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
        Schema::dropIfExists('trades');
    }
}
