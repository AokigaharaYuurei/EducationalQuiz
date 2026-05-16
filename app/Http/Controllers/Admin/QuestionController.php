<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Question;
use App\Models\Subject;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class QuestionController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');
        $withTrashed = $request->boolean('with_trashed');

        $questions = Question::with('subject', 'answers')
            ->when($search, fn($q) => $q->where('question_text', 'like', "%{$search}%"))
            ->when($withTrashed, fn($q) => $q->withTrashed())
            ->latest()
            ->paginate(15);

        return view('admin.questions.index', compact('questions', 'search', 'withTrashed'));
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
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'answers.*.image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ], [
            'answers.min' => 'Добавьте минимум два варианта ответа',
        ]);

        $hasCorrect = collect($request->answers)->contains(fn($answer) => ($answer['is_correct'] ?? false) == true);
        if (!$hasCorrect) {
            return back()->withInput()->withErrors(['answers' => 'Необходимо отметить хотя бы один правильный ответ']);
        }

        $questionData = [
            'subject_id' => $request->subject_id,
            'question_text' => $request->question_text,
            'points' => $request->points,
        ];
        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('questions', 'public');
            $questionData['image'] = $path;
        }
        $question = Question::create($questionData);

        foreach ($request->answers as $answerData) {
            $answerItem = [
                'answer_text' => $answerData['text'],
                'is_correct' => $answerData['is_correct'] ?? false,
            ];
            if (isset($answerData['image']) && $answerData['image'] instanceof \Illuminate\Http\UploadedFile) {
                $path = $answerData['image']->store('answers', 'public');
                $answerItem['image'] = $path;
            }
            $question->answers()->create($answerItem);
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
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'answers.*.image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'delete_question_image' => 'nullable|boolean',
        ]);

        $hasCorrect = collect($request->answers)->contains(fn($answer) => ($answer['is_correct'] ?? false) == true);
        if (!$hasCorrect) {
            return back()->withInput()->withErrors(['answers' => 'Необходимо отметить хотя бы один правильный ответ']);
        }

        $questionData = [
            'subject_id' => $request->subject_id,
            'question_text' => $request->question_text,
            'points' => $request->points,
        ];

        if ($request->hasFile('image')) {
            if ($question->image) {
                Storage::disk('public')->delete($question->image);
            }
            $path = $request->file('image')->store('questions', 'public');
            $questionData['image'] = $path;
        } elseif ($request->input('delete_question_image') == 1 && $question->image) {
            Storage::disk('public')->delete($question->image);
            $questionData['image'] = null;
        }

        $question->update($questionData);

        $existingAnswerIds = [];
        foreach ($request->answers as $answerData) {
            if (isset($answerData['id'])) {
                $answer = $question->answers()->find($answerData['id']);
                if ($answer) {
                    $updateData = [
                        'answer_text' => $answerData['text'],
                        'is_correct' => $answerData['is_correct'] ?? false,
                    ];
                    if (isset($answerData['image']) && $answerData['image'] instanceof \Illuminate\Http\UploadedFile) {
                        if ($answer->image) {
                            Storage::disk('public')->delete($answer->image);
                        }
                        $path = $answerData['image']->store('answers', 'public');
                        $updateData['image'] = $path;
                    } elseif (isset($answerData['delete_image']) && $answerData['delete_image'] == 1) {
                        if ($answer->image) {
                            Storage::disk('public')->delete($answer->image);
                        }
                        $updateData['image'] = null;
                    }
                    $answer->update($updateData);
                    $existingAnswerIds[] = $answer->id;
                }
            } else {
                $newAnswerData = [
                    'answer_text' => $answerData['text'],
                    'is_correct' => $answerData['is_correct'] ?? false,
                ];
                if (isset($answerData['image']) && $answerData['image'] instanceof \Illuminate\Http\UploadedFile) {
                    $path = $answerData['image']->store('answers', 'public');
                    $newAnswerData['image'] = $path;
                }
                $newAnswer = $question->answers()->create($newAnswerData);
                $existingAnswerIds[] = $newAnswer->id;
            }
        }

        $answersToDelete = $question->answers()->whereNotIn('id', $existingAnswerIds)->get();
        foreach ($answersToDelete as $answer) {
            if ($answer->image) {
                Storage::disk('public')->delete($answer->image);
            }
            $answer->delete();
        }

        return redirect()->route('admin.questions.index')
            ->with('success', 'Вопрос обновлён.');
    }

    public function destroy(Question $question)
{
    if ($question->image) {
        Storage::disk('public')->delete($question->image);
    }
    foreach ($question->answers as $answer) {
        if ($answer->image) {
            Storage::disk('public')->delete($answer->image);
        }
    }
    $question->delete();
    return redirect()->route('admin.questions.index')->with('success', 'Вопрос удалён.');
}

    public function restore($id)
    {
        $question = Question::withTrashed()->findOrFail($id);
        $question->restore();
        return back()->with('success', 'Вопрос восстановлен.');
    }
}