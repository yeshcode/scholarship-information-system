<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;
use App\Models\Section; 
use App\Models\YearLevel;
use App\Models\College;
use App\Models\Course;
use App\Models\Semester;
use App\Models\Enrollment;
use App\Models\User;
use App\Models\UserType;
 // Add your model imports (e.g., YearLevel, etc.)

class SuperAdminController extends Controller
{
    public function dashboard()
    {
       

        $data = [];
        $page = request('page');
        
        if ($page === 'sections') {
            $data['sections'] = Section::with('course', 'yearLevel')->get();  // Load related data
            $data['courses'] = Course::all();  // For dropdowns
            $data['yearLevels'] = YearLevel::all();  // For dropdowns
        }

        elseif ($page === 'colleges') {
            $data['colleges'] = College::all();  // No relationships needed
        }

        elseif ($page === 'year-levels') {
            $data['yearLevels'] = YearLevel::all();  // No relationships needed
        }

        elseif ($page === 'user-type') {
            $data['userTypes'] = UserType::orderBy('id')->get();  // Added ->orderBy('id') for sequential display by ID
        }

        elseif ($page === 'courses') {
            $data['courses'] = Course::with('college')->get();  // Load related college data
            $data['colleges'] = College::all();  // For dropdowns in create/edit
        }

        elseif ($page === 'semesters') {
            $data['semesters'] = Semester::all();  // No relationships needed yet
        }

        // Update the 'enrollments' section in dashboard() to load more data
        elseif ($page === 'enrollments') {
            // Get the selected semester ID from the request (for filtering)
            $selectedSemesterId = request('semester_id');
        
            // Fetch all semesters for the dropdown (order by academic year descending for usability)
            $data['semesters'] = Semester::orderBy('academic_year', 'desc')->get();
        
            // Build the enrollments query with relationships
            $query = Enrollment::with('user', 'semester', 'section.course', 'course');
        
            // Apply semester filter if selected
            if ($selectedSemesterId) {
                $query->where('semester_id', $selectedSemesterId);
            }
        
            // Paginate the results (15 per page; adjust as needed)
            $data['enrollments'] = $query->paginate(15);
        
            // Pass the selected semester ID to the view (for pre-selecting the dropdown)
            $data['selectedSemesterId'] = $selectedSemesterId;
        
            // Existing data for other parts of the page (e.g., dropdowns in create/edit forms)
            $data['users'] = User::all();  // For user dropdown
            $data['sections'] = Section::with('course', 'yearLevel')->get();  // For section dropdown, with related data
            $data['courses'] = Course::all();  // Added for filters on enroll students page
            $data['yearLevels'] = YearLevel::all();  // Added for filters on enroll students page
        }

        elseif ($page === 'manage-users') {
            try {
                // Paginate users (15 per page) for performance
                $data['users'] = User::with('userType', 'college', 'yearLevel', 'section')->paginate(15);
                $data['userTypes'] = UserType::all();
                $data['colleges'] = College::all();
                $data['yearLevels'] = YearLevel::all();
                $data['sections'] = Section::with('course', 'yearLevel')->get();
            } catch (\Exception $e) {
                $data['error'] = 'Database error: ' . $e->getMessage();
            }
        }
        // Add for other pages later
        
        return view('super-admin.dashboard', $data);
    }

    
    
    // Section CRUD Methods
    public function createSection()
    {
        $courses = Course::all();
        $yearLevels = YearLevel::all();
        return view('super-admin.sections-create', compact('courses', 'yearLevels'));
    }

    public function editSection($id)
    {
        $section = Section::findOrFail($id);
        $courses = Course::all();  // For dropdown
        $yearLevels = YearLevel::all();  // For dropdown
        return view('super-admin.sections-edit', compact('section', 'courses', 'yearLevels'));
    }

