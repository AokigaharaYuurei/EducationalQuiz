<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Subject;
use App\Models\Question;
use App\Models\QuizAttempt;


class QuizController extends Controller
{
    public function start($subjectId)
    {
        $subject = Subject::findOrFail($subjectId);
        $questions = Question::where('subject_id', $subjectId)
                            ->with('answers')
                            ->inRandomOrder() 
                            ->get();

        if ($questions->isEmpty()) {
            return redirect()->route('student.index')
                ->with('error', 'Для этой категории ещё нет вопросов.');
        }

        $questions->each(function ($question) {
            $question->answers = $question->answers->shuffle();
        });

        return view('student.quiz', compact('subject', 'questions'));
    }

    public function submit(Request $request, $subjectId)
    {
        $subject = Subject::findOrFail($subjectId);
        $questions = Question::where('subject_id', $subjectId)->with('answers')->get();

        $score = 0;
        $totalPoints = 0;
        $answersData = [];

        foreach ($questions as $question) {
            $totalPoints += $question->points;
            $userAnswerId = $request->input("question_{$question->id}");
            $correctAnswer = $question->answers->where('is_correct', true)->first();

            $isCorrect = ($userAnswerId && $correctAnswer && $userAnswerId == $correctAnswer->id);
            if ($isCorrect) {
                $score += $question->points;
            }

            $answersData[$question->id] = [
                'question_text' => $question->question_text,
                'user_answer' => $userAnswerId ? optional($question->answers->find($userAnswerId))->answer_text : 'Не выбран',
                'correct_answer' => $correctAnswer->answer_text,
                'is_correct' => $isCorrect,
                'points' => $question->points
            ];
        }

        $percentage = ($totalPoints > 0) ? ($score / $totalPoints) * 100 : 0;

        $attempt = QuizAttempt::create([
            'user_id' => auth()->id(),
            'subject_id' => $subjectId,
            'score' => $score,
            'total_points' => $totalPoints,
            'percentage' => $percentage,
            'answers_data' => json_encode($answersData, JSON_UNESCAPED_UNICODE),
        ]);

        return redirect()->route('quiz.result', $attempt->id)
                         ->with('success', 'Викторина завершена!');
    }

    public function result($attemptId)
    {
        $attempt = QuizAttempt::with('subject')->findOrFail($attemptId);
        if ($attempt->user_id !== auth()->id()) {
            abort(403);
        }
        return view('student.result', compact('attempt'));
    }
}