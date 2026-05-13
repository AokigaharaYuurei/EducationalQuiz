<?php

namespace Database\Seeders;

use App\Models\Question;
use App\Models\Subject;
use Illuminate\Database\Seeder;

class QuestionsSeeder extends Seeder
{
    public function run(): void
    {
        $info = Subject::where('name', 'Интерфейсы пользователя')->first();
        if (!$info) return;

        // Вопрос 1
        $q1 = Question::create([
            'subject_id' => $info->id,
            'question_text' => 'Вы разрабатываете информационный сайт для пожилой аудитории. Какой основной принцип вы выберете для типографики, учитывая социальный контекст?',
            'points' => 10,
        ]);
        $q1->answers()->createMany([
            ['answer_text' => 'Применить высококонтрастный текст (черный на белом) достаточного размера с простым шрифтом', 'is_correct' => true],
            ['answer_text' => 'Использовать трендовые готические шрифты минимального размера', 'is_correct' => false],
            ['answer_text' => 'Использовать анимацию текста для привлечения внимания', 'is_correct' => false],
            ['answer_text' => 'Сделать основной текст серого цвета для снижения утомляемости', 'is_correct' => false],
        ]);

        // Вопрос 2
        $q2 = Question::create([
            'subject_id' => $info->id,
            'question_text' => 'При анализе источников информации для решения проблемы несовместимости CSS-свойства в разных браузерах, вы в первую очередь обратитесь к:',
            'points' => 20,
        ]);
        $q2->answers()->createMany([
            ['answer_text' => 'Форумам 10-летней давности', 'is_correct' => false],
            ['answer_text' => 'Авторитетным ресурсам типа MDN Web Docs или Can I Use', 'is_correct' => true],
            ['answer_text' => 'Документации на личном блоге неизвестного разработчика', 'is_correct' => false],
            ['answer_text' => 'Интуиции и предположениям', 'is_correct' => false],
        ]);

        // Вопрос 3
        $q3 = Question::create([
            'subject_id' => $info->id,
            'question_text' => 'Какой алгоритм выполнения работ является наиболее эффективным при верстке адаптивного макета (Responsive Web Design)?',
            'points' => 50,
        ]);
        $q3->answers()->createMany([
            ['answer_text' => 'Сначала верстать десктопную версию, затем добавлять медиа-запросы', 'is_correct' => false],
            ['answer_text' => 'Начинать с мобильной верстки (Mobile First), затем расширять стили', 'is_correct' => true],
            ['answer_text' => 'Создавать отдельные HTML-файлы для каждого типа устройств', 'is_correct' => false],
            ['answer_text' => 'Использовать только абсолютные единицы измерения', 'is_correct' => false],
        ]);

        // Вопрос 4
        $q4 = Question::create([
            'subject_id' => $info->id,
            'question_text' => 'Вы получили макет с нестандартными скругленными элементами и сложными тенями. Какой метод работы будет предпочтительнее для реализации?',
            'points' => 100,
        ]);
        $q4->answers()->createMany([
            ['answer_text' => 'Сделать скриншот и вставить его на страницу как изображение', 'is_correct' => false],
            ['answer_text' => 'Использовать современные CSS-свойства border-radius, box-shadow', 'is_correct' => true],
            ['answer_text' => 'Попросить дизайнера упростить макет', 'is_correct' => false],
            ['answer_text' => 'Создать элементы в графическом редакторе и добавить, как фоновые изображения', 'is_correct' => false],
        ]);

        // Вопрос 5
        $q5 = Question::create([
            'subject_id' => $info->id,
            'question_text' => 'Какой элемент структуры плана для решения задачи "Создать навигационное меню" будет первым?',
            'points' => 200,
        ]);
        $q5->answers()->createMany([
            ['answer_text' => 'Непосредственное написание кода nav', 'is_correct' => false],
            ['answer_text' => 'Анализ контекста: для какого сайта, какая аудитория?', 'is_correct' => true],
            ['answer_text' => 'Оценка результатов тестирования меню', 'is_correct' => false],
            ['answer_text' => 'Поиск готового решения в интернете и его копирование', 'is_correct' => false],
        ]);

        // Вопрос 6
        $q6 = Question::create([
            'subject_id' => $info->id,
            'question_text' => 'Вы разрабатываете интерфейс для портала, который будут использовать с IE11 и современными браузерами. Нужна раскладка из двух колонок. Какой основной подход к выбору технологии наиболее уместен?',
            'points' => 500,
        ]);
        $q6->answers()->createMany([
            ['answer_text' => 'Использовать только Flexbox', 'is_correct' => false],
            ['answer_text' => 'Применить гибридный или фолбэк-подход', 'is_correct' => true],
            ['answer_text' => 'Использовать только CSS Grid', 'is_correct' => false],
            ['answer_text' => 'Написать два разных CSS-файла', 'is_correct' => false],
        ]);

        // Вопрос 7
        $q7 = Question::create([
            'subject_id' => $info->id,
            'question_text' => 'Какой HTML-элемент является семантически правильным и наиболее доступным для кнопки, выполняющей действие на странице (не ведущей на другую страницу)?',
            'points' => 1000,
        ]);
        $q7->answers()->createMany([
            ['answer_text' => 'div class="button"', 'is_correct' => false],
            ['answer_text' => 'button type="button"', 'is_correct' => true],
            ['answer_text' => 'a href="#" class="button"', 'is_correct' => false],
            ['answer_text' => 'span role="button"', 'is_correct' => false],
        ]);

        // Вопрос 8
        $q8 = Question::create([
            'subject_id' => $info->id,
            'question_text' => 'Что такое медиа-запрос (media query) в CSS?',
            'points' => 2000,
        ]);
        $q8->answers()->createMany([
            ['answer_text' => 'Язык программирования для стилей', 'is_correct' => false],
            ['answer_text' => 'Специальный синтаксис для применения стилей при определенных условиях', 'is_correct' => true],
            ['answer_text' => 'Способ подключения шрифтов', 'is_correct' => false],
            ['answer_text' => 'Метод анимации элементов', 'is_correct' => false],
        ]);

    }
}