    public function storeSection(Request $request)
    {
        $request->validate([
            'course_id' => 'required|exists:courses,id',
            'year_level_id' => 'required|exists:year_levels,id',
            'section_name' => 'required|string|max:255',
        ]);
        
        Section::create($request->only(['course_id', 'year_level_id', 'section_name']));
        return redirect()->route('admin.dashboard', ['page' => 'sections'])->with('success', 'Section added successfully!');
    }
    
    public function updateSection(Request $request, $id)
    {
        $request->validate([
            'course_id' => 'required|exists:courses,id',
            'year_level_id' => 'required|exists:year_levels,id',
            'section_name' => 'required|string|max:255',
        ]);
        
        $section = Section::findOrFail($id);
        $section->update($request->only(['course_id', 'year_level_id', 'section_name']));
        return redirect()->route('admin.dashboard', ['page' => 'sections'])->with('success', 'Section updated successfully!');
    }
    
    public function destroySection($id)
    {
        Section::findOrFail($id)->delete();
        return redirect()->route('admin.dashboard', ['page' => 'sections'])->with('success', 'Section deleted successfully!');
    }

    // In app/Http/Controllers/SectionController.php
    public function delete($id)
    {
        $section = Section::findOrFail($id);  // Assuming Section model
        return view('super-admin.sections-delete', compact('section'));
    }

