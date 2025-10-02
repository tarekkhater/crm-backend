<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddComToCurrencyPairs extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('currency_pairs', function (Blueprint $table) {
            $table->decimal('com')->default(4)->after('ex_sym');
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
            $table->dropColumn('com');
        });
    }
}
