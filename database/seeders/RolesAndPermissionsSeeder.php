<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Domains\Auth\Models\Role;
use App\Domains\Auth\Models\Permission;
use App\Domains\User\Models\User;

class RolesAndPermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        app()[\Spatie\Permission\PermissionRegistrar::class]
            ->forgetCachedPermissions();

        Permission::create(['name' => 'manage users']);
        Permission::create(['name' => 'manage resumes']);
        Permission::create(['name' => 'manage templates']);
        Permission::create(['name' => 'manage ai']);
        Permission::create(['name' => 'manage files']);

        $admin = Role::firstOrCreate([
            'name' => 'Super Admin'
        ]);

        $admin->givePermissionTo(Permission::all());

        $user = User::first();

        if ($user) {
            $user->assignRole($admin);
        }
    }
}
