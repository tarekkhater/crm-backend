<?php

use Illuminate\Database\Migrations\Migration;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\PermissionRegistrar;

return new class extends Migration
{
    public function up(): void
    {
        $balancePermissions = [
            [
                'name' => 'Show-Balance',
                'display_name' => 'Show Balance',
                'description' => 'View customer account balance',
            ],
            [
                'name' => 'Add-Balance',
                'display_name' => 'Add Balance',
                'description' => 'Add funds to customer balance',
            ],
            [
                'name' => 'Edit-Balance',
                'display_name' => 'Edit Balance',
                'description' => 'Modify customer account balance',
            ],
            [
                'name' => 'show-balance',
                'display_name' => 'Show Balance',
                'description' => 'View customer account balance (legacy)',
            ],
            [
                'name' => 'add-balance',
                'display_name' => 'Add Balance',
                'description' => 'Add funds to customer balance (legacy)',
            ],
            [
                'name' => 'edit-balance',
                'display_name' => 'Edit Balance',
                'description' => 'Modify customer account balance (legacy)',
            ],
        ];

        foreach ($balancePermissions as $permission) {
            Permission::query()->updateOrCreate(
                ['name' => $permission['name'], 'guard_name' => 'api'],
                [
                    'display_name' => $permission['display_name'],
                    'description' => $permission['description'],
                    'title' => 'Balance',
                ]
            );
        }

        app(PermissionRegistrar::class)->forgetCachedPermissions();
    }

    public function down(): void
    {
        Permission::query()
            ->where('guard_name', 'api')
            ->whereIn('name', [
                'Show-Balance',
                'Add-Balance',
                'Edit-Balance',
                'show-balance',
                'add-balance',
                'edit-balance',
            ])
            ->delete();

        app(PermissionRegistrar::class)->forgetCachedPermissions();
    }
};
