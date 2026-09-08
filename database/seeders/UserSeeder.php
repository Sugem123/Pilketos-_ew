<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $user = User::first();
        if ($user) {
            $user->update([
                'password' => Hash::make('admin123'),
                'role' => User::ROLE_ADMIN,
            ]);
        } else {
            User::create([
                'nama_lengkap' => 'Administrator',
                'email' => 'admin@gmail.com',
                'password' => Hash::make('admin123'),
                'role' => User::ROLE_ADMIN,
            ]);
        }

        // Akun Operator Panitia
        User::updateOrCreate(
            ['email' => 'panitia@gmail.com'],
            [
                'nama_lengkap' => 'panitia',
                'password' => Hash::make('panitia123'),
                'role' => User::ROLE_OPERATOR,
            ]
        );
    }
}

