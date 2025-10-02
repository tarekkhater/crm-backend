<?php

namespace Database\Seeders;

use App\Models\Role;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{

    public function run()
    {
        $roles = [
           ['name' => 'manager', 'display_name' =>  'Manager'],
           ['name' => 'autotrader', 'display_name' =>  'Auto trader'],
           ['name' => 'dev', 'display_name' =>  'Developer '],
        ];

        foreach ($roles as $role){
            $oldrole = Role::whereName($role['name'])->first();
            if(!$oldrole){
                Role::create([
                    'name' => $role['name'],
                    'display_name' => $role['display_name'],
                    'description' => $role['display_name'],
                ]);
            }
        }
    }
}
