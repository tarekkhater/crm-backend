<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateIBClientsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('i_b_clients', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('ib_id')->nullable();
            $table->bigInteger('user_id')->nullable();
            $table->string('currency')->nullable();
            $table->bigInteger('balance')->default(0);
            $table->bigInteger('offer_id')->nullable();
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
        Schema::dropIfExists('i_b_clients');
    }
}
