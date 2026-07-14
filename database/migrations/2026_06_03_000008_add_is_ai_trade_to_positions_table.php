<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('positions')) {
            return;
        }

        Schema::table('positions', function (Blueprint $table) {
            if (!Schema::hasColumn('positions', 'is_ai_trade')) {
                $table->boolean('is_ai_trade')->default(false)->after('created_by');
            }
        });
    }

    public function down(): void
    {
        if (!Schema::hasTable('positions')) {
            return;
        }

        Schema::table('positions', function (Blueprint $table) {
            if (Schema::hasColumn('positions', 'is_ai_trade')) {
                $table->dropColumn('is_ai_trade');
            }
        });
    }
};
