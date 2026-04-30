<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Middleware\Admin;
use App\Models\User;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\QuestionController;
use App\Http\Controllers\QuizController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\Admin\StatisticsController;
use App\Http\Controllers\RatingController;
use App\Models\Subject;

Route::get('/', function () {
    $subjects = Subject::all();
    return view('welcome', compact('subjects'));
})->name('welcome');

Route::get('/dashboard', function () {
    if (auth()->user()?->role === 'admin') {
        return redirect()->route('admin.index');
    }
    return redirect()->route('student.index');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::get('/student', [StudentController::class, 'index'])->name('student.index');
    Route::get('/quiz/{subjectId}', [QuizController::class, 'start'])->name('quiz.start');
    Route::post('/quiz/{subjectId}', [QuizController::class, 'submit'])->name('quiz.submit');
    Route::get('/quiz/result/{attemptId}', [QuizController::class, 'result'])->name('quiz.result');
    Route::get('/rating', [RatingController::class, 'index'])->name('rating.index');
});
Route::middleware((Admin::class))->group(function () {
    Route::get(
        '/admin',
        function () {
            return view('admin.index');
        }
    )->name('admin.index');
    Route::get('/admin/users', [UserController::class, 'index'])->name('admin.users');
    Route::delete('/admin/users/{user}', [UserController::class, 'destroy'])->name('admin.users.destroy');
    Route::patch('/admin/users/{user}/role', [UserController::class, 'toggleRole'])->name('admin.users.toggleRole');
    Route::get('/admin/categories', [CategoryController::class, 'index'])->name('admin.categories');
    Route::post('/admin/categories', [CategoryController::class, 'store'])->name('admin.categories.store');
    Route::get('/admin/categories/{subject}/edit', [CategoryController::class, 'edit'])->name('admin.categories.edit');
    Route::put('/admin/categories/{subject}', [CategoryController::class, 'update'])->name('admin.categories.update');
    Route::delete('/admin/categories/{subject}', [CategoryController::class, 'destroy'])->name('admin.categories.destroy');
    Route::get('/admin/questions', [QuestionController::class, 'index'])->name('admin.questions.index');
    Route::get('/admin/questions/create', [QuestionController::class, 'create'])->name('admin.questions.create');
    Route::post('/admin/questions', [QuestionController::class, 'store'])->name('admin.questions.store');
    Route::get('/admin/questions/{question}/edit', [QuestionController::class, 'edit'])->name('admin.questions.edit');
    Route::put('/admin/questions/{question}', [QuestionController::class, 'update'])->name('admin.questions.update');
    Route::delete('/admin/questions/{question}', [QuestionController::class, 'destroy'])->name('admin.questions.destroy');
    Route::get('/admin/statistics', [StatisticsController::class, 'index'])->name('admin.statistics');
    Route::get('/admin/statistics/export', [StatisticsController::class, 'export'])->name('admin.statistics.export');
});

require __DIR__ . '/auth.php';
