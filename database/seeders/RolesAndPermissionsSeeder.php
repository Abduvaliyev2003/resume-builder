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

        collect([
            'manage users',
            'manage resumes',
            'manage templates',
            'manage ai',
            'manage files',
            'access admin panel',
        ])->each(fn (string $permission) => Permission::firstOrCreate([
            'name' => $permission,
        ]));

        $superAdmin = Role::firstOrCreate([
            'name' => 'Super Admin',
        ]);

        $admin = Role::firstOrCreate([
            'name' => 'Admin',
        ]);

        $superAdmin->syncPermissions(Permission::all());
        $admin->syncPermissions(Permission::whereIn('name', [
            'manage users',
            'manage resumes',
            'manage templates',
            'manage ai',
            'manage files',
            'access admin panel',
        ])->get());

        $user = User::first();

        if ($user && ! $user->hasAnyRole(['Super Admin', 'Admin'])) {
            $user->assignRole($superAdmin);
        }
    }
}
