<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\QuizAttempt;
use App\Models\Subject;

class StudentController extends Controller
{
    public function index()
{
    $user = auth()->user();
    $attempts = QuizAttempt::where('user_id', $user->id)
                          ->with('subject')
                          ->orderBy('created_at', 'desc')
                          ->get();

    $totalQuizzes = $attempts->count();

    $lastAttempts = $attempts->groupBy('subject_id')->map(function ($group) {
        return $group->first(); 
    });

    $tableData = [];
    foreach ($lastAttempts as $attempt) {
        $tableData[] = [
            'category' => $attempt->subject->name,
            'avg_percentage' => round($attempt->percentage, 2), 
        ];
    }

    $subjects = Subject::all();

    return view('student.index', compact('totalQuizzes', 'tableData', 'subjects'));
}
}