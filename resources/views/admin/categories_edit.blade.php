<x-app-layout>
    <div class="mt-[50px] px-4">
        <a href="{{ route('admin.categories') }}" class="text-[#000] dark:text-[#fff] text-[25px] ml-[50px] hover:text-[#E84400] hover:underline transition">← Назад к списку предметов</a>

        <div class="max-w-md mx-auto mt-10 bg-white dark:bg-gray-800 p-6 rounded shadow">
            <h2 class="text-xl font-bold mb-4 dark:text-white">Редактирование предмета</h2>
            <form action="{{ route('admin.categories.update', $subject) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="mb-4">
                    <label class="block text-gray-700 dark:text-gray-300 mb-2">Название предмета</label>
                    <input type="text" name="name" value="{{ old('name', $subject->name) }}" 
                           class="w-full rounded border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white" required>
                    @error('name')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
                <button type="submit" class="bg-[#E84400] hover:bg-[#df6937] text-white px-4 py-2 rounded">Сохранить</button>
            </form>
        </div>
    </div>
</x-app-layout>