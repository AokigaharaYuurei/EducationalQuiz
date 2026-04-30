<x-app-layout>
    <div class="py-12">
        <a href="{{ route('admin.index') }}" class="text-[#000] dark:text-[#fff] text-[25px] ml-[30px] hover:text-[#E84400] hover:underline transition">← Административная панель</a>
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <div class="flex justify-between items-center mb-6">
                        <h2 class="text-2xl font-bold dark:text-white">Статистика прохождения викторин</h2>
                        <a href="{{ route('admin.statistics.export', ['search' => request('search')]) }}" 
                           class="bg-[#F7733C] hover:bg-[#E84400] text-white px-4 py-2 rounded">
                            Экспорт в CSV
                        </a>
                    </div>

                    <div class="mb-8">
                        <h3 class="text-xl font-semibold mb-3 dark:text-white">Средний процент по категориям</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-x-8 gap-y-2">
                            @forelse($categoryStats as $stat)
                                <div class="flex justify-between border-b border-gray-200 dark:border-gray-700 py-2">
                                    <span class="dark:text-white">{{ $stat['category'] }}</span>
                                    <span class="font-bold text-[#E84400]">{{ $stat['avg_percentage'] }}%</span>
                                </div>
                            @empty
                                <div class="col-span-2 text-center py-4">Нет данных</div>
                            @endforelse
                        </div>
                    </div>

                    <div class="mb-6">
                        <p class="text-xl font-semibold dark:text-white">Всего пройдено викторин: <span class="text-[#E84400]">{{ $totalQuizzes }}</span></p>
                    </div>

                    <div class="mb-4">
                        <form method="GET" action="{{ route('admin.statistics') }}" class="flex gap-2 max-w-md">
                            <input type="text" name="search" placeholder="Поиск по имени или email пользователя" 
                                   value="{{ request('search') }}"
                                   class="flex-1 px-4 py-2 border border-gray-300 rounded dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                            <button type="submit" class="bg-[#E84400] hover:bg-[#df6937] text-white px-4 py-2 rounded">Поиск</button>
                            @if(request('search'))
                                <a href="{{ route('admin.statistics') }}" class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded">Сбросить</a>
                            @endif
                        </form>
                    </div>

                    <div class="overflow-x-auto rounded-lg border border-gray-200 dark:border-gray-700">
                        <table class="min-w-full bg-white dark:bg-gray-800">
                            <thead>
                                <tr class="bg-[#E84400] text-white">
                                    <th class="px-4 py-2 border">Пользователь</th>
                                    <th class="px-4 py-2 border">Email</th>
                                    <th class="px-4 py-2 border">Категория</th>
                                    <th class="px-4 py-2 border">Баллы</th>
                                    <th class="px-4 py-2 border">Макс. баллов</th>
                                    <th class="px-4 py-2 border">Процент</th>
                                    <th class="px-4 py-2 border">Дата</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($attempts as $attempt)
                                    <tr class="border-b border-gray-200 dark:border-gray-700">
                                        <td class="px-4 py-2 dark:text-white">{{ $attempt->user?->name ?? 'Пользователь удалён' }}</td>
                                        <td class="px-4 py-2 dark:text-white">{{ $attempt->user?->email ?? '—' }}</td>
                                        <td class="px-4 py-2 dark:text-white">{{ $attempt->subject->name }}</td>
                                        <td class="px-4 py-2 dark:text-white">{{ $attempt->score }}</td>
                                        <td class="px-4 py-2 dark:text-white">{{ $attempt->total_points }}</td>
                                        <td class="px-4 py-2 dark:text-white">{{ round($attempt->percentage, 2) }}%</td>
                                        <td class="px-4 py-2 dark:text-white">{{ $attempt->created_at->format('d.m.Y H:i') }}</td>
                                    </tr>
                                @empty
                                    <tr><td colspan="7" class="text-center py-4">Нет записей</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-4">
                        {{ $attempts->appends(['search' => request('search')])->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>