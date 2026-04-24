<x-app-layout>
    <div class="mt-[50px] px-4">
        <a href="{{ route('admin.questions.index') }}" class="text-[#000] dark:text-[#fff] text-[25px] ml-[30px] hover:text-[#E84400] hover:underline transition">← Назад к вопросам</a>

        <div class="max-w-3xl mx-auto mt-8 bg-white dark:bg-gray-800 p-6 rounded shadow">
            <h2 class="text-2xl font-bold mb-6 dark:text-white">Добавление вопроса</h2>

            <form action="{{ route('admin.questions.store') }}" method="POST" id="questionForm">
                @csrf

                <div class="mb-4">
                    <label class="block text-gray-700 dark:text-gray-300 mb-2">Категория (предмет)</label>
                    <select name="subject_id" class="w-full rounded border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white" required>
                        <option value="">Выберите категорию</option>
                        @foreach($subjects as $subject)
                            <option value="{{ $subject->id }}" {{ old('subject_id') == $subject->id ? 'selected' : '' }}>{{ $subject->name }}</option>
                        @endforeach
                    </select>
                    @error('subject_id') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
                </div>

                <div class="mb-4">
                    <label class="block text-gray-700 dark:text-gray-300 mb-2">Текст вопроса</label>
                    <textarea name="question_text" rows="4" class="w-full rounded border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white" required>{{ old('question_text') }}</textarea>
                    @error('question_text') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
                </div>

                <div class="mb-4">
                    <label class="block text-gray-700 dark:text-gray-300 mb-2">Количество баллов</label>
                    <input type="number" name="points" value="{{ old('points', 1) }}" min="1" class="w-32 rounded border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white" required>
                    @error('points') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
                </div>

                <div class="mb-4">
                    <label class="block text-gray-700 dark:text-gray-300 mb-2">Варианты ответов</label>
                    <div id="answers-container">
                        @foreach(old('answers', [['text' => '', 'is_correct' => false]]) as $index => $answer)
                            <div class="answer-group flex gap-2 mb-2 items-start">
                                <input type="text" name="answers[{{ $index }}][text]" value="{{ $answer['text'] }}" placeholder="Текст ответа" class="flex-1 rounded border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white" required>
                                <label class="flex items-center gap-1 whitespace-nowrap">
                                    <input type="checkbox" name="answers[{{ $index }}][is_correct]" value="1" {{ $answer['is_correct'] ? 'checked' : '' }} class="rounded">
                                    <span class="text-sm dark:text-white">Правильный</span>
                                </label>
                                <button type="button" class="remove-answer bg-red-500 text-white px-2 py-1 rounded">✕</button>
                            </div>
                        @endforeach
                    </div>
                    <button type="button" id="add-answer" class="mt-2 bg-gray-500 hover:bg-gray-600 text-white px-3 py-1 rounded text-sm">+ Добавить вариант</button>
                    @error('answers') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
                </div>

                <div class="flex justify-end">
                    <button type="submit" class="bg-[#E84400] hover:bg-[#F7733C] text-white px-6 py-2 rounded">Сохранить вопрос</button>
                </div>
            </form>
        </div>
    </div>

    @push('scripts')
    <script>
        let answerIndex = {{ count(old('answers', [['text' => '', 'is_correct' => false]])) }};
        document.getElementById('add-answer').addEventListener('click', function() {
            const container = document.getElementById('answers-container');
            const newDiv = document.createElement('div');
            newDiv.className = 'answer-group flex gap-2 mb-2 items-start';
            newDiv.innerHTML = `
                <input type="text" name="answers[${answerIndex}][text]" placeholder="Текст ответа" class="flex-1 rounded border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white" required>
                <label class="flex items-center gap-1 whitespace-nowrap">
                    <input type="checkbox" name="answers[${answerIndex}][is_correct]" value="1" class="rounded">
                    <span class="text-sm">Правильный</span>
                </label>
                <button type="button" class="remove-answer bg-red-500 text-white px-2 py-1 rounded">✕</button>
            `;
            container.appendChild(newDiv);
            answerIndex++;
        });
        document.addEventListener('click', function(e) {
            if (e.target.classList.contains('remove-answer')) {
                e.target.closest('.answer-group').remove();
            }
        });
    </script>
    @endpush
</x-app-layout>