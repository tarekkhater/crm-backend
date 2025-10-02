<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ForgetPassword extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('forget_passwords', function (Blueprint $table) {
            $table->id();
            $table->string('email');
            $table->enum('status',[1,0])->default(1);
            $table->bigInteger('code')->nullable();
            $table->bigInteger('user_id')->uniq();
            $table->timestamps();
            $table->foreign('user_id')->references('id')->on('user_id')->onDelete('cascade');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('forget_passwords');
    }
}
