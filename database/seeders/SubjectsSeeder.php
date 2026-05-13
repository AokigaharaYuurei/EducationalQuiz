<?php

namespace Database\Seeders;

use App\Models\Subject;
use Illuminate\Database\Seeder;

class SubjectsSeeder extends Seeder
{
    public function run(): void
    {
        $subjects = [
            'Интерфейсы пользователя',
            'Графический дизайн',
            'Безопастность веб-приложений',
            'Русский язык',
            'Математика',
            'Основы программирования',
        ];

        foreach ($subjects as $name) {
            Subject::firstOrCreate(['name' => $name]);
        }
    }
}