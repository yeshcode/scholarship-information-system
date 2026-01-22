<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SuperAdminController;
use App\Http\Controllers\CoordinatorController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SettingsController;
use App\Http\Controllers\QuestionController;
use App\Http\Controllers\QuestionClusterController;
use App\Http\Controllers\SemesterFilterController;


// Public routes
Route::get('/', function () { return redirect('/login'); });
Route::get('/login', [AuthenticatedSessionController::class, 'create'])->name('login');
Route::post('/login', [AuthenticatedSessionController::class, 'store']);
Route::post('/logout', [AuthenticatedSessionController::class, 'destroy'])->name('logout');

// Protected routes (authenticated users only)
Route::middleware(['auth'])->group(function () {
    // NEW: Profile routes (accessible to all logged-in users) - Added here, before role-specific groups
    Route::get('/profile', [ProfileController::class, 'show'])->name('profile');
    Route::post('/profile/update-password', [ProfileController::class, 'updatePassword'])->name('profile.update-password');

    // Semester filter (Coordinator + Super Admin)
    Route::get('/semester/filter', [SemesterFilterController::class, 'show'])
        ->name('semester.filter.show');

    Route::post('/semester/filter', [SemesterFilterController::class, 'set'])
        ->name('semester.filter.set');

    Route::post('/semester/filter/clear', [SemesterFilterController::class, 'clear'])
        ->name('semester.filter.clear');


    Route::middleware('role:Super Admin')->group(function () {
        Route::get('/settings', [SettingsController::class, 'index'])->name('settings.index');
        Route::post('/settings', [SettingsController::class, 'update'])->name('settings.update');
    });


    // Super Admin routes (only Super Admins can access)
    Route::middleware('role:Super Admin')->prefix('admin')->group(function () {
        Route::get('/dashboard', [SuperAdminController::class, 'dashboard'])->name('admin.dashboard');
        Route::get('/dashboard-data', [SuperAdminController::class, 'dashboardData'])->name('admin.dashboard-data');

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
        Route::get('/colleges/{id}/delete', [SuperAdminController::class, 'deleteCollege'])->name('admin.colleges.delete');  // Add this for confirmation

        // Year Levels CRUD
        Route::get('/year-levels/create', [SuperAdminController::class, 'createYearLevel'])->name('admin.year-levels.create');
        Route::get('/year-levels/{id}/edit', [SuperAdminController::class, 'editYearLevel'])->name('admin.year-levels.edit');
        Route::post('/year-levels', [SuperAdminController::class, 'storeYearLevel'])->name('admin.year-levels.store');
        Route::put('/year-levels/{id}', [SuperAdminController::class, 'updateYearLevel'])->name('admin.year-levels.update');
        Route::delete('/year-levels/{id}', [SuperAdminController::class, 'destroyYearLevel'])->name('admin.year-levels.destroy');
        Route::get('/year-levels/{id}/delete', [SuperAdminController::class, 'deleteYearLevel'])->name('admin.year-levels.delete');  // Add this for confirmation

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
        Route::get('/courses/{id}/delete', [SuperAdminController::class, 'deleteCourse'])->name('admin.courses.delete');  // Add this for confirmation

        // Semesters CRUD
        Route::get('/semesters/create', [SuperAdminController::class, 'createSemester'])->name('admin.semesters.create');
        Route::get('/semesters/{id}/edit', [SuperAdminController::class, 'editSemester'])->name('admin.semesters.edit');
        Route::post('/semesters', [SuperAdminController::class, 'storeSemester'])->name('admin.semesters.store');
        Route::put('/semesters/{id}', [SuperAdminController::class, 'updateSemester'])->name('admin.semesters.update');
        Route::delete('/semesters/{id}', [SuperAdminController::class, 'destroySemester'])->name('admin.semesters.destroy');
        Route::get('/semesters/{id}/delete', [SuperAdminController::class, 'deleteSemester'])->name('admin.semesters.delete');  // Add this for confirmation

        // Enrollments CRUD
        Route::get('/enrollments/enroll-students', [SuperAdminController::class, 'enrollStudents'])->name('admin.enrollments.enroll-students');
        Route::post('/enrollments/enroll-students', [SuperAdminController::class, 'storeEnrollStudents'])->name('admin.enrollments.store-enroll-students');
        Route::get('/enrollments/create', [SuperAdminController::class, 'createEnrollment'])->name('admin.enrollments.create');
        Route::get('/enrollments/{id}/edit', [SuperAdminController::class, 'editEnrollment'])->name('admin.enrollments.edit');
        Route::post('/enrollments', [SuperAdminController::class, 'storeEnrollment'])->name('admin.enrollments.store');
        Route::put('/enrollments/{id}', [SuperAdminController::class, 'updateEnrollment'])->name('admin.enrollments.update');
        Route::delete('/enrollments/{id}', [SuperAdminController::class, 'destroyEnrollment'])->name('admin.enrollments.destroy');
        Route::get('/enrollments/{id}/delete', [SuperAdminController::class, 'deleteEnrollment'])->name('admin.enrollments.delete');  // For confirmation

        // Enrollment Records (by Academic Year)
        Route::get('/enrollments/records', [SuperAdminController::class, 'enrollmentRecords'])->name('admin.enrollments.records');
        Route::get('/enrollments/records/{academicYear}', [SuperAdminController::class, 'enrollmentRecordsByYear'])->name('admin.enrollments.records.year');


        // Users CRUD
        Route::get('/users/bulk-upload', [SuperAdminController::class, 'showBulkUploadForm'])->name('admin.users.bulk-upload-form');
        Route::get('/users/create', [SuperAdminController::class, 'createUser'])->name('admin.users.create');
        Route::get('/users/{id}/edit', [SuperAdminController::class, 'editUser'])->name('admin.users.edit');
        Route::post('/users', [SuperAdminController::class, 'storeUser'])->name('admin.users.store');
        Route::put('/users/{id}', [SuperAdminController::class, 'updateUser'])->name('admin.users.update');
        Route::delete('/users/{id}', [SuperAdminController::class, 'destroyUser'])->name('admin.users.destroy');
        Route::post('/users/bulk-upload', [SuperAdminController::class, 'bulkUploadUsers'])->name('admin.users.bulk-upload');
        Route::get('/users/{id}/delete', [SuperAdminController::class, 'deleteUser'])->name('admin.users.delete');  // Add this for confirmation
    });

    // Scholarship Coordinator routes (only Coordinators can access)
    Route::middleware('role:Scholarship Coordinator')->prefix('coordinator')->group(function () {

    Route::get('/dashboard', [CoordinatorController::class, 'dashboard'])->name('coordinator.dashboard');

    // Manage Scholars
    Route::get('/manage-scholars', [CoordinatorController::class, 'manageScholars'])->name('coordinator.manage-scholars');
    Route::get('/manage-scholars/create', [CoordinatorController::class, 'createScholar'])->name('coordinator.scholars.create');
    Route::post('/manage-scholars', [CoordinatorController::class, 'storeScholar'])->name('coordinator.scholars.store');

    // OCR
    Route::get('/manage-scholars/ocr-upload', [CoordinatorController::class, 'uploadOcr'])->name('coordinator.scholars.ocr-upload');
    Route::post('/manage-scholars/ocr-process', [CoordinatorController::class, 'processOcr'])->name('coordinator.scholars.ocr-process');
    Route::post('/manage-scholars/add-selected-ocr', [CoordinatorController::class, 'addSelectedOcrScholars'])->name('coordinator.scholars.add-selected-ocr');
    Route::get('/manage-scholars/confirm-add-ocr', [CoordinatorController::class, 'showConfirmAddOcr'])->name('coordinator.scholars.confirm-add-ocr');
    Route::post('/manage-scholars/confirm-add-ocr', [CoordinatorController::class, 'confirmAddOcrScholars'])->name('coordinator.scholars.confirm-add-ocr.post');

    // Filters (scholarship / batch)
    Route::get('/manage-scholars/scholarship/{scholarship}', [CoordinatorController::class, 'scholarsByScholarship'])->name('coordinator.scholars.by-scholarship');
    Route::get('/manage-scholars/scholarship/{scholarship}/batches', [CoordinatorController::class, 'batchesByScholarship'])->name('coordinator.scholars.batches');
    Route::get('/manage-scholars/batch/{batch}', [CoordinatorController::class, 'scholarsByBatch'])->name('coordinator.scholars.by-batch');

    // Enrollment Records (Coordinator)
    Route::get('/enrollment-records', [CoordinatorController::class, 'enrollmentRecords'])->name('coordinator.enrollment-records');
    Route::post('/enrollment-records/add', [CoordinatorController::class, 'addEnrollmentRecord'])->name('coordinator.enrollment-records.add');

    // Manage Scholarships
    Route::get('/manage-scholarships', [CoordinatorController::class, 'manageScholarships'])->name('coordinator.manage-scholarships');
    Route::get('/manage-scholarships/create', [CoordinatorController::class, 'createScholarship'])->name('coordinator.scholarships.create');
    Route::post('/manage-scholarships', [CoordinatorController::class, 'storeScholarship'])->name('coordinator.scholarships.store');
    Route::get('/manage-scholarships/{id}/edit', [CoordinatorController::class, 'editScholarship'])->name('coordinator.scholarships.edit');
    Route::put('/manage-scholarships/{id}', [CoordinatorController::class, 'updateScholarship'])->name('coordinator.scholarships.update');
    Route::delete('/manage-scholarships/{id}', [CoordinatorController::class, 'destroyScholarship'])->name('coordinator.scholarships.destroy');
    Route::get('/manage-scholarships/{id}/delete', [CoordinatorController::class, 'confirmDeleteScholarship'])->name('coordinator.scholarships.confirm-delete');

    // Scholarship Batches
    Route::get('/scholarship-batches', [CoordinatorController::class, 'manageScholarshipBatches'])->name('coordinator.scholarship-batches');
    Route::get('/scholarship-batches/create', [CoordinatorController::class, 'createScholarshipBatch'])->name('coordinator.scholarship-batches.create');
    Route::post('/scholarship-batches', [CoordinatorController::class, 'storeScholarshipBatch'])->name('coordinator.scholarship-batches.store');
    Route::get('/scholarship-batches/{id}/edit', [CoordinatorController::class, 'editScholarshipBatch'])->name('coordinator.scholarship-batches.edit');
    Route::put('/scholarship-batches/{id}', [CoordinatorController::class, 'updateScholarshipBatch'])->name('coordinator.scholarship-batches.update');
    Route::delete('/scholarship-batches/{id}', [CoordinatorController::class, 'destroyScholarshipBatch'])->name('coordinator.scholarship-batches.destroy');
    Route::get('/scholarship-batches/{id}/delete', [CoordinatorController::class, 'confirmDeleteScholarshipBatch'])->name('coordinator.scholarship-batches.confirm-delete');

    // Stipends
    Route::get('/manage-stipends', [CoordinatorController::class, 'manageStipends'])->name('coordinator.manage-stipends');
    Route::get('/manage-stipends/create', [CoordinatorController::class, 'createStipend'])->name('coordinator.stipends.create');
    Route::post('/manage-stipends', [CoordinatorController::class, 'storeStipend'])->name('coordinator.stipends.store');
    Route::get('/manage-stipends/{id}/edit', [CoordinatorController::class, 'editStipend'])->name('coordinator.stipends.edit');
    Route::put('/manage-stipends/{id}', [CoordinatorController::class, 'updateStipend'])->name('coordinator.stipends.update');
    Route::delete('/manage-stipends/{id}', [CoordinatorController::class, 'destroyStipend'])->name('coordinator.stipends.destroy');
    Route::get('/manage-stipends/{id}/delete', [CoordinatorController::class, 'confirmDeleteStipend'])->name('coordinator.stipends.confirm-delete');

    // Stipend Releases
    Route::get('/manage-stipend-releases', [CoordinatorController::class, 'manageStipendReleases'])->name('coordinator.manage-stipend-releases');
    Route::get('/manage-stipend-releases/create', [CoordinatorController::class, 'createStipendRelease'])->name('coordinator.stipend-releases.create');
    Route::post('/manage-stipend-releases', [CoordinatorController::class, 'storeStipendRelease'])->name('coordinator.stipend-releases.store');
    Route::get('/manage-stipend-releases/{id}/edit', [CoordinatorController::class, 'editStipendRelease'])->name('coordinator.stipend-releases.edit');
    Route::put('/manage-stipend-releases/{id}', [CoordinatorController::class, 'updateStipendRelease'])->name('coordinator.stipend-releases.update');
    Route::delete('/manage-stipend-releases/{id}', [CoordinatorController::class, 'destroyStipendRelease'])->name('coordinator.stipend-releases.destroy');
    Route::get('/manage-stipend-releases/{id}/delete', [CoordinatorController::class, 'confirmDeleteStipendRelease'])->name('coordinator.stipend-releases.confirm-delete');

    // Announcements
    Route::get('/manage-announcements', [CoordinatorController::class, 'manageAnnouncements'])->name('coordinator.manage-announcements');
    Route::get('/manage-announcements/create', [CoordinatorController::class, 'createAnnouncement'])->name('coordinator.announcements.create');
    Route::post('/manage-announcements', [CoordinatorController::class, 'storeAnnouncement'])->name('coordinator.announcements.store');
    

    // Clusters
    Route::get('/clusters', [QuestionClusterController::class, 'index'])->name('clusters.index');
    Route::get('/clusters/{cluster}', [QuestionClusterController::class, 'show'])->name('clusters.show');
    Route::post('/clusters/{cluster}/answer', [QuestionClusterController::class, 'answer'])->name('clusters.answer');

    // Reports
    Route::get('/reports', [CoordinatorController::class, 'reports'])->name('coordinator.reports');
});


    // Student routes (only Students can access)
    Route::middleware('role:Student')->prefix('student')->group(function () {
        Route::get('/dashboard', [StudentController::class, 'dashboard'])->name('student.dashboard');
        Route::get('/announcements', [StudentController::class, 'announcements'])->name('student.announcements');
        Route::get('/scholarships', [StudentController::class, 'scholarships'])->name('student.scholarships');
        Route::get('/stipend-history', [StudentController::class, 'stipendHistory'])->name('student.stipend-history');
        Route::get('/notifications', [StudentController::class, 'notifications'])->name('student.notifications');
        
        //Questions
        Route::get('/ask', [QuestionController::class, 'create'])->name('questions.create');
        Route::get('/my-questions', [QuestionController::class, 'myQuestions'])->name('questions.my');
        Route::post('/ask', [QuestionController::class, 'store'])->name('questions.store');
    });
});