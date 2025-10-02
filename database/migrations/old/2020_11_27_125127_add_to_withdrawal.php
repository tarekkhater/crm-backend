<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddToWithdrawal extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('withdrawals', function (Blueprint $table) {
            $table->boolean('processed')->default(false);
            $table->integer('commission')->nullable();
            $table->string('commission_proof')->nullable();
            $table->integer('tax')->nullable();
            $table->string('tax_proof')->nullable();
            $table->integer('cost_of_transfer')->nullable();
            $table->string('cot_proof')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('withdrawals', function (Blueprint $table) {
            $table->dropColumn(['processed','commission','tax','cost_of_transfer','commission_proof','tax_proof','cot_proof']);
        });
    }
}
