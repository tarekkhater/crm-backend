<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddJointAccountToUsers extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('j_first_name')->nullable();
            $table->string('j_last_name')->nullable();
            $table->string('j_email')->nullable();
            $table->string('j_phone')->nullable();
            $table->string('j_country')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'j_first_name',
'j_last_name',
'j_email',
'j_phone',
'j_country',
            ]);
        });
    }
}
