<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInvProfitsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('inv_profits', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('user_plan_id')->unsigned();
            $table->bigInteger('user_id')->unsigned();
            $table->decimal('amount', 12, 2);
            $table->decimal('profit', 11, 2);
            $table->decimal('total_profit', 11, 2);
            $table->integer('period')->comment('plan period in days');
            $table->integer('status')->default(0)->comment("2,1,0");
            $table->string('trx')->nullable();
            $table->date('start_date');
            $table->date('end_date')->nullable();
            $table->date('updated')->default(now());
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('user_plan_id')->references('id')->on('user_plans')->onDelete('cascade');
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
        Schema::dropIfExists('inv_profits');
    }
}
