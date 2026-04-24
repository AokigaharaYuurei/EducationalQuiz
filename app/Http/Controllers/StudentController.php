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
        
        $attempts = QuizAttempt::where('user_id', $user->id)->get();
        
        $totalQuizzes = $attempts->count();
        
        $subjectsStats = [];
        foreach ($attempts as $attempt) {
            $subjectId = $attempt->subject_id;
            if (!isset($subjectsStats[$subjectId])) {
                $subjectsStats[$subjectId] = [
                    'subject' => Subject::find($subjectId)->name,
                    'percentages' => []
                ];
            }
            $subjectsStats[$subjectId]['percentages'][] = $attempt->percentage;
        }
        
        $tableData = [];
        foreach ($subjectsStats as $subjectId => $data) {
            $avgPercentage = array_sum($data['percentages']) / count($data['percentages']);
            $tableData[] = [
                'category' => $data['subject'],
                'avg_percentage' => round($avgPercentage, 2) 
            ];
        }
        $subjects = Subject::all();
        
        return view('student.index', compact('totalQuizzes', 'tableData', 'subjects'));
    }
}