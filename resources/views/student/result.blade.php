<x-app-layout>
    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <h1 class="text-2xl font-bold mb-4">Результаты викторины</h1>
                    <p><strong>Категория:</strong> {{ $attempt->subject->name }}</p>
                    <p><strong>Баллы:</strong> {{ $attempt->score }} из {{ $attempt->total_points }}</p>
                    <p><strong>Процент:</strong> {{ round($attempt->percentage, 2) }}%</p>

                    <div class="mt-6">
                        <a href="{{ route('student.index') }}" class="bg-[#E84400] hover:bg-[#df6937] text-white px-4 py-2 rounded">К списку викторин</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>