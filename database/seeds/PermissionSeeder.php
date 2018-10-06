<?php

use Illuminate\Database\Seeder;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \App\Models\Role::create([
            'name' => 'superAdmin',
            'guard_name' => 'api'
        ]);

        \App\Models\Role::create([
            'name' => 'admin',
            'guard_name' => 'api'
        ]);

        \App\Models\Role::create([
            'name' => 'teacher',
            'guard_name' => 'api'
        ]);
    }
}
