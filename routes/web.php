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
        // New routes for Super Admin pages
        Route::get('/sections', [SuperAdminController::class, 'sections'])->name('admin.sections');
        Route::get('/year-levels', [SuperAdminController::class, 'yearLevels'])->name('admin.year-levels');
        Route::get('/colleges', [SuperAdminController::class, 'colleges'])->name('admin.colleges');
        Route::get('/courses', [SuperAdminController::class, 'courses'])->name('admin.courses');
        Route::get('/semesters', [SuperAdminController::class, 'semesters'])->name('admin.semesters');
        Route::get('/enrollments', [SuperAdminController::class, 'enrollments'])->name('admin.enrollments');
        Route::get('/manage-users', [SuperAdminController::class, 'manageUsers'])->name('admin.manage-users');
        Route::get('/user-type', [SuperAdminController::class, 'usertype'])->name('admin.user-types');
        // Add more Super Admin features here later (e.g., Route::get('/users', ...);)

        //sections CRUD
        Route::get('/sections/create', [SuperAdminController::class, 'createSection'])->name('admin.sections.create');
        Route::get('/sections/{id}/edit', [SuperAdminController::class, 'editSection'])->name('admin.sections.edit');
        Route::post('/sections', [SuperAdminController::class, 'storeSection'])->name('admin.sections.store');
        Route::put('/sections/{id}', [SuperAdminController::class, 'updateSection'])->name('admin.sections.update');
        Route::get('/sections/{id}/delete', [SuperAdminController::class, 'delete'])->name('admin.sections.delete');  // For confirmation page
        Route::delete('/sections/{id}', [SuperAdminController::class, 'destroySection'])->name('admin.sections.destroy');  // For actual deletion


        // Colleges CRUD
        Route::get('/colleges/create', [SuperAdminController::class, 'createCollege'])->name('admin.colleges.create'); 
        Route::get('/colleges/{id}/edit', [SuperAdminController::class, 'editCollege'])->name('admin.colleges.edit');
        Route::post('/colleges', [SuperAdminController::class, 'storeCollege'])->name('admin.colleges.store');
        Route::put('/colleges/{id}', [SuperAdminController::class, 'updateCollege'])->name('admin.colleges.update');
        Route::delete('/colleges/{id}', [SuperAdminController::class, 'destroyCollege'])->name('admin.colleges.destroy');

        // Year Levels CRUD
        Route::get('/year-levels/create', [SuperAdminController::class, 'createYearLevel'])->name('admin.year-levels.create');
        Route::get('/year-levels/{id}/edit', [SuperAdminController::class, 'editYearLevel'])->name('admin.year-levels.edit');
        Route::post('/year-levels', [SuperAdminController::class, 'storeYearLevel'])->name('admin.year-levels.store');
        Route::put('/year-levels/{id}', [SuperAdminController::class, 'updateYearLevel'])->name('admin.year-levels.update');
        Route::delete('/year-levels/{id}', [SuperAdminController::class, 'destroyYearLevel'])->name('admin.year-levels.destroy');

        // User Types CRUD
        Route::get('/user-types/create', [SuperAdminController::class, 'createUserType'])->name('admin.user-types.create');
        Route::get('/user-types/{id}/edit', [SuperAdminController::class, 'editUserType'])->name('admin.user-types.edit');
        Route::post('/user-types', [SuperAdminController::class, 'storeUserType'])->name('admin.user-types.store');
        Route::put('/user-types/{id}', [SuperAdminController::class, 'updateUserType'])->name('admin.user-types.update');
        Route::delete('/user-types/{id}', [SuperAdminController::class, 'destroyUserType'])->name('admin.user-types.destroy');
        Route::get('/user-types/{id}/delete', [SuperAdminController::class, 'deleteUserType'])->name('admin.user-types.delete');

        // Courses CRUD
        Route::get('/courses/create', [SuperAdminController::class, 'createCourse'])->name('admin.courses.create');
        Route::get('/courses/{id}/edit', [SuperAdminController::class, 'editCourse'])->name('admin.courses.edit');
        Route::post('/courses', [SuperAdminController::class, 'storeCourse'])->name('admin.courses.store');
        Route::put('/courses/{id}', [SuperAdminController::class, 'updateCourse'])->name('admin.courses.update');
        Route::delete('/courses/{id}', [SuperAdminController::class, 'destroyCourse'])->name('admin.courses.destroy');

        // Semesters CRUD
        Route::get('/semesters/create', [SuperAdminController::class, 'createSemester'])->name('admin.semesters.create');
        Route::get('/semesters/{id}/edit', [SuperAdminController::class, 'editSemester'])->name('admin.semesters.edit');
        Route::post('/semesters', [SuperAdminController::class, 'storeSemester'])->name('admin.semesters.store');
        Route::put('/semesters/{id}', [SuperAdminController::class, 'updateSemester'])->name('admin.semesters.update');
        Route::delete('/semesters/{id}', [SuperAdminController::class, 'destroySemester'])->name('admin.semesters.destroy');

        // Enrollments CRUD
        Route::get('/enrollments/enroll-students', [SuperAdminController::class, 'enrollStudents'])->name('admin.enrollments.enroll-students');
        Route::post('/enrollments/enroll-students', [SuperAdminController::class, 'storeEnrollStudents'])->name('admin.enrollments.store-enroll-students');
        Route::get('/enrollments/create', [SuperAdminController::class, 'createEnrollment'])->name('admin.enrollments.create');
        Route::get('/enrollments/{id}/edit', [SuperAdminController::class, 'editEnrollment'])->name('admin.enrollments.edit');
        Route::post('/enrollments', [SuperAdminController::class, 'storeEnrollment'])->name('admin.enrollments.store');
        Route::put('/enrollments/{id}', [SuperAdminController::class, 'updateEnrollment'])->name('admin.enrollments.update');
        Route::delete('/enrollments/{id}', [SuperAdminController::class, 'destroyEnrollment'])->name('admin.enrollments.destroy');

        // Users CRUD
        Route::get('/users/bulk-upload', [SuperAdminController::class, 'showBulkUploadForm'])->name('admin.users.bulk-upload-form');
        Route::get('/users/create', [SuperAdminController::class, 'createUser'])->name('admin.users.create');
        Route::get('/users/{id}/edit', [SuperAdminController::class, 'editUser'])->name('admin.users.edit');
        Route::post('/users', [SuperAdminController::class, 'storeUser'])->name('admin.users.store');
        Route::put('/users/{id}', [SuperAdminController::class, 'updateUser'])->name('admin.users.update');
        Route::delete('/users/{id}', [SuperAdminController::class, 'destroyUser'])->name('admin.users.destroy');
        Route::post('/users/bulk-upload', [SuperAdminController::class, 'bulkUploadUsers'])->name('admin.users.bulk-upload');
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