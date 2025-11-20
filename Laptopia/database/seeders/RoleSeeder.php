<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class RoleSeeder extends Seeder
{
    public function run(): void
    {
        $adminRole = Role::firstOrCreate(['name' => 'admin']);
        $workerRole = Role::firstOrCreate(['name' => 'worker']);
        $customerRole = Role::firstOrCreate(['name' => 'customer']);

        if (! User::where('email', 'admin@laptopia.test')->exists()) {
            $admin = User::create([
                'name' => 'Admin',
                'email' => 'admin@laptopia.test',
                'password' => Hash::make('admin123'),
            ]);

            $admin->assignRole($adminRole);
        }

        if (! User::where('email', 'worker@laptopia.test')->exists()) {
            $worker = User::create([
                'name' => 'Worker',
                'email' => 'worker@laptopia.test',
                'password' => Hash::make('worker123'),
            ]);

            $worker->assignRole($workerRole);
        }

        User::whereDoesntHave('roles')->each(function (User $user) use ($customerRole) {
            $user->assignRole($customerRole);
        });
    }
}
