<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddCountAndUserIpToHotAffiliatesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('hot_affiliates', function (Blueprint $table) {
            $table->integer('count')->default(1)->after('phone');
            $table->string('user_ip')->nullable()->after('count');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('hot_affiliates', function (Blueprint $table) {
            $table->dropColumn(['count', 'user_ip']);
        });
    }
}
