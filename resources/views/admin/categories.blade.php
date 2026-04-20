<x-app-layout>
    <div class="mt-[50px] px-4">
        <a href="{{ route('admin.index') }}" class="text-[#000] dark:text-[#fff] text-[25px] ml-[50px] hover:text-[#E84400] hover:underline transition">← Административная панель</a>

        <div class="mt-8 overflow-x-auto">
            <table class="min-w-full bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700">
                <thead>
                    <tr class="bg-gray-100 dark:bg-gray-700">
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">ID</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Название предмета</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Действия</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($subjects as $subject)
                        <tr class="border-b border-gray-200 dark:border-gray-700">
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white">{{ $subject->id }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white">{{ $subject->name }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium space-x-2">
                                <a href="{{ route('admin.categories.edit', $subject) }}" class="bg-blue-600 hover:bg-blue-700 text-white px-3 py-1 rounded inline-block">Переименовать</a>

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

        <div class="mt-8 p-4 bg-gray-100 dark:bg-gray-700 rounded">
            <h3 class="text-lg font-semibold mb-2 dark:text-white">Добавить новый предмет</h3>
            <form action="{{ route('admin.categories.store') }}" method="POST">
                @csrf
                <div class="flex gap-2">
                    <input type="text" name="name" placeholder="Название предмета, например: Английский" 
                           class="flex-1 rounded border-gray-300 dark:border-gray-600 dark:bg-gray-800 dark:text-white" required>
                    <button type="submit" class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded">Добавить</button>
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