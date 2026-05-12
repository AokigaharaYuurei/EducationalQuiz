<x-app-layout>
    <div class="mt-[50px] px-4">
        <a href="{{ route('admin.index') }}" class="text-[#000] dark:text-[#fff] text-[25px] ml-[30px] hover:text-[#E84400] hover:underline transition">
            <span class="hidden sm:inline">←Панель администратора</span>
            <span class="sm:hidden">←Админ-панель</span>
        </a>

        <div class="mt-8 flex justify-between items-center gap-4 flex-wrap">
            <form method="GET" action="{{ route('admin.questions.index') }}" id="filterForm" class="flex flex-wrap justify-between items-end gap-4 flex-1">
                <div class="flex gap-2 max-w-md flex-1">
                    <input type="text" name="search" placeholder="Поиск по тексту вопроса..."
                        value="{{ request('search') }}"
                        class="flex-1 px-4 py-2 border border-gray-300 dark:border-gray-600 dark:bg-gray-800 dark:text-white rounded">
                    <button type="submit" class="bg-[#E84400] hover:bg-[#df6937] text-white px-4 py-2 rounded">Поиск</button>
                    @if(request('search'))
                        <a href="{{ route('admin.questions.index', ['with_trashed' => request('with_trashed')]) }}" class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded">Сбросить</a>
                    @endif
                </div>
                <label class="inline-flex items-center">
                    <input type="checkbox" name="with_trashed" value="1" {{ request('with_trashed') ? 'checked' : '' }}
                           onchange="this.form.submit();" class="mr-2">
                    <span class="text-gray-700 dark:text-gray-300">Показать удалённые</span>
                </label>
            </form>
            <a href="{{ route('admin.questions.create') }}" class="bg-[#E84400] hover:bg-[#F7733C] text-white px-4 py-2 rounded whitespace-nowrap">+ Добавить вопрос</a>
        </div>

        <div class="mt-8 overflow-hidden rounded-lg border-2 border-[#E84400] dark:border-[#E84400]">
            <div class="overflow-x-auto">
                <table class="min-w-full bg-white dark:bg-gray-800">
                    <thead>
                        <tr class="bg-[#E84400]">
                            <th class="px-6 py-3 text-left text-xs font-medium text-white uppercase">ID</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-white uppercase">Категория</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-white uppercase">Вопрос</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-white uppercase">Баллы</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-white uppercase">Варианты ответов</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-white uppercase">Действия</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($questions as $question)
                        <tr class="border-b border-gray-200 dark:border-gray-700 {{ $question->trashed() ? 'bg-gray-100 dark:bg-gray-900 opacity-75' : '' }}">
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white">{{ $question->id }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white">{{ $question->subject->name ?? '—' }}</td>
                            <td class="px-6 py-4 text-sm text-gray-900 dark:text-white">{{ Str::limit($question->question_text, 80) }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white">{{ $question->points }}</td>
                            <td class="px-6 py-4 text-sm text-gray-900 dark:text-white">
                                <ul class="list-disc pl-4">
                                    @foreach($question->answers as $answer)
                                    <li class="{{ $answer->is_correct ? 'text-[#F7733C] font-semibold' : '' }}">
                                        {{ $answer->answer_text }}
                                        @if($answer->is_correct) (верный) @endif
                                    </li>
                                    @endforeach
                                </ul>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium space-x-2">
                                @if($question->trashed())
                                    <form action="{{ route('admin.questions.restore', $question->id) }}" method="POST" class="inline-block">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit" class="bg-green-600 hover:bg-green-700 text-white px-3 py-1 rounded">Восстановить</button>
                                    </form>
                                @else
                                    <a href="{{ route('admin.questions.edit', $question) }}" class="bg-[#E84400] hover:bg-[#F7733C] text-white px-3 py-1 rounded inline-block">Редактировать</a>
                                    <form action="{{ route('admin.questions.destroy', $question) }}" method="POST" class="inline-block" onsubmit="return confirm('Удалить вопрос?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="bg-red-600 hover:bg-red-700 text-white px-3 py-1 rounded">Удалить</button>
                                    </form>
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr><td colspan="6" class="text-center py-4 text-gray-500">Вопросы не найдены.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <div class="mt-4">
            {{ $questions->appends(['search' => request('search'), 'with_trashed' => request('with_trashed')])->links() }}
        </div>

        @if(session('success')) <div class="mt-4 p-2 bg-green-100 text-green-800 rounded">{{ session('success') }}</div> @endif
        @if(session('error'))   <div class="mt-4 p-2 bg-red-100 text-red-800 rounded">{{ session('error') }}</div> @endif
    </div>
</x-app-layout>