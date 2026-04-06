<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\ClientLoginController;
use App\Http\Controllers\WorkoutController;

// ─── Static Pages (UserController) ───────────────────────────────────────────
Route::middleware(['auth.client'])->group(function () {
    Route::get('/home',       [UserController::class, 'Home'])->name('home');
    Route::get('/membership', [UserController::class, 'Membership'])->name('membership');
    Route::get('/equipment',  [UserController::class, 'Equipment'])->name('equipment');
    Route::get('/feedback',   [UserController::class, 'Feedback'])->name('feedback');

    // ✅ Workout Plans
    Route::get('/plans',                                    [WorkoutController::class, 'index'])->name('plans');
    Route::post('/plans',                                   [WorkoutController::class, 'store'])->name('plans.store');
    Route::put('/plans/{plan}',                             [WorkoutController::class, 'update'])->name('plans.update');
    Route::delete('/plans/{plan}',                          [WorkoutController::class, 'destroy'])->name('plans.destroy');
    Route::post('/plans/{plan}/exercises',                  [WorkoutController::class, 'addExercise'])->name('plans.exercises.store');
    Route::put('/exercises/{exercise}',                     [WorkoutController::class, 'updateExercise'])->name('exercises.update');
    Route::delete('/exercises/{exercise}',                  [WorkoutController::class, 'destroyExercise'])->name('exercises.destroy');
});
// ─── Auth: Login ─────────────────────────────────────────────────────────────
Route::get('/',       [ClientLoginController::class, 'showLogin'])->name('login');
Route::post('/login', [ClientLoginController::class, 'login'])->name('login.submit');
Route::post('/logout',[ClientLoginController::class, 'logout'])->name('logout');

// ─── Auth: Registration ───────────────────────────────────────────────────────
Route::get('/register',              [RegisterController::class, 'showForm'])->name('register');
Route::post('/register/send-otp',    [RegisterController::class, 'sendOtp'])->name('register.send-otp');
Route::get('/register/otp',          [RegisterController::class, 'showOtpForm'])->name('register.otp.form');
Route::post('/register/verify-otp',  [RegisterController::class, 'verifyOtp'])->name('register.verify-otp');
Route::post('/register/resend-otp',  [RegisterController::class, 'resendOtp'])->name('register.resend-otp');
Route::get('/register/success',      [RegisterController::class, 'success'])->name('register.success');