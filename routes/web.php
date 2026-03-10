<?php

use App\Http\Controllers\ChapterController;
use App\Http\Controllers\CourseController;
use App\Http\Controllers\CurriculumController;
use App\Http\Controllers\ParticipantController;
use App\Http\Controllers\ParticipantCurriculumController;
use App\Http\Controllers\ProfileController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

// Route::get('/', function () {
//     return Inertia::render('Welcome', [
//         'canLogin' => Route::has('login'),
//         'canRegister' => Route::has('register'),
//         'laravelVersion' => Application::VERSION,
//         'phpVersion' => PHP_VERSION,
//     ]);
// });

// Route::get('/dashboard', function () {
//     return Inertia::render('Dashboard');
// })->name('dashboard');

// Route::middleware('auth')->group(function () {
//     Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
//     Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
//     Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
// });

require __DIR__.'/auth.php';

Route::get('/',[ParticipantController::class,'index'])->name('index');

Route::resource('/participants', ParticipantController::class,['only' => ['create','store','edit','update','show']]);
Route::patch('/participants/{participant}/curriculum/next',[ParticipantCurriculumController::class,'complete'])->name('complete');
Route::patch('/participants/{participant}/curriculum/cancel',[ParticipantCurriculumController::class, 'cancelComplete'])->name('cancelComplete');
Route::patch('/participants/{participant}/curriculum/start',[ParticipantCurriculumController::class, 'startCurriculum'])->name('startCurriculum');

Route::resource('/courses', CourseController::class,['only' => ['index','create','store','edit','update','show']]);
Route::resource('/chapters', ChapterController::class,['only' => ['create','store','show','edit','update']]);
Route::resource('/curricula', CurriculumController::class,['only' => ['create','store','show', 'edit', 'update']]);