    // Colleges CRUD
public function createCollege()
{
    return view('super-admin.college-create');
}

public function storeCollege(Request $request)
{
    $request->validate(['college_name' => 'required|string|max:255']);
    College::create($request->only('college_name'));
    return redirect()->route('admin.dashboard', ['page' => 'colleges'])->with('success', 'College added!');
}

public function updateCollege(Request $request, $id)
{
    $request->validate(['college_name' => 'required|string|max:255']);
    $college = College::findOrFail($id);
    $college->update($request->only('college_name'));
    return redirect()->route('admin.dashboard', ['page' => 'colleges'])->with('success', 'College updated!');
}

public function destroyCollege($id)
{
    College::findOrFail($id)->delete();
    return redirect()->route('admin.dashboard', ['page' => 'colleges'])->with('success', 'College deleted!');
}

public function editCollege($id)
{
    $college = College::findOrFail($id);
    return view('super-admin.colleges-edit', compact('college'));
}

// Add this for the delete confirmation page
public function deleteCollege($id)
{
    $college = College::findOrFail($id);
    return view('super-admin.colleges-delete', compact('college'));
}

// Year Levels CRUD
public function createYearLevel()
{
    return view('super-admin.year-levels-create');
}

public function storeYearLevel(Request $request)
{
    $request->validate(['year_level_name' => 'required|string|max:255']);
    YearLevel::create($request->only('year_level_name'));
    return redirect()->route('admin.dashboard', ['page' => 'year-levels'])->with('success', 'Year Level added!');
}

public function updateYearLevel(Request $request, $id)
{
    $request->validate(['year_level_name' => 'required|string|max:255']);
    $yearLevel = YearLevel::findOrFail($id);
    $yearLevel->update($request->only('year_level_name'));
    return redirect()->route('admin.dashboard', ['page' => 'year-levels'])->with('success', 'Year Level updated!');
}

public function destroyYearLevel($id)
{
    YearLevel::findOrFail($id)->delete();
    return redirect()->route('admin.dashboard', ['page' => 'year-levels'])->with('success', 'Year Level deleted!');
}

public function editYearLevel($id)
{
    $yearLevel = YearLevel::findOrFail($id);
    return view('super-admin.year-levels-edit', compact('yearLevel'));
}

// Add this for the delete confirmation page
public function deleteYearLevel($id)
{
    $yearLevel = YearLevel::findOrFail($id);
    return view('super-admin.year-levels-delete', compact('yearLevel'));
}

// User Types CRUD
public function createUserType()
{
    return view('super-admin.user-types-create');
}

public function storeUserType(Request $request)
{
    $request->validate([
        'name' => 'required|string|max:255',
        'description' => 'nullable|string|max:500',
        'dashboard_url' => 'nullable|string|max:255',
    ]);
    
    // Create the user_type
    $userType = UserType::create($request->only(['name', 'description', 'dashboard_url']));
    
    // Also create a matching Spatie role (if it doesn't exist)
    \Spatie\Permission\Models\Role::firstOrCreate(['name' => $userType->name]);
    
    return redirect()->route('admin.dashboard', ['page' => 'user-type'])->with('success', 'User Type and Role added!');
}

public function updateUserType(Request $request, $id)
{
    $request->validate([
        'name' => 'required|string|max:255',
        'description' => 'nullable|string|max:500',
        'dashboard_url' => 'nullable|string|max:255',
    ]);
    $userType = UserType::findOrFail($id);
    $userType->update($request->only(['name', 'description', 'dashboard_url']));

     // Update or create the Spatie role
     \Spatie\Permission\Models\Role::updateOrCreate(
        ['name' => $userType->name],  // Match by name
        ['name' => $userType->name]   // Ensure it exists
    );

    return redirect()->route('admin.dashboard', ['page' => 'user-type'])->with('success', 'User Type updated!');
}

public function destroyUserType($id)
{
    UserType::findOrFail($id)->delete();
    return redirect()->route('admin.dashboard', ['page' => 'user-type'])->with('success', 'User Type deleted!');
}

public function editUserType($id)
{
    $userType = UserType::findOrFail($id);
    return view('super-admin.user-types-edit', compact('userType'));
}

public function deleteUserType($id)
{
    $userType = UserType::findOrFail($id);
    return view('super-admin.user-types-delete', compact('userType'));
}

// Courses CRUD Methods
public function createCourse()
{
    $colleges = College::all();
    return view('super-admin.courses-create', compact('colleges'));
}

public function editCourse($id)
{
    $course = Course::findOrFail($id);
    $colleges = College::all();  // For dropdown
    return view('super-admin.courses-edit', compact('course', 'colleges'));
}

public function storeCourse(Request $request)
{
    $request->validate([
        'course_name' => 'required|string|max:255',
        'course_description' => 'nullable|string|max:500',  // Added validation (optional)
        'college_id' => 'required|exists:colleges,id',
    ]);
    
    Course::create($request->only(['course_name', 'course_description', 'college_id']));  // Added course_description
    return redirect()->route('admin.dashboard', ['page' => 'courses'])->with('success', 'Course added successfully!');
}

public function updateCourse(Request $request, $id)
{
    $request->validate([
        'course_name' => 'required|string|max:255',
        'course_description' => 'nullable|string|max:500',  // Added validation (optional)
        'college_id' => 'required|exists:colleges,id',
    ]);
    
    $course = Course::findOrFail($id);
    $course->update($request->only(['course_name', 'course_description', 'college_id']));  // Added course_description
    return redirect()->route('admin.dashboard', ['page' => 'courses'])->with('success', 'Course updated successfully!');
}

public function destroyCourse($id)
{
    Course::findOrFail($id)->delete();
    return redirect()->route('admin.dashboard', ['page' => 'courses'])->with('success', 'Course deleted successfully!');
}

public function deleteCourse($id)
{
    $course = Course::findOrFail($id);
    return view('super-admin.courses-delete', compact('course'));
}

// Semesters CRUD Methods
public function createSemester()
{
    return view('super-admin.semesters-create');
}

public function editSemester($id)
{
    $semester = Semester::findOrFail($id);
    return view('super-admin.semesters-edit', compact('semester'));
}

public function storeSemester(Request $request)
{
    $request->validate([
        'term' => 'required|string|max:255',
        'academic_year' => 'required|string|max:255',
        'start_date' => 'required|date',
        'end_date' => 'required|date|after:start_date',
        'is_current' => 'boolean',  // Optional, defaults to false
    ]);
    
    Semester::create($request->only(['term', 'academic_year', 'start_date', 'end_date', 'is_current']));
    return redirect()->route('admin.dashboard', ['page' => 'semesters'])->with('success', 'Semester added successfully!');
}

public function updateSemester(Request $request, $id)
{
    $request->validate([
        'term' => 'required|string|max:255',
        'academic_year' => 'required|string|max:255',
        'start_date' => 'required|date',
        'end_date' => 'required|date|after:start_date',
        'is_current' => 'boolean',  // Optional, defaults to false
    ]);
    
    $semester = Semester::findOrFail($id);
    $semester->update($request->only(['term', 'academic_year', 'start_date', 'end_date', 'is_current']));
    return redirect()->route('admin.dashboard', ['page' => 'semesters'])->with('success', 'Semester updated successfully!');
}

public function destroySemester($id)
{
    Semester::findOrFail($id)->delete();
    return redirect()->route('admin.dashboard', ['page' => 'semesters'])->with('success', 'Semester deleted successfully!');
}

public function deleteSemester($id)
{
    $semester = Semester::findOrFail($id);
    return view('super-admin.semesters-delete', compact('semester'));
}


// Enrollments CRUD Methods

public function enrollments(Request $request)
{
    // Get the selected semester ID from the request (for filtering)
    $selectedSemesterId = $request->input('semester_id');

    // Fetch all semesters for the dropdown (order by academic year descending for usability)
    $semesters = Semester::orderBy('academic_year', 'desc')->get();

    // Build the enrollments query with relationships
    $query = Enrollment::with('user', 'semester', 'section.course', 'course');

    // Apply semester filter if selected
    if ($selectedSemesterId) {
        $query->where('semester_id', $selectedSemesterId);
    }

    // Paginate the results (15 per page; adjust as needed)
    $enrollments = $query->paginate(15);

    // Existing data for other parts of the page (e.g., dropdowns in create/edit forms)
    $users = User::all();  // For user dropdown
    $sections = Section::with('course', 'yearLevel')->get();  // For section dropdown, with related data
    $courses = Course::all();  // Added for filters on enroll students page
    $yearLevels = YearLevel::all();  // Added for filters on enroll students page

    // Return the view with all data
    return view('super-admin.enrollments', compact('enrollments', 'semesters', 'selectedSemesterId', 'users', 'sections', 'courses', 'yearLevels'));
}
public function createEnrollment()
{
    $users = User::all();
    $semesters = Semester::all();
    $sections = Section::with('course', 'yearLevel')->get();
    $courses = Course::all();  // Add this line to load courses
    return view('super-admin.enrollments-create', compact('users', 'semesters', 'sections', 'courses'));  // Add 'courses' to compact
}

public function editEnrollment($id)
{
    $enrollment = Enrollment::findOrFail($id);
    $users = User::all();
    $semesters = Semester::all();
    $sections = Section::with('course', 'yearLevel')->get();
    $courses = Course::all();  // Add this line
    return view('super-admin.enrollments-edit', compact('enrollment', 'users', 'semesters', 'sections', 'courses'));  // Add 'courses' to compact
}

public function storeEnrollment(Request $request)
{
    $request->validate([
        'user_id' => 'required|exists:users,id',
        'semester_id' => 'required|exists:semesters,id',
        'section_id' => 'required|exists:sections,id',
        'course_id' => 'required|exists:courses,id', 
        'status' => 'required|in:enrolled,graduated,not_enrolled',  // Restrict to valid enum values (adjust if different)
    ]);
    
    Enrollment::create([
        'user_id' => $request->user_id,
        'semester_id' => $request->semester_id,
        'section_id' => $request->section_id,
        'course_id' => $request->course_id,
        'status' => strtolower($request->status),  // Ensure lowercase for enum
    ]);
    return redirect()->route('admin.dashboard', ['page' => 'enrollments'])->with('success', 'Enrollment added successfully!');
}

public function updateEnrollment(Request $request, $id)
{
    $request->validate([
        'user_id' => 'required|exists:users,id',
        'semester_id' => 'required|exists:semesters,id',
        'section_id' => 'required|exists:sections,id',
        'course_id' => 'required|exists:courses,id', 
        'status' => 'required|in:enrolled,graduated,not_enrolled',
    ]);
    
    $enrollment = Enrollment::findOrFail($id);
    $enrollment->update([
        'user_id' => $request->user_id,
        'semester_id' => $request->semester_id,
        'section_id' => $request->section_id,
        'course_id' => $request->course_id,
        'status' => strtolower($request->status),
    ]);
    return redirect()->route('admin.dashboard', ['page' => 'enrollments'])->with('success', 'Enrollment updated successfully!');
}

public function destroyEnrollment($id)
{
    Enrollment::findOrFail($id)->delete();
    return redirect()->route('admin.dashboard', ['page' => 'enrollments'])->with('success', 'Enrollment deleted successfully!');
}

// Add these new methods at the end of the class (before the closing })
public function enrollStudents(Request $request)
{
    $search = $request->get('search');  // Get the search term

    $query = User::where('user_type_id', 3)  // Assuming 3 is Student; adjust if needed
        ->with('college', 'yearLevel', 'section.course');

    if ($search) {
        // Search across section name, course name, or year level name
        $query->where(function ($q) use ($search) {
            $q->whereHas('section', function ($subQ) use ($search) {
                $subQ->where('section_name', 'LIKE', '%' . $search . '%')
                     ->orWhereHas('course', function ($courseQ) use ($search) {
                         $courseQ->where('course_name', 'LIKE', '%' . $search . '%');
                     });
            })
            ->orWhereHas('yearLevel', function ($yearQ) use ($search) {
                $yearQ->where('year_level_name', 'LIKE', '%' . $search . '%');
            });
        });
    }

    $students = $query->paginate(10);  // 10 per page
    $semesters = Semester::all();
    $sections = Section::with('course', 'yearLevel')->get();
    $courses = Course::all();
    $yearLevels = YearLevel::all();

    return view('super-admin.enroll-students', compact('students', 'semesters', 'sections', 'courses', 'yearLevels', 'request'));
}

// Add this method right after enrollStudents
public function storeEnrollStudents(Request $request)
{
    $request->validate([
        'selected_users' => 'required|array|min:1',
        'semester_id' => 'required|exists:semesters,id',
        'section_id' => 'nullable|exists:sections,id',
        'course_id' => 'required|exists:courses,id',
    ]);

    $selectedUserIds = $request->selected_users;
    $semesterId = $request->semester_id;
    $sectionId = $request->section_id;
    $courseId = $request->course_id;

    $enrolledCount = 0;
    foreach ($selectedUserIds as $userId) {
        // Update or create enrollment: If student is already enrolled in this semester, update; else create
        Enrollment::updateOrCreate(
            ['user_id' => $userId, 'semester_id' => $semesterId],
            ['section_id' => $sectionId, 'course_id' => $courseId, 'status' => 'enrolled']  // Changed from 'active' to 'enrolled'
        );
        $enrolledCount++;
    }

    return redirect()->route('admin.dashboard', ['page' => 'enrollments'])->with('success', "$enrolledCount students enrolled in the selected semester.");
}

public function deleteEnrollment($id)
{
    $enrollment = Enrollment::findOrFail($id);
    return view('super-admin.enrollments-delete', compact('enrollment'));
}


// Users CRUD Methods
public function createUser()
{
    $userTypes = UserType::all();
    $colleges = College::all();
    $yearLevels = YearLevel::all();
    $sections = Section::with('course', 'yearLevel')->get();
    
    $studentUserType = UserType::where('name', 'Student')->first();
    $studentUserTypeId = $studentUserType ? $studentUserType->id : null;
    
    return view('super-admin.users-create', compact('userTypes', 'colleges', 'yearLevels', 'sections', 'studentUserTypeId'));
}

public function editUser($id)
{
    $user = User::findOrFail($id);
    $userTypes = UserType::all();
    $colleges = College::all();
    $yearLevels = YearLevel::all();
    $sections = Section::with('course', 'yearLevel')->get();
    return view('super-admin.users-edit', compact('user', 'userTypes', 'colleges', 'yearLevels', 'sections'));
}

public function storeUser(Request $request)
{
    // Dynamically get the "Student" user type
    $studentUserType = \App\Models\UserType::where('name', 'Student')->first();
    $studentUserTypeId = $studentUserType ? $studentUserType->id : null;
    
    $isStudent = $request->user_type_id == $studentUserTypeId;
    
    // Validation rules (conditional for students)
    $rules = [
        'user_id' => 'required|string|unique:users,user_id',
        'bisu_email' => 'required|email|unique:users,bisu_email',
        'firstname' => 'required|string|max:255',
        'lastname' => 'required|string|max:255',
        'middlename' => 'nullable|string|max:255',
        'contact_no' => 'required|string|max:255',
        'student_id' => $isStudent ? 'required|string|max:255' : 'nullable|string|max:255',  // Required for students (for password)
        'user_type_id' => 'required|exists:user_types,id',
        'college_id' => $isStudent ? 'required|exists:colleges,id' : 'nullable|exists:colleges,id',
        'year_level_id' => $isStudent ? 'required|exists:year_levels,id' : 'nullable|exists:year_levels,id',
        'section_id' => $isStudent ? 'required|exists:sections,id' : 'nullable|exists:sections,id',
        'password' => $isStudent ? 'nullable|string|min:8' : 'required|string|min:8',  // Ignored for students
    ];
    
    $request->validate($rules);
    
    // NEW: Ensure student_id is provided for students (prevents empty password)
    if ($isStudent && empty($request->student_id)) {
        return redirect()->back()->with('error', 'Student ID is required for students.');
    }
    
    try {
        // UPDATED: For students, ALWAYS use student_id as password (no fallback). For others, use manual password.
        $password = $isStudent ? $request->student_id : $request->password;
        
        if (!$password) {
            return redirect()->back()->with('error', 'Password is required. For students, ensure Student ID is provided.');
        }

        // Temporary debug: Uncomment to check values (remove after testing)
//         dd([
//             'isStudent' => $isStudent,
//             'request->password' => $request->password,
//             'request->student_id' => $request->student_id,
//             'final password' => $password,
//         ]);
        
        $user = User::create([
            'user_id' => $request->user_id,
            'bisu_email' => $request->bisu_email,
            'firstname' => $request->firstname,
            'lastname' => $request->lastname,
            'middlename' => $request->middlename,
            'contact_no' => $request->contact_no,
            'student_id' => $request->student_id,
            'user_type_id' => $request->user_type_id,
            'college_id' => $request->college_id,
            'year_level_id' => $request->year_level_id,
            'section_id' => $request->section_id,
            'password' => bcrypt($password),  // Hash it (student_id for students)
            'status' => 'active',
        ]);
        
        // Assign role
        if ($user->userType) {
            $roleName = $user->userType->name;
            if (\Spatie\Permission\Models\Role::where('name', $roleName)->exists()) {
                $user->assignRole($roleName);
            } else {
                Log::warning("Role '$roleName' does not exist for user {$user->id}");
            }
        }
        
        return redirect()->route('admin.dashboard', ['page' => 'manage-users'])->with('success', 'User added successfully!');
    } catch (\Exception $e) {
        Log::error('User creation failed: ' . $e->getMessage());
        return redirect()->back()->with('error', 'Failed to add user: ' . $e->getMessage());
    }
}

public function updateUser(Request $request, $id)
{
    $request->validate([
        'user_id' => 'required|string|unique:users,user_id,' . $id,
        'bisu_email' => 'required|email|unique:users,bisu_email,' . $id,
        'firstname' => 'required|string|max:255',
        'lastname' => 'required|string|max:255',
        'contact_no' => 'required|string|max:255',
        'student_id' => 'nullable|string|max:255',
        'user_type_id' => 'required|exists:user_types,id',
        'college_id' => 'nullable|exists:colleges,id',
        'year_level_id' => 'nullable|exists:year_levels,id',
        'section_id' => 'nullable|exists:sections,id',
    ]);
    
    $user = User::findOrFail($id);
    $user->update($request->except('password'));  // Don't update password unless provided
    return redirect()->route('admin.dashboard', ['page' => 'manage-users'])->with('success', 'User updated successfully!');
}

public function destroyUser($id)
{
    User::findOrFail($id)->delete();
    return redirect()->route('admin.dashboard', ['page' => 'manage-users'])->with('success', 'User deleted successfully!');
}

public function showBulkUploadForm()
{
    $colleges = College::all();
    $yearLevels = YearLevel::all();
    $sections = Section::with('course', 'yearLevel')->get();
    return view('super-admin.users-bulk-upload', compact('colleges', 'yearLevels', 'sections'));
}

// Bulk Upload for Students (Updated for Dynamic CSV)
public function bulkUploadUsers(Request $request)
{
    $request->validate([
        'csv_file' => 'required|file|mimes:csv,txt',
        'college_id' => 'required|exists:colleges,id',
        'year_level_id' => 'required|exists:year_levels,id',
        'section_id' => 'required|exists:sections,id',
    ]);

    $file = $request->file('csv_file');
    $data = array_map('str_getcsv', file($file->getRealPath()));
    $headers = array_shift($data);

    $studentTypeId = UserType::where('name', 'Student')->first()->id ?? null;
    if (!$studentTypeId) {
        return back()->with('error', 'Student user type not found. Please add it first.');
    }

    $fillable = (new User)->getFillable();
    $errors = [];

    foreach ($data as $row) {
        $userData = [
            'user_type_id' => $studentTypeId,
            'college_id' => $request->college_id,
            'year_level_id' => $request->year_level_id,
            'section_id' => $request->section_id,
            // Remove the default password here; we'll set it conditionally below
            'status' => 'active',
        ];

        foreach ($headers as $index => $header) {
            $header = trim(strtolower($header));
            if (in_array($header, $fillable) && isset($row[$index])) {
                $userData[$header] = trim($row[$index]);
            }
        }

        if (empty($userData['firstname']) || empty($userData['lastname']) || empty($userData['bisu_email'])) {
            $errors[] = 'Row skipped: Missing required fields.';
            continue;
        }

        // NEW: Set password based on user type
        if ($studentTypeId == $userData['user_type_id'] && !empty($userData['student_id'])) {
            // For students, hash the student_id as the default password
            $userData['password'] = \Illuminate\Support\Facades\Hash::make($userData['student_id']);
        } else {
            // For non-students, use a default hashed password
            $userData['password'] = bcrypt('password123');
        }

        try {
            $user = User::create($userData);  // Create the user
            
            // NEW: Assign Spatie role after creation
            $user->load('userType');  // Load the relationship
            if ($user->userType) {
                $user->assignRole($user->userType->name);
            }
        } catch (\Exception $e) {
            $errors[] = 'Row skipped: ' . $e->getMessage();
        }
    }

    $message = 'Bulk upload completed! Students registered (enroll them separately via Enrollments page).';
    if (!empty($errors)) {
        $message .= ' Errors: ' . implode('; ', $errors);
    }

    return redirect()->route('admin.dashboard', ['page' => 'manage-users'])->with('success', $message);
}

// Add this for the delete confirmation page
public function deleteUser($id)
{
    $user = User::findOrFail($id);
    return view('super-admin.users-delete', compact('user'));
}
    // Add similar methods for other tables (e.g., createYearLevel, storeYearLevel, etc.)
}