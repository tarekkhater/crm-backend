<?php

use Illuminate\Database\Migrations\Migration;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\PermissionRegistrar;

return new class extends Migration
{
    public function up(): void
    {
        Permission::query()
            ->where('guard_name', 'api')
            ->where('name', 'Show-Balance')
            ->update(['path' => 'profile/user']);

        app(PermissionRegistrar::class)->forgetCachedPermissions();
    }

    public function down(): void
    {
        Permission::query()
            ->where('guard_name', 'api')
            ->where('name', 'Show-Balance')
            ->update(['path' => null]);

        app(PermissionRegistrar::class)->forgetCachedPermissions();
    }
};
