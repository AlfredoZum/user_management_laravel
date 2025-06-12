<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Permission;

class RolesAndAdminSeeder extends Seeder
{

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Permission::create(['name' => 'view users']);
        Permission::create(['name' => 'create users']);
        Permission::create(['name' => 'edit users']);
        Permission::create(['name' => 'delete users']);

        Permission::firstOrCreate(['name' => 'view tasks']);
        Permission::firstOrCreate(['name' => 'view all tasks']);
        Permission::firstOrCreate(['name' => 'view own tasks']);
        Permission::create(['name' => 'create tasks']);
        Permission::create(['name' => 'update tasks']);
        Permission::create(['name' => 'delete tasks']);

        $adminRole = Role::firstOrCreate(['name' => 'admin']);
        $editorRole  = Role::firstOrCreate(['name' => 'editor']);
        $viewerRole = Role::firstOrCreate(['name' => 'viewer']);

        if (!User::where('email', 'admin@gmail.com')->exists()) {
            $admin = User::create([
                'name' => 'Admin User',
                'email' => 'admin@gmail.com',
                'password' => Hash::make('Password123'),
                'email_verified_at' => now()
            ]);

            $admin->assignRole($adminRole);
            $permissionsAdmin = Permission::query()->pluck('name');
            $adminRole->syncPermissions($permissionsAdmin);
        }

        if (!User::where('email', 'editor@gmail.com')->exists()) {
            $editor = User::create([
                'name' => 'Normal editor',
                'email' => 'editor@gmail.com',
                'password' => Hash::make('Password123'),
                'email_verified_at' => now()
            ]);
            $editor->assignRole($editorRole);
            $editorRole->syncPermissions([
                'view tasks',
                'view all tasks',
                'create tasks',
                'update tasks', 
            ]);
        }

        if (!User::where('email', 'viewer@gmail.com')->exists()) {
            $viewer = User::create([
                'name' => 'Viewer User',
                'email' => 'viewer@gmail.com',
                'password' => Hash::make('Password123'),
                'email_verified_at' => now()
            ]);
            $viewer->assignRole($viewerRole);
            $viewerRole->syncPermissions([
                'view tasks',
                'view own tasks',
            ]);
        }
    }
}
