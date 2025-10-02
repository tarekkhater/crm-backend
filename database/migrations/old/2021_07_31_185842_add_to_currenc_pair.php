<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddToCurrencPair extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('currency_pairs', function (Blueprint $table) {
            $table->string('sym')->nullable()->after('name');
            $table->string('ex_sym')->nullable()->after('name');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('currency_pairs', function (Blueprint $table) {
            $table->dropColumn('sym','ex_sym');
        });
    }
}
