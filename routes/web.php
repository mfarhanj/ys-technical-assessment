<?php

use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::middleware(['auth'])->group(function () {
    Route::get('/home', [HomeController::class, 'index'])->name('home');

    // Profile routes (all authenticated users)
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::get('/profile/check-email', [ProfileController::class, 'checkEmail'])->name('profile.check-email');

    // Lecturer-only routes
    Route::middleware('lecturer')->prefix('lecturer')->name('lecturer.')->group(function () {
        Route::get('/dashboard', fn () => view('lecturer.dashboard'))->name('dashboard');
        Route::get('/classes', fn () => view('lecturer.classes'))->name('classes');
        Route::get('/classes/{class}', fn (App\Models\ClassModel $class) => view('lecturer.class-manage', ['class' => $class]))->name('classes.manage');
        Route::get('/subjects', fn () => view('lecturer.subjects'))->name('subjects');
        Route::get('/exams', fn () => view('lecturer.exams'))->name('exams');
        Route::get('/exams/create', fn () => view('lecturer.exam-form', ['exam' => null]))->name('exams.create');
        Route::get('/exams/{exam}/edit', [App\Http\Controllers\Lecturer\ExamViewController::class, 'edit'])->name('exams.edit');
        Route::get('/results', fn () => view('lecturer.results'))->name('results');
    });

    // Student-only routes
    Route::middleware('student')->prefix('student')->name('student.')->group(function () {
        Route::get('/dashboard', fn () => view('student.dashboard'))->name('dashboard');
        Route::get('/exams', fn () => view('student.exams'))->name('exams');
        Route::get('/exams/{exam}/take', [App\Http\Controllers\Student\TakeExamController::class, 'show'])->name('exams.take');
        Route::get('/results', fn () => view('student.results'))->name('results');
    });
});
