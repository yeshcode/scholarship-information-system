<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SuperAdminController;
use App\Http\Controllers\CoordinatorController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;

// Public routes
Route::get('/', function () { return redirect('/login'); });
Route::get('/login', [AuthenticatedSessionController::class, 'create'])->name('login');
Route::post('/login', [AuthenticatedSessionController::class, 'store']);
Route::post('/logout', [AuthenticatedSessionController::class, 'destroy'])->name('logout');

// Protected routes (authenticated users only)
Route::middleware(['auth'])->group(function () {
    // Super Admin routes (only Super Admins can access)
    Route::middleware('role:Super Admin')->prefix('admin')->group(function () {
        Route::get('/dashboard', [SuperAdminController::class, 'dashboard'])->name('admin.dashboard');
        // Add more Super Admin features here later (e.g., Route::get('/users', ...);)
    });

    // Scholarship Coordinator routes (only Coordinators can access)
    Route::middleware('role:Scholarship Coordinator')->prefix('coordinator')->group(function () {
        Route::get('/dashboard', [CoordinatorController::class, 'dashboard'])->name('coordinator.dashboard');
        // Add more Coordinator features here later
    });

    // Student routes (only Students can access)
    Route::middleware('role:Student')->prefix('student')->group(function () {
        Route::get('/dashboard', [StudentController::class, 'dashboard'])->name('student.dashboard');
        // Add more Student features here later
    });
});