<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => 'haider101602@gmail.com'], // cek unik
            [
                'name' => 'Super Admin',
                'password' => Hash::make('12345678'),
                'role' => 'SuperAdmin',
            ]
        );
    }
}
