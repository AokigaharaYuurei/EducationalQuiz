<x-app-layout>
    <div class="mt-[50px]">
        <a href="{{route('admin.index')}}" class="text-[#000] dark:text-[#fff] text-[25px] ml-[50px] hover:text-[#E84400] hover:underline transition">Административная панель</a>
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-10 justify-items-center mt-[200px] text-[#fff] text-[20px]">
            <a href="{{ route('admin.users') }}" class="w-[300px] bg-[#E84400] rounded-3xl h-[200px] hover:bg-[#df6937] flex items-center justify-center text-center">
                Справочник пользователей
            </a>
            <a href="{{ route('admin.categories') }}" class="w-[300px] bg-[#E84400] rounded-3xl h-[200px] hover:bg-[#df6937] flex items-center justify-center text-center">
                Справочник категорий
            </a>
            <button class="w-[300px] bg-[#E84400] rounded-3xl h-[200px] hover:bg-[#df6937]">Справочник вопросов</button>
            <button class="w-[300px] bg-[#E84400] rounded-3xl h-[200px] hover:bg-[#df6937]">Форма статистики</button>
        </div>
    </div>
</x-app-layout>