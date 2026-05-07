<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // Admin
        User::create([
            'name'     => 'Admin',
            'email'    => 'admin@erp.com',
            'password' => Hash::make('admin123'),
            'role'     => 'admin',
        ]);

        // Operator
        User::create([
            'name'     => 'Operator',
            'email'    => 'operator@erp.com',
            'password' => Hash::make('operator123'),
            'role'     => 'operator',
        ]);
    }
}
