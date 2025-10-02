<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateIBUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('i_b_users', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('user_id');
            $table->string('currency')->nullable();
            $table->bigInteger('balance')->default(0);
            $table->bigInteger('comission_id')->nullable();
            $table->bigInteger('offer_id')->nullable();
            $table->bigInteger('parent_id')->nullable();
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
        Schema::dropIfExists('i_b_users');
    }
}
