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
            'name'     => 'Sania',
            'email'    => 'sania@erp.com',
            'password' => Hash::make('sania123'),
            'role'     => 'admin',
        ]);

        User::create([
            'name'     => 'Dilla',
            'email'    => 'dilla@erp.com',
            'password' => Hash::make('dilla123'),
            'role'     => 'admin',
        ]);

        // Operator
        User::create([
            'name'     => 'Team A',
            'email'    => 'teama@erp.com',
            'password' => Hash::make('teama123'),
            'role'     => 'operator',
        ]);

        User::create([
            'name'     => 'Team B',
            'email'    => 'teamb@erp.com',
            'password' => Hash::make('teamb123'),
            'role'     => 'operator',
        ]);
    }
}
