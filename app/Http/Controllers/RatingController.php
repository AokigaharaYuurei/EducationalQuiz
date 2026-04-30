<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\QuizAttempt;
use App\Models\User;

class RatingController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        $personalRating = QuizAttempt::where('user_id', $user->id)
            ->with('subject')
            ->get()
            ->groupBy('subject_id')
            ->map(function ($attempts) {
                $best = $attempts->sortByDesc('percentage')->first();
                return [
                    'subject_name' => $best->subject->name,
                    'score' => $best->score,
                    'total_points' => $best->total_points,
                    'percentage' => round($best->percentage, 2),
                    'completed_at' => $best->created_at->format('d.m.Y H:i'),
                ];
            })
            ->sortByDesc('percentage')
            ->values();

        $globalRating = User::whereHas('quizAttempts')
            ->with(['quizAttempts'])
            ->get()
            ->map(function ($user) {
                $totalScore = $user->quizAttempts->sum('score');
                $attemptsCount = $user->quizAttempts->count();
                $avgPercentage = $user->quizAttempts->avg('percentage') ? round($user->quizAttempts->avg('percentage'), 2) : 0;
                return [
                    'name' => $user->name,
                    'email' => $user->email,
                    'total_score' => $totalScore,
                    'attempts_count' => $attemptsCount,
                    'avg_percentage' => $avgPercentage,
                ];
            })
            ->sortByDesc('total_score')
            ->take(10)
            ->values();

        return view('rating.index', compact('personalRating', 'globalRating'));
    }
}
