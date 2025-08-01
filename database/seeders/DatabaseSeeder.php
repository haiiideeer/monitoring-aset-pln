<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::create([
            'name' => 'Super Admin',
            'email' => 'admin@pln.com',
            'password' => Hash::make('@dminpln'), // Ganti dengan password aman nantinya
            'role' => 'superadmin',
        ]);
    } 
}
