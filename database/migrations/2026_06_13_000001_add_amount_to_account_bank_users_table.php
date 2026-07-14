<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddAmountToAccountBankUsersTable extends Migration
{
    public function up()
    {
        Schema::table('account_bank_users', function (Blueprint $table) {
            $table->decimal('amount', 15, 2)->nullable()->after('user_id');
        });
    }

    public function down()
    {
        Schema::table('account_bank_users', function (Blueprint $table) {
            $table->dropColumn('amount');
        });
    }
}
