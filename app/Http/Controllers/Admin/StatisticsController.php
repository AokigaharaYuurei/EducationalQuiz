<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\QuizAttempt;
use App\Models\User;
use App\Models\Subject;

class StatisticsController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');
        
        $attemptsQuery = QuizAttempt::with(['user', 'subject']);
        
        if ($search) {
            $attemptsQuery->whereHas('user', function ($query) use ($search) {
                $query->where('name', 'like', "%{$search}%")
                      ->orWhere('email', 'like', "%{$search}%");
            });
        }
        
        $attempts = $attemptsQuery->orderBy('created_at', 'desc')->paginate(15);
        
        $categoryStats = Subject::with(['quizAttempts'])->get()->map(function ($subject) {
            $avgPercentage = $subject->quizAttempts->avg('percentage') ?? 0;
            return [
                'category' => $subject->name,
                'avg_percentage' => round($avgPercentage, 2),
            ];
        })->filter(function ($item) {
            return $item['avg_percentage'] > 0; 
        });
        
        $totalQuizzes = QuizAttempt::count();
        
        return view('admin.statistics', compact('attempts', 'categoryStats', 'totalQuizzes', 'search'));
    }
    
   public function export(Request $request)
{
    $search = $request->input('search');
    $attemptsQuery = QuizAttempt::with(['user', 'subject']);
    
    if ($search) {
        $attemptsQuery->whereHas('user', function ($query) use ($search) {
            $query->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
        });
    }
    
    $attempts = $attemptsQuery->orderBy('created_at', 'desc')->get();
    
    $csvFileName = 'statistics_' . date('Y-m-d_His') . '.csv';
    
    // Добавляем BOM для UTF-8, чтобы русские буквы отображались в Excel
    $bom = "\xEF\xBB\xBF";
    
    $headers = [
        'Content-Type' => 'text/csv; charset=utf-8',
        'Content-Disposition' => "attachment; filename=\"$csvFileName\"",
    ];
    
    $callback = function () use ($attempts, $bom) {
        $file = fopen('php://output', 'w');
        fwrite($file, $bom);
        
        // Заголовки CSV (читаемые на русском)
        fputcsv($file, [
            'ID попытки',
            'Пользователь',
            'Email',
            'Категория',
            'Набранные баллы',
            'Максимальный балл',
            'Процент',
            'Дата и время'
        ]);
        
        foreach ($attempts as $attempt) {
            fputcsv($file, [
                $attempt->id,
                $attempt->user->name ?? 'Удалённый пользователь',
                $attempt->user->email ?? '',
                $attempt->subject->name ?? '—',
                $attempt->score,
                $attempt->total_points,
                round($attempt->percentage, 2) . '%',
                $attempt->created_at->format('Y-m-d H:i:s')
            ]);
        }
        fclose($file);
    };
    
    return response()->stream($callback, 200, $headers);
}
}