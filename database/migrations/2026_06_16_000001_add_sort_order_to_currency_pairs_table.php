<?php

use App\Models\CurrencyPair;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('currency_pairs', function (Blueprint $table) {
            $table->unsignedInteger('sort_order')->default(0)->after('type');
        });

        $types = CurrencyPair::query()
            ->select('type')
            ->whereNotNull('type')
            ->distinct()
            ->pluck('type');

        foreach ($types as $type) {
            CurrencyPair::query()
                ->where('type', $type)
                ->orderBy('id')
                ->get()
                ->each(function (CurrencyPair $asset, int $index) {
                    $asset->update(['sort_order' => $index + 1]);
                });
        }
    }

    public function down(): void
    {
        Schema::table('currency_pairs', function (Blueprint $table) {
            $table->dropColumn('sort_order');
        });
    }
};
