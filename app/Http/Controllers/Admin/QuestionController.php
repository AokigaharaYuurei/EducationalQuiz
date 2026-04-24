<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Question;
use App\Models\Subject;
use Illuminate\Http\Request;

class QuestionController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');
        $questions = Question::with('subject', 'answers')
            ->when($search, function ($query, $search) {
                return $query->where('question_text', 'like', "%{$search}%");
            })
            ->latest()
            ->paginate(15);

        return view('admin.questions.index', compact('questions', 'search'));
    }

    public function create()
    {
        $subjects = Subject::all();
        return view('admin.questions.create', compact('subjects'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'subject_id' => 'required|exists:subjects,id',
            'question_text' => 'required|string',
            'points' => 'required|integer|min:1',
            'answers' => 'required|array|min:2',
            'answers.*.text' => 'required|string',
            'answers.*.is_correct' => 'nullable|boolean',
        ]);

        $question = Question::create([
            'subject_id' => $request->subject_id,
            'question_text' => $request->question_text,
            'points' => $request->points,
        ]);

        foreach ($request->answers as $answerData) {
            $question->answers()->create([
                'answer_text' => $answerData['text'],
                'is_correct' => $answerData['is_correct'] ?? false,
            ]);
        }

        return redirect()->route('admin.questions.index')
            ->with('success', 'Вопрос успешно создан.');
    }

    public function edit(Question $question)
    {
        $subjects = Subject::all();
        $question->load('answers');
        return view('admin.questions.edit', compact('question', 'subjects'));
    }

    public function update(Request $request, Question $question)
    {
        $request->validate([
            'subject_id' => 'required|exists:subjects,id',
            'question_text' => 'required|string',
            'points' => 'required|integer|min:1',
            'answers' => 'required|array|min:2',
            'answers.*.id' => 'nullable|exists:answers,id',
            'answers.*.text' => 'required|string',
            'answers.*.is_correct' => 'nullable|boolean',
        ]);

        $question->update([
            'subject_id' => $request->subject_id,
            'question_text' => $request->question_text,
            'points' => $request->points,
        ]);

        $existingAnswerIds = [];
        foreach ($request->answers as $answerData) {
            if (isset($answerData['id'])) {
                $answer = $question->answers()->find($answerData['id']);
                if ($answer) {
                    $answer->update([
                        'answer_text' => $answerData['text'],
                        'is_correct' => $answerData['is_correct'] ?? false,
                    ]);
                    $existingAnswerIds[] = $answer->id;
                }
            } else {
                $newAnswer = $question->answers()->create([
                    'answer_text' => $answerData['text'],
                    'is_correct' => $answerData['is_correct'] ?? false,
                ]);
                $existingAnswerIds[] = $newAnswer->id;
            }
        }
        $question->answers()->whereNotIn('id', $existingAnswerIds)->delete();

        return redirect()->route('admin.questions.index')
            ->with('success', 'Вопрос обновлён.');
    }

    public function destroy(Question $question)
    {
        $question->delete();
        return redirect()->route('admin.questions.index')
            ->with('success', 'Вопрос удалён.');
    }
}