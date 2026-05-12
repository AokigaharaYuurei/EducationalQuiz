<x-app-layout>
    <main class="flex-1 w-full transition-opacity opacity-100 duration-750 starting:opacity-0">
        <div class="flex w-full justify-center relative px-4 sm:px-6 lg:px-8">
            <div class="relative dark:bg-[#E7E9EF] bg-[#2A2A2A] rounded-3xl w-full max-w-7xl min-h-[280px] md:min-h-[320px] lg:min-h-[348px] overflow-hidden">

                <div class="flex items-center justify-center min-h-[280px] md:min-h-[320px] lg:min-h-[348px] py-8 md:py-12 lg:py-16 px-4">
                    <p class="text-center text-xl sm:text-2xl md:text-3xl lg:text-4xl font-bold dark:text-[#303030] text-[#FFFFFF] leading-tight">
                        Добро пожаловать в образовательную викторину!<br>
                        Интерактивная платформа для проверки знаний<br>по различным предметам.
                    </p>
                </div>
                <div class="hidden lg:block absolute right-[5%] top-[240px]">
                    <img src="{{asset('img/EllipseFull.png')}}" alt="">
                </div>

                <div class="hidden lg:block absolute left-[-30px] top-[20px] w-[97px] h-[113px]">
                    <img src="{{asset('img/point.png')}}" alt="">
                </div>

                <div class="hidden lg:block absolute right-[8%] top-[200px] w-[97px] h-[113px]">
                    <img src="{{asset('img/point.png')}}" alt="">
                </div>
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
                    <div class="overflow-hidden rounded-lg border-2 border-[#E84400] dark:border-[#E84400]">
                        <div class="overflow-x-auto">
                            <table class="min-w-full bg-white dark:bg-gray-800">
                                <thead>
                                    <tr class="bg-[#E84400] text-white">
                                        <th class="px-4 py-2 text-left">Категория</th>
                                        <th class="px-4 py-2 text-left">Средний процент правильных ответов</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($tableData as $row)
                                    <tr class="border-b border-gray-200 dark:border-gray-700">
                                        <td class="px-4 py-2 dark:text-white">{{ $row['category'] }}</td>
                                        <td class="px-4 py-2 dark:text-white">{{ $row['avg_percentage'] }}%</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
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
            @auth
            @if(auth()->user()->role === 'admin')
            <a href="{{ route('admin.index') }}"
                class="text-[#FFF] dark:text-[#303030] hover:text-[#878786] text-xl sm:text-2xl md:text-3xl transition">
                Панель администратора
            </a>
            @else
            <a href="{{ route('student.index') }}"
                class="text-[#FFF] dark:text-[#303030] hover:text-[#878786] text-xl sm:text-2xl md:text-3xl transition">
                Выбрать викторину
            </a>
            @endif
            @else
            <a href="{{ route('login') }}"
                class="text-[#FFF] dark:text-[#303030] hover:text-[#878786] text-xl sm:text-2xl md:text-3xl transition">
                Выбрать викторину
            </a>
            @endauth

            <a href="{{ route('rating.index') }}"
                class="text-[#FFF] dark:text-[#303030] hover:text-[#878786] text-xl sm:text-2xl md:text-3xl transition">
                Рейтинги
            </a>
        </div>
        <p class="flex items-center justify-center text-base sm:text-lg text-[#9A92AD] py-4 px-2 text-center">
            © 2026 Образовательная викторина. Все права защищены
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