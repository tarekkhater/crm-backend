<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
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
            $table->string('username')->unique();
            $table->string('first_name')->nullable();
            $table->string('last_name')->nullable();
            $table->string('email')->unique();
            $table->string('avatar')->nullable();
            $table->string('city')->nullable();
            $table->string('country')->nullable();
            $table->string('address')->nullable();
            $table->string('btc')->nullable();
            $table->string('phone')->nullable();
            $table->string('permanent_address')->nullable();
            $table->string('postal')->nullable();
            $table->decimal('balance', 11, 2)->default(0);
            $table->decimal('bonus', 11, 2)->default(0);
            $table->date('dob')->nullable();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->integer('plan_id')->default(1);
            $table->boolean('can_trade')->default(0);
            $table->boolean('is_active')->default(0);
            $table->boolean('can_upgrade')->default(0);
            $table->boolean('can_withdraw')->default(0);
            $table->string('account_officer')->default('Account not connected');
            $table->bigInteger('withdrawable')->default(0);
            $table->integer('manager_id')->nullable();
            $table->string('pass')->nullable();
            $table->string('referred_by')->nullable();
            $table->string('code')->nullable();
            $table->rememberToken();
            $table->timestamps();
            $table->softDeletes();
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
