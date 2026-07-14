<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('desks')) {
            Schema::table('desks', function (Blueprint $table) {
                if (!Schema::hasColumn('desks', 'registration_token')) {
                    $table->string('registration_token', 64)->nullable()->unique()->after('code');
                }
            });

            $desks = DB::table('desks')->whereNull('registration_token')->get();
            foreach ($desks as $desk) {
                do {
                    $token = Str::random(40);
                } while (DB::table('desks')->where('registration_token', $token)->exists());

                DB::table('desks')->where('id', $desk->id)->update(['registration_token' => $token]);
            }
        }

        if (Schema::hasTable('users') && Schema::hasTable('desks')) {
            Schema::table('users', function (Blueprint $table) {
                if (!Schema::hasColumn('users', 'registration_desk_id')) {
                    $table->foreignId('registration_desk_id')->nullable()->constrained('desks')->nullOnDelete();
                }
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('users') && Schema::hasColumn('users', 'registration_desk_id')) {
            Schema::table('users', function (Blueprint $table) {
                $table->dropForeign(['registration_desk_id']);
            });
            Schema::table('users', function (Blueprint $table) {
                $table->dropColumn('registration_desk_id');
            });
        }

        if (Schema::hasTable('desks') && Schema::hasColumn('desks', 'registration_token')) {
            Schema::table('desks', function (Blueprint $table) {
                $table->dropColumn('registration_token');
            });
        }
    }
};
