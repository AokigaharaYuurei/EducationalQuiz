<x-app-layout>
    <div class="mt-[50px] px-4">
        <a href="{{ route('admin.questions.index') }}" class="text-[#000] dark:text-[#fff] text-[25px] ml-[30px] hover:text-[#E84400] hover:underline transition">← Назад к вопросам</a>

        <div class="max-w-3xl mx-auto mt-8 bg-white dark:bg-gray-800 p-6 rounded shadow">
            <h2 class="text-2xl font-bold mb-6 dark:text-white">Редактирование вопроса</h2>

            <form action="{{ route('admin.questions.update', $question) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <div class="mb-4">
                    <label class="block text-gray-700 dark:text-gray-300 mb-2">Категория</label>
                    <select name="subject_id" class="w-full rounded border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white" required>
                        @foreach($subjects as $subject)
                            <option value="{{ $subject->id }}" {{ $question->subject_id == $subject->id ? 'selected' : '' }}>{{ $subject->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="mb-4">
                    <label class="block text-gray-700 dark:text-gray-300 mb-2">Текст вопроса</label>
                    <textarea name="question_text" rows="4" class="w-full rounded border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white" required>{{ old('question_text', $question->question_text) }}</textarea>
                </div>

                <div class="mb-4">
                    <label class="block text-gray-700 dark:text-gray-300 mb-2">Изображение для вопроса</label>
                    @if($question->image)
                        <div class="mb-2">
                            <img src="{{ asset('storage/' . $question->image) }}" class="w-32 h-32 object-cover rounded border">
                            <label class="inline-flex items-center mt-1">
                                <input type="checkbox" name="delete_question_image" value="1" class="rounded">
                                <span class="ml-2 text-sm text-gray-600 dark:text-gray-400">Удалить текущее изображение</span>
                            </label>
                        </div>
                    @endif
                    <input type="file" name="image" accept="image/*" class="mt-1 w-full rounded border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white">
                </div>

                <div class="mb-4">
                    <label class="block text-gray-700 dark:text-gray-300 mb-2">Количество баллов</label>
                    <input type="number" name="points" value="{{ old('points', $question->points) }}" min="1" class="w-32 rounded border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white" required>
                </div>

                <div class="mb-4">
                    <label class="block text-gray-700 dark:text-gray-300 mb-2">Варианты ответов</label>
                    <div id="answers-container">
                        @foreach($question->answers as $index => $answer)
                            <div class="answer-group flex flex-wrap gap-2 mb-3 items-start border-b pb-2 border-gray-200 dark:border-gray-700">
                                <input type="text" name="answers[{{ $index }}][text]" value="{{ $answer->answer_text }}" placeholder="Текст ответа" class="flex-1 rounded border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white" required>
                                <input type="hidden" name="answers[{{ $index }}][id]" value="{{ $answer->id }}">
                                
                                <div class="flex items-center gap-2">
                                    @if($answer->image)
                                        <img src="{{ asset('storage/' . $answer->image) }}" class="w-8 h-8 object-cover rounded">
                                        <label class="inline-flex items-center">
                                            <input type="checkbox" name="answers[{{ $index }}][delete_image]" value="1" class="rounded">
                                            <span class="ml-1 text-xs text-gray-600">удалить</span>
                                        </label>
                                    @endif
                                    <input type="file" name="answers[{{ $index }}][image]" accept="image/*" class="text-sm">
                                </div>

                                <label class="flex items-center gap-1 whitespace-nowrap">
                                    <input type="checkbox" name="answers[{{ $index }}][is_correct]" value="1" {{ $answer->is_correct ? 'checked' : '' }} class="rounded">
                                    <span class="text-sm text-gray-900 dark:text-white">Правильный</span>
                                </label>
                                <button type="button" class="remove-answer bg-red-500 text-white px-2 py-1 rounded text-sm">✕</button>
                            </div>
                        @endforeach
                    </div>
                    <button type="button" id="add-answer" class="mt-2 bg-gray-500 hover:bg-gray-600 text-white px-3 py-1 rounded text-sm">+ Добавить вариант</button>
                </div>

                <div class="flex justify-end">
                    <button type="submit" class="bg-[#E84400] hover:bg-[#F7733C] text-white px-6 py-2 rounded">Обновить вопрос</button>
                </div>
            </form>
        </div>
    </div>

    @push('scripts')
    <script>
        let answerIndex = {{ count($question->answers) }};
        document.getElementById('add-answer').addEventListener('click', function() {
            const container = document.getElementById('answers-container');
            const newDiv = document.createElement('div');
            newDiv.className = 'answer-group flex flex-wrap gap-2 mb-3 items-start border-b pb-2 border-gray-200 dark:border-gray-700';
            newDiv.innerHTML = `
                <input type="text" name="answers[${answerIndex}][text]" placeholder="Текст ответа" class="flex-1 rounded border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white" required>
                <div class="flex items-center gap-2">
                    <input type="file" name="answers[${answerIndex}][image]" accept="image/*" class="text-sm">
                </div>
                <label class="flex items-center gap-1 whitespace-nowrap">
                    <input type="checkbox" name="answers[${answerIndex}][is_correct]" value="1" class="rounded">
                    <span class="text-sm text-gray-900 dark:text-white">Правильный</span>
                </label>
                <button type="button" class="remove-answer bg-red-500 text-white px-2 py-1 rounded text-sm">✕</button>
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