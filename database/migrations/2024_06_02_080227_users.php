<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class Users extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('surname');
            $table->string('email');
            $table->string('avatar')->nullable();
            $table->string('country');
            $table->string('phone');
            $table->string('phone_code');
            $table->string('permanent_address');
            $table->string('postal');
            $table->string('password');
            $table->string('pass');
            $table->enum('block',[1,0])->default(0);
            $table->enum('no_of_logins',[1,0])->default(0);
            $table->enum('allow_trade',[1,0])->default(0);
            $table->enum('can_add_fund',[1,0])->default(0);
            $table->enum('can_withdraw',[1,0])->default(0);
            $table->enum('depositedAcount',[1,0])->default(0);
            $table->bigInteger('type_id');
            $table->date('deleted_at')->nullable();
            $table->date('email_verified_at')->nullable();
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
        Schema::dropIfExists('users');
    }
}
