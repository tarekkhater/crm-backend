<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOvernightsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('overnights', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('trade_id')->index();
            $table->bigInteger('user_id')->index();
            $table->decimal('amount',12,2);
            $table->bigInteger('fee')->default(4);
            $table->decimal('com',12,3)->default(0);
            $table->dateTime('charged_at')->nullable();
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
        Schema::dropIfExists('overnights');
    }
}
