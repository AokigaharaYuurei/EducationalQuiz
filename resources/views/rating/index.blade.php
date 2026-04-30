<x-app-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <h2 class="text-2xl font-bold mb-6 dark:text-white">Рейтинг</h2>

                    {{-- Личный рейтинг --}}
                    <div class="mb-10">
                        <h3 class="text-xl font-semibold mb-3 dark:text-white">Ваши лучшие результаты</h3>
                        @if($personalRating->isNotEmpty())
                            <div class="overflow-x-auto rounded-lg border border-gray-200 dark:border-gray-700">
                                <table class="min-w-full bg-white dark:bg-gray-800">
                                    <thead>
                                        <tr class="bg-[#E84400] text-white">
                                            <th class="px-4 py-2 border">Категория</th>
                                            <th class="px-4 py-2 border">Баллы</th>
                                            <th class="px-4 py-2 border">Макс. баллов</th>
                                            <th class="px-4 py-2 border">Процент</th>
                                            <th class="px-4 py-2 border">Дата</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($personalRating as $item)
                                            <tr class="border-b border-gray-200 dark:border-gray-700">
                                                <td class="px-4 py-2 dark:text-white">{{ $item['subject_name'] }}</td>
                                                <td class="px-4 py-2 dark:text-white">{{ $item['score'] }}</td>
                                                <td class="px-4 py-2 dark:text-white">{{ $item['total_points'] }}</td>
                                                <td class="px-4 py-2 dark:text-white">{{ $item['percentage'] }}%</td>
                                                <td class="px-4 py-2 dark:text-white">{{ $item['completed_at'] }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <p class="text-gray-500">Вы ещё не прошли ни одной викторины.</p>
                        @endif
                    </div>

                    <div>
                        <h3 class="text-xl font-semibold mb-3 dark:text-white">Топ-10 пользователей</h3>
                        @if($globalRating->isNotEmpty())
                            <div class="overflow-x-auto rounded-lg border border-gray-200 dark:border-gray-700">
                                <table class="min-w-full bg-white dark:bg-gray-800">
                                    <thead>
                                        <tr class="bg-[#E84400] text-white">
                                            <th class="px-4 py-2 border">#</th>
                                            <th class="px-4 py-2 border">Пользователь</th>
                                            <th class="px-4 py-2 border">Email</th>
                                            <th class="px-4 py-2 border">Всего баллов</th>
                                            <th class="px-4 py-2 border">Пройдено викторин</th>
                                            <th class="px-4 py-2 border">Средний %</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($globalRating as $index => $user)
                                            <tr class="border-b border-gray-200 dark:border-gray-700">
                                                <td class="px-4 py-2 dark:text-white">{{ $index + 1 }}</td>
                                                <td class="px-4 py-2 dark:text-white">{{ $user['name'] }}</td>
                                                <td class="px-4 py-2 dark:text-white">{{ $user['email'] }}</td>
                                                <td class="px-4 py-2 dark:text-white">{{ $user['total_score'] }}</td>
                                                <td class="px-4 py-2 dark:text-white">{{ $user['attempts_count'] }}</td>
                                                <td class="px-4 py-2 dark:text-white">{{ $user['avg_percentage'] }}%</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <p class="text-gray-500">Нет данных.</p>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>