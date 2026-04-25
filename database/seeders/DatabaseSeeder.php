<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Otomatis membuat akun Admin PosyanduCare
        User::create([
            'name'     => 'Super Admin',
            'nik'      => '3326031212020001',
            'email'    => 'admin@posyanducare.com', // Opsional, jaga-jaga jika butuh email
            'password' => Hash::make('password123'), // Silakan ganti passwordnya
            'role'     => 'admin',
            'status'   => 'active',
        ]);
    }
}