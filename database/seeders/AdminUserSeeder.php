<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    public function run(): void
    {
        User::firstOrCreate(
            ['email' => 'admin@quiz.com'],
            [
                'name' => 'Администратор',
                'middlename' => 'Сергеевич',
                'lastname' => 'Иванов',
                'login' => 'admin',
                'tel' => '+7 (999) 123-45-67',
                'role' => 'admin',
                'password' => Hash::make('admin123'), // пароль: admin123
                'email_verified_at' => now(),
            ]
        );
    }
}