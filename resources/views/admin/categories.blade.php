<x-app-layout>
    <div class="mt-[50px] px-4">
        <a href="{{ route('admin.index') }}" class="text-[#000] dark:text-[#fff] text-[25px] ml-[30px] hover:text-[#E84400] hover:underline transition">← Административная панель</a>
        <div class="mt-8">
            <form method="GET" action="{{ route('admin.categories') }}" class="flex gap-2 max-w-md">
                <input type="text" name="search" placeholder="Поиск по названию предмета..."
                    value="{{ request('search') }}"
                    class="flex-1 px-4 py-2 border border-gray-300 dark:border-gray-600 dark:bg-gray-800 dark:text-white rounded">
                <button type="submit" class="bg-[#E84400] hover:bg-[#df6937] text-white px-4 py-2 rounded">Поиск</button>
                @if(request('search'))
                <a href="{{ route('admin.categories') }}" class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded">Сбросить</a>
                @endif
            </form>
        </div>
        <div class="mt-8 overflow-x-auto">
            <table class="min-w-full bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-lg overflow-hidden">
                <thead>
                    <tr class="bg-[#E84400]">
                        <th class="px-6 py-3 text-left text-xs font-medium text-white uppercase">ID</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-white uppercase">Название предмета</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-white uppercase">Действия</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($subjects as $subject)
                    <tr class="border-b border-gray-200 dark:border-gray-700">
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white">{{ $subject->id }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white">{{ $subject->name }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium space-x-2">
                            <a href="{{ route('admin.categories.edit', $subject) }}" class="bg-[#E84400] hover:bg-[#F7733C] text-white px-3 py-1 rounded inline-block">Переименовать</a>

                            <form action="{{ route('admin.categories.destroy', $subject) }}" method="POST" class="inline-block" onsubmit="return confirm('Удалить предмет «{{ $subject->name }}»?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="bg-red-600 hover:bg-red-700 text-white px-3 py-1 rounded">Удалить</button>
                            </form>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="mt-8 p-4 bg-white dark:bg-gray-700 rounded">
            <h3 class="text-lg font-semibold mb-2 dark:text-white">Добавить новый предмет</h3>
            <form action="{{ route('admin.categories.store') }}" method="POST">
                @csrf
                <div class="flex gap-2">
                    <input type="text" name="name" placeholder="Название предмета"
                        class="flex-1 rounded border-gray-300 dark:border-gray-600 dark:bg-gray-800 dark:text-white" required>
                    <button type="submit" class="bg-[#E84400] hover:bg-[#F7733C] text-white px-4 py-2 rounded">Добавить</button>
                </div>
                @error('name')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </form>
        </div>

        @if(session('success'))
        <div class="mt-4 p-2 bg-green-100 text-green-800 rounded">{{ session('success') }}</div>
        @endif
    </div>
</x-app-layout>