<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Permission;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            RolesAndAdminSeeder::class,
        ]);
        // User::factory(10)->create();

        // $adminRole = Role::firstOrCreate(['name' => 'admin']);
        // $userRole  = Role::firstOrCreate(['name' => 'user']);
        // $viewerRole = Role::firstOrCreate(['name' => 'viewer']);

        // if (!User::where('email', 'admin@gmail.com')->exists()) {
        //     $admin = User::create([
        //         'name' => 'Admin User',
        //         'email' => 'admin@gmail.com',
        //         'password' => Hash::make('Password123'),
        //     ]);
        //     $admin->assignRole($adminRole);
        // }

        // if (!User::where('email', 'user@gmail.com')->exists()) {
        //     $user = User::create([
        //         'name' => 'Normal User',
        //         'email' => 'user@gmail.com',
        //         'password' => Hash::make('Password123'),
        //     ]);
        //     $user->assignRole($userRole);
        // }

        // if (!User::where('email', 'viewer@gmail.com')->exists()) {
        //     $viewer = User::create([
        //         'name' => 'Viewer User',
        //         'email' => 'viewer@gmail.com',
        //         'password' => Hash::make('Password123'),
        //     ]);
        //     $viewer->assignRole($viewerRole);
        // }
    }
}
