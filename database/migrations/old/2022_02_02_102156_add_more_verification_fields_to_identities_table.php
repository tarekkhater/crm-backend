<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddMoreVerificationFieldsToIdentitiesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('identities', function (Blueprint $table) {
            $table->string('credit_card_front')->nullable()->after('type');
            $table->string('credit_card_back')->nullable()->after('type');
            $table->string('proof_of_address_type')->nullable()->after('type');
            $table->string('proof_of_address')->nullable()->after('type');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('identities', function (Blueprint $table) {
            $table->dropColumn('credit_card_front', 'credit_card_back', 'proof_of_address_type', 'proof_of_address');
        });
    }
}
