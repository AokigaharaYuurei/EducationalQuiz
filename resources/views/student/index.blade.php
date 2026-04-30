<x-app-layout>
    <main class="flex-1 w-full transition-opacity opacity-100 duration-750 starting:opacity-0">
        <div class="flex w-full justify-center">
            <div class="dark:bg-[#E7E9EF] rounded-3xl w-[1400px] bg-[#2A2A2A] h-[348px]">
                <p class="text-[32px] dark:text-[#303030] text-[#FFFFFF] font-bold mt-[100px] justify-center flex">
                    Добро пожаловать в образовательную викторину!<br>
                    Интерактивная платформа для проверки знаний<br>по различным предметам.
                </p>
            </div>
            <div class="hidden lg:block absolute ml-[980px] mt-[240px]">
                <img src="{{asset('img/EllipseFull.png')}}" alt="">
            </div>
            <div class="hidden lg:block absolute ml-[-1150px] mt-[20px] w-[97px] h-[113px]">
                <img src="{{asset('img/point.png')}}" alt="">
            </div>
            <div class="hidden lg:block absolute ml-[1100px] mt-[200px] w-[97px] h-[113px]">
                <img src="{{asset('img/point.png')}}" alt="">
            </div>
        </div>

        <div class="flex w-full justify-center mt-[150px]">
            <div class="w-[1400px] max-w-full px-4">
                <div>
                    <img src="{{asset('img/Line.png')}}" alt="">
                    <p class="text-[32px] mb-[15px] text-[#303030] font-bold dark:text-[#EDEDEC] mt-[30px] text-center md:text-left">
                        Статистика
                    </p>
                    <p class="text-[20px] mb-[15px] text-[#303030] font-bold dark:text-[#EDEDEC] mt-[30px]">
                        Общее количество пройденных викторин: {{ $totalQuizzes }}
                    </p>
                    <div class="overflow-x-auto rounded">
                        @if(count($tableData) > 0)
                        <table class="w-full border-collapse border-gray-300 dark:border-gray-600 border-2">
                            <thead>
                                <tr class="bg-[#E84400] text-white">
                                    <th class=" border-gray-300 dark:border-gray-600 px-4 py-2 border-2">Категория</th>
                                    <th class=" border-gray-300 dark:border-gray-600 px-4 py-2 border-2">Средний процент правильных ответов</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($tableData as $row)
                                <tr class="bg-white dark:bg-[#000]">
                                    <td class="border-2 border-gray-300 dark:border-gray-600 dark:text-white px-4 py-2">{{ $row['category'] }}</td>
                                    <td class="border-2 border-gray-300 dark:border-gray-600 dark:text-white px-4 py-2">{{ $row['avg_percentage'] }}%</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                        @else
                        <p class="text-gray-500 dark:text-gray-400 mt-4">Вы ещё не прошли ни одной викторины.</p>
                        @endif
                    </div>
                    <img src="{{asset('img/Line.png')}}" class="mt-8" alt="">
                </div>

                <div class="mt-12 text-center mb-[50px]">
                    <p class="text-[32px] mb-[15px] text-[#303030] font-bold dark:text-[#EDEDEC] mt-[30px]">
                        Выбор теста
                    </p>
                    <form id="quiz-form" class="flex flex-col items-center gap-4">
                        <select name="subject_id" id="subject_select" required
                            class="w-full max-w-md px-4 py-2 rounded border border-gray-300 dark:border-gray-600 dark:bg-[#000] dark:text-white bg-[#E7E9EF] text-[#303030] text-lg">
                            <option value="" class="text-[#303030] font-bold">Выберите категорию</option>
                            @foreach($subjects as $subject)
                            <option value="{{ $subject->id }}">{{ $subject->name }}</option>
                            @endforeach
                        </select>
                        <button type="submit" class="bg-[#F7733C] hover:bg-[#E84400] text-white px-6 py-2 rounded text-lg">
                            Начать тест
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </main>
    <div class="dark:bg-[#E7E9EF] bg-[#2A2A2A] w-full">
    <div class="flex flex-col sm:flex-row items-center justify-between gap-4 px-4 py-4 text-center sm:text-left">
        <img src="{{ asset('img/Logolight.png') }}" alt="Logo" class="w-auto block dark:hidden">
        <img src="{{ asset('img/Logodark.png') }}" alt="Logo" class="w-auto hidden dark:block">

        <a href="{{ route('login') }}" class="text-[#FFF] dark:text-[#303030] hover:text-[#878786] text-xl sm:text-2xl md:text-3xl transition">
            Выбрать викторину
        </a>
        <a href="{{ route('rating.index') }}" class="text-[#FFF] dark:text-[#303030] hover:text-[#878786] text-xl sm:text-2xl md:text-3xl transition">
            Рейтинги
        </a>
    </div>
    <p class="flex items-center justify-center text-base sm:text-lg text-[#9A92AD] py-4 px-2 text-center">
        © 2025 Образовательная викторина. Все права защищены
    </p>
</div>
    <script>
        document.getElementById('quiz-form').addEventListener('submit', function(e) {
            e.preventDefault(); 
            var subjectId = document.getElementById('subject_select').value;
            if (subjectId) {
                window.location.href = "{{ url('quiz') }}/" + subjectId;
            } else {
                alert('Пожалуйста, выберите категорию');
            }
        });
    </script>
</x-app-layout>