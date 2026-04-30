<x-app-layout>
    <div class="wflex flex-col min-h-screen py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-[#000] overflow-hidden border-[#2A2A2A] dark:border-[#fff] border-2 shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">

                    <div class="flex justify-between items-center border-b pb-4 mb-6">
                        <h1 class="text-2xl font-bold">{{ $subject->name }}</h1>
                        <div class="text-xl font-mono bg-gray-200 dark:bg-gray-700 px-4 py-2 rounded">
                            <span id="timer">00:20</span>
                        </div>
                    </div>

                    <div id="question-area">
                        <form id="quiz-form" action="{{ route('quiz.submit', $subject->id) }}" method="POST">
                            @csrf
                            <div id="questions-data" data-questions='@json($questions)'></div>
                            <div id="answers-container"></div>
                            <div id="current-question" class="mb-6"></div>
                        </form>
                        <div class="flex justify-between mt-4">
                            <button type="button" id="prev-btn" class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded disabled:opacity-50" disabled>← Назад</button>
                            <button type="button" id="next-btn" class="bg-[#E84400] hover:bg-[#df6937] text-white px-4 py-2 rounded">Далее →</button>
                            <button type="button" id="submit-btn" class="bg-[#E84400] hover:bg-[#df6937] text-white px-4 py-2 rounded hidden">Завершить</button>
                        </div>
                    </div>

                    <div id="scale-screen" class="hidden text-center py-6">
                        <h2 class="text-2xl font-bold mb-4 text-[#E84400]">Правильно!</h2>
                        <p class="mb-4 text-lg">Вы заработали <span id="earned-points"></span> баллов.</p>
                        <div class="flex justify-center mb-6">
                            <div id="vertical-scale" class="flex flex-col-reverse items-center gap-2">
                            </div>
                        </div>
                        <button id="continue-btn" class="bg-[#E84400] hover:bg-[#df6937] text-white px-6 py-2 rounded">Следующий вопрос →</button>
                    </div>

                    <div id="game-over-screen" class="hidden text-center py-8">
                        <h2 class="text-2xl font-bold mb-4 text-[#E84400]">Неправильный ответ!</h2>
                        <p class="mb-4">Викторина завершена.</p>
                        <p class="mb-4">Ваш выигрыш: <span id="final-score">0</span> баллов</p>
                        <button id="finish-game-btn" class="bg-[#E84400] hover:bg-[#df6937] text-white px-6 py-2 rounded">Завершить и посмотреть результат</button>
                    </div>

                </div>
            </div>
        </div>
    </div>

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
        const questions = @json($questions);
        const scale = [10, 20, 50, 100, 200, 500, 1000, 2000];
        const totalQuestions = questions.length;
        let currentStep = 0;
        let userAnswers = new Array(totalQuestions).fill(null);
        let timerInterval = null;
        let timeLeft = 40;
        let gameActive = true;

        const currentQuestionDiv = document.getElementById('current-question');
        const prevBtn = document.getElementById('prev-btn');
        const nextBtn = document.getElementById('next-btn');
        const submitBtn = document.getElementById('submit-btn');
        const timerSpan = document.getElementById('timer');
        const answersContainer = document.getElementById('answers-container');
        const questionArea = document.getElementById('question-area');
        const scaleScreen = document.getElementById('scale-screen');
        const gameOverScreen = document.getElementById('game-over-screen');
        const continueBtn = document.getElementById('continue-btn');
        const finishGameBtn = document.getElementById('finish-game-btn');
        const verticalScaleDiv = document.getElementById('vertical-scale');
        const earnedPointsSpan = document.getElementById('earned-points');
        const finalScoreSpan = document.getElementById('final-score');

        function renderVerticalScale(completedSteps) {
            verticalScaleDiv.innerHTML = '';

            for (let i = scale.length - 1; i >= 0; i--) {
                const stepValue = scale[i];
                const stepDiv = document.createElement('div');
                stepDiv.className = 'w-20 h-12 flex items-center justify-center border rounded-md m-1 text-sm font-bold transition-all';
                if (i < currentStep) {
                    stepDiv.className += ' bg-[#E84400] text-white';
                } else if (i === currentStep) {
                    stepDiv.className += ' bg-yellow-400 text-black';
                } else {
                    stepDiv.className += ' bg-gray-300 dark:bg-gray-600 text-gray-600 dark:text-gray-300';
                }
                stepDiv.textContent = stepValue;
                verticalScaleDiv.appendChild(stepDiv);
            }
        }

        function renderQuestion() {
            if (!gameActive) return;
            const q = questions[currentStep];
            if (!q) {
                finishQuiz();
                return;
            }
            let html = `<h3 class="text-lg font-semibold mb-4">Вопрос ${currentStep+1} из ${totalQuestions}</h3>`;
            html += `<p class="mb-4 text-gray-700 dark:text-gray-300">${q.question_text}</p>`;
            html += `<div class="space-y-2">`;
            q.answers.forEach(answer => {
                const checked = (userAnswers[currentStep] == answer.id) ? 'checked' : '';
                html += `
                    <label class="flex items-center space-x-3 p-2 rounded hover:bg-gray-100 dark:hover:bg-gray-700 cursor-pointer">
                        <input type="radio" name="question_radio" value="${answer.id}" ${checked} class="w-4 h-4" data-answer-id="${answer.id}">
                        <span class="text-gray-800 dark:text-gray-200">${answer.answer_text}</span>
                    </label>
                `;
            });
            html += `</div>`;
            currentQuestionDiv.innerHTML = html;

            document.querySelectorAll('input[name="question_radio"]').forEach(radio => {
                radio.addEventListener('change', (e) => {
                    userAnswers[currentStep] = parseInt(e.target.value);
                });
            });

            prevBtn.disabled = (currentStep === 0);
            if (currentStep === totalQuestions - 1) {
                nextBtn.classList.add('hidden');
                submitBtn.classList.remove('hidden');
            } else {
                nextBtn.classList.remove('hidden');
                submitBtn.classList.add('hidden');
            }
        }

        function startTimer() {
            if (timerInterval) clearInterval(timerInterval);
            if (!gameActive) return;
            timeLeft = 40;
            updateTimerDisplay();
            timerInterval = setInterval(() => {
                if (!gameActive) return;
                if (timeLeft <= 1) {
                    clearInterval(timerInterval);
                    handleGameOver();
                } else {
                    timeLeft--;
                    updateTimerDisplay();
                }
            }, 1000);
        }

        function updateTimerDisplay() {
            const minutes = Math.floor(timeLeft / 60);
            const seconds = timeLeft % 60;
            timerSpan.textContent = `${minutes.toString().padStart(2, '0')}:${seconds.toString().padStart(2, '0')}`;
        }

        function showScaleScreen() {
            clearInterval(timerInterval);
            questionArea.classList.add('hidden');
            scaleScreen.classList.remove('hidden');
            earnedPointsSpan.textContent = scale[currentStep];
            renderVerticalScale(currentStep);
        }

        function proceedToNextQuestion() {
            scaleScreen.classList.add('hidden');
            questionArea.classList.remove('hidden');
            if (currentStep + 1 < totalQuestions) {
                currentStep++;
                renderQuestion();
                startTimer();
            } else {
                finishQuiz();
            }
        }

        function showGameOver() {
            gameActive = false;
            clearInterval(timerInterval);
            questionArea.classList.add('hidden');
            scaleScreen.classList.add('hidden');
            gameOverScreen.classList.remove('hidden');
            let winScore = (currentStep > 0) ? scale[currentStep - 1] : 0;
            finalScoreSpan.textContent = winScore;
        }

        function handleGameOver() {
            if (gameActive) {
                showGameOver();
            }
        }

        function checkAnswerAndProceed() {
            if (!gameActive) return false;
            const selectedId = userAnswers[currentStep];
            if (selectedId === null) {
                alert('Пожалуйста, выберите вариант ответа');
                return false;
            }
            const currentQ = questions[currentStep];
            const correctId = currentQ.answers.find(a => a.is_correct)?.id;
            if (selectedId === correctId) {
                if (currentStep === totalQuestions - 1) {
                    finishQuiz();
                } else {
                    showScaleScreen();
                }
                return true;
            } else {
                handleGameOver();
                return false;
            }
        }

        function finishQuiz() {
            clearInterval(timerInterval);
            answersContainer.innerHTML = '';
            for (let i = 0; i <= currentStep; i++) {
                if (userAnswers[i] !== null) {
                    const input = document.createElement('input');
                    input.type = 'hidden';
                    input.name = `question_${questions[i].id}`;
                    input.value = userAnswers[i];
                    answersContainer.appendChild(input);
                }
            }
            document.getElementById('quiz-form').submit();
        }

        nextBtn.addEventListener('click', () => {
            if (gameActive) checkAnswerAndProceed();
        });
        submitBtn.addEventListener('click', (e) => {
            e.preventDefault();
            if (gameActive) checkAnswerAndProceed();
        });
        prevBtn.addEventListener('click', () => {
            if (currentStep > 0 && gameActive) {
                currentStep--;
                renderQuestion();
                startTimer();
            }
        });
        continueBtn.addEventListener('click', () => {
            proceedToNextQuestion();
        });
        finishGameBtn.addEventListener('click', () => {
            finishQuiz();
        });

        renderQuestion();
        startTimer();
    </script>
</x-app-layout>