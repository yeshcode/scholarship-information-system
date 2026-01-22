<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;
use App\Models\YearLevel;
use App\Models\College;
use App\Models\Course;
use App\Models\Semester;
use App\Models\Enrollment;
use App\Models\User;
use App\Models\UserType;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Response;

 // Add your model imports (e.g., YearLevel, etc.)

class SuperAdminController extends Controller
{
    private function buildDashboardData(): array
{
    $data = [];

    // 1) Students added per year (created_at year, "Student" user type)
    $studentsByYear = User::whereHas('userType', function ($q) {
            $q->where('name', 'Student');
        })
        ->select(
            DB::raw("EXTRACT(YEAR FROM created_at) as year"),
            DB::raw("COUNT(*) as total")
        )
        ->groupBy(DB::raw("EXTRACT(YEAR FROM created_at)"))
        ->orderBy('year')
        ->get();

    $data['studentYearLabels'] = $studentsByYear->pluck('year');   // e.g. [2019, 2020, 2021]
    $data['studentYearCounts'] = $studentsByYear->pluck('total');  // counts per year

    // 2) Enrollments per course (for pie chart)
    $enrollmentByCourse = Enrollment::select('course_id', DB::raw('COUNT(*) as total'))
        ->with('course') // assumes Enrollment has course()
        ->groupBy('course_id')
        ->get();

    $data['coursePieLabels'] = $enrollmentByCourse->map(function ($row) {
        return $row->course ? $row->course->course_name : 'Unknown Course';
    });

    $data['coursePieCounts'] = $enrollmentByCourse->pluck('total');

    // 3) Enrollments per course per semester (for stacked bar)
    $enrollmentBySemesterCourse = Enrollment::select(
            'semester_id',
            'course_id',
            DB::raw('COUNT(*) as total')
        )
        ->with(['semester', 'course'])
        ->groupBy('semester_id', 'course_id')
        ->get();

    // Build maps: semester_id -> label, course_id -> name
    $semesterMap = [];
    $courseMap = [];

    foreach ($enrollmentBySemesterCourse as $row) {
        if (!isset($semesterMap[$row->semester_id])) {
            if ($row->semester) {
                $semesterMap[$row->semester_id] = $row->semester->term . ' ' . $row->semester->academic_year;
            } else {
                $semesterMap[$row->semester_id] = 'Unknown Semester';
            }
        }

        if (!isset($courseMap[$row->course_id])) {
            $courseMap[$row->course_id] = $row->course ? $row->course->course_name : 'Unknown Course';
        }
    }

    // Sort semesters and courses by label for stable order
    $semesterIds = array_keys($semesterMap);
    $courseIds   = array_keys($courseMap);

    usort($semesterIds, function ($a, $b) use ($semesterMap) {
        return strcmp($semesterMap[$a], $semesterMap[$b]);
    });

    usort($courseIds, function ($a, $b) use ($courseMap) {
        return strcmp($courseMap[$a], $courseMap[$b]);
    });

    // Build lookup [course_id][semester_id] = total
    $lookup = [];
    foreach ($enrollmentBySemesterCourse as $row) {
        $lookup[$row->course_id][$row->semester_id] = $row->total;
    }

    // Build matrix: for each course → array of counts per semester
    $matrix = [];
    foreach ($courseIds as $courseId) {
        $rowData = [];
        foreach ($semesterIds as $semesterId) {
            $rowData[] = $lookup[$courseId][$semesterId] ?? 0;
        }
        $matrix[] = $rowData;
    }

    $data['semCourseLabels']      = array_map(fn($id) => $semesterMap[$id], $semesterIds);  // x-axis labels
    $data['semCourseCourseNames'] = array_map(fn($id) => $courseMap[$id], $courseIds);      // dataset labels
    $data['semCourseMatrix']      = $matrix;                                                // [courseIndex][semIndex]

    return $data;
}

public function dashboardData()
{
    // You can add auth/permission checks if needed, but route is already in Super Admin group
    $data = $this->buildDashboardData();
    return response()->json($data);
}


    public function dashboard()
    {
       

        $data = [];
        $page = request('page');

            
        // MAIN DASHBOARD (no ?page parameter)
    if (!$page) {
        // get dashboard-only data
        $dashboardData = $this->buildDashboardData();
        $data = array_merge($data, $dashboardData);
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

        // ENROLLMENTS PAGE (inside dashboard: ?page=enrollments)
        elseif ($page === 'enrollments') {
            $selectedSemesterId = request('semester_id');

            $data['semesters'] = Semester::orderBy('academic_year', 'desc')->get();

            // ✅ NO section.course anymore
            $query = Enrollment::with(['user', 'semester', 'course']);

            if ($selectedSemesterId) {
                $query->where('semester_id', $selectedSemesterId);
            }

            $data['enrollments'] = $query
                ->orderByDesc('id')
                ->paginate(15, ['*'], 'enrollments_page');

            $data['selectedSemesterId'] = $selectedSemesterId;

            // ✅ dropdowns
            $data['users']      = User::all();
            $data['courses']    = Course::all();
            $data['yearLevels'] = YearLevel::all(); // keep if you need in UI
        }

       elseif ($page === 'manage-users') {
            try {
                $search       = request('search');
                $collegeId    = request('college_id');
                $yearLevelId  = request('year_level_id');
                $courseId     = request('course_id'); // ✅ direct course_id now

                // ✅ Base query (no section)
                $query = User::with('userType', 'college', 'yearLevel', 'course');

                // ✅ TEXT SEARCH
                if ($search) {
                    $search = trim($search);

                    $query->where(function ($q) use ($search) {
                        $q->where('firstname', 'like', "%{$search}%")
                        ->orWhere('lastname', 'like', "%{$search}%")
                        ->orWhere('bisu_email', 'like', "%{$search}%")
                        ->orWhere('status', 'like', "%{$search}%")

                        ->orWhereHas('college', function ($sub) use ($search) {
                            $sub->where('college_name', 'like', "%{$search}%");
                        })

                        ->orWhereHas('yearLevel', function ($sub) use ($search) {
                            $sub->where('year_level_name', 'like', "%{$search}%");
                        })

                        // ✅ course direct
                        ->orWhereHas('course', function ($sub) use ($search) {
                            $sub->where('course_name', 'like', "%{$search}%");
                        });
                    });
                }

                if (!empty($collegeId)) {
                    $query->where('college_id', $collegeId);
                }

                if (!empty($yearLevelId)) {
                    $query->where('year_level_id', $yearLevelId);
                }

                // ✅ course filter direct
                if (!empty($courseId)) {
                    $query->where('course_id', $courseId);
                }

                $data['users'] = $query
                    ->orderBy('lastname')
                    ->orderBy('firstname')
                    ->paginate(15, ['*'], 'users_page');

                $data['userTypes']  = UserType::all();
                $data['colleges']   = College::all();
                $data['yearLevels'] = YearLevel::all();
                $data['courses']    = Course::all();

                $data['selectedCollegeId']   = $collegeId;
                $data['selectedYearLevelId'] = $yearLevelId;
                $data['selectedCourseId']    = $courseId;

            } catch (\Exception $e) {
                $data['error'] = 'Database error: ' . $e->getMessage();
            }
        }

        // Add for other pages later
        
        return view('super-admin.dashboard', $data);
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
    $query = Enrollment::with('user', 'semester', 'course');

    // Apply semester filter if selected
    if ($selectedSemesterId) {
        $query->where('semester_id', $selectedSemesterId);
    }

    // Paginate the results (15 per page; adjust as needed)
    $enrollments = $query->paginate(15);

    // Existing data for other parts of the page (e.g., dropdowns in create/edit forms)
    $users = User::all();  // For user dropdown
    $courses = Course::all();  // Added for filters on enroll students page
    $yearLevels = YearLevel::all();  // Added for filters on enroll students page

    // Return the view with all data
    return view('super-admin.enrollments', compact('enrollments', 'semesters', 'selectedSemesterId', 'users', 'sections', 'courses', 'yearLevels'));
}
public function createEnrollment()
{
    $users = User::all();
    $semesters = Semester::all();
    $courses = Course::all();
    return view('super-admin.enrollments-create', compact('users', 'semesters', 'courses'));
}

public function editEnrollment($id)
{
    $enrollment = Enrollment::findOrFail($id);
    $users = User::all();
    $semesters = Semester::all();
    $courses = Course::all();
    return view('super-admin.enrollments-create', compact('users', 'semesters', 'courses'));
}

public function storeEnrollment(Request $request)
{
    $request->validate([
        'user_id' => 'required|exists:users,id',
        'semester_id' => 'required|exists:semesters,id',
        'course_id' => 'required|exists:courses,id',
        'status' => 'required|in:enrolled,graduated,not_enrolled',
    ]);

    Enrollment::create([
        'user_id' => $request->user_id,
        'semester_id' => $request->semester_id,
        'course_id' => $request->course_id,
        'status' => strtolower($request->status),
    ]);

    return redirect()->route('admin.dashboard', ['page' => 'enrollments'])->with('success', 'Enrollment added successfully!');
}

public function updateEnrollment(Request $request, $id)
{
    $request->validate([
        'user_id' => 'required|exists:users,id',
        'semester_id' => 'required|exists:semesters,id',
        'course_id' => 'required|exists:courses,id',
        'status' => 'required|in:enrolled,graduated,not_enrolled',
    ]);
    
    $enrollment = Enrollment::findOrFail($id);
    $enrollment->update([
        'user_id' => $request->user_id,
        'semester_id' => $request->semester_id,
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
        ->with('college', 'yearLevel', 'course');

    if ($search) {
    $query->where(function ($q) use ($search) {
        $q->whereHas('course', function ($courseQ) use ($search) {
            $courseQ->where('course_name', 'LIKE', '%' . $search . '%');
        })
        ->orWhereHas('yearLevel', function ($yearQ) use ($search) {
            $yearQ->where('year_level_name', 'LIKE', '%' . $search . '%');
        })
        ->orWhere('firstname', 'LIKE', '%' . $search . '%')
        ->orWhere('lastname', 'LIKE', '%' . $search . '%')
        ->orWhere('bisu_email', 'LIKE', '%' . $search . '%');
    });
}


    $students = $query->paginate(10);  // 10 per page
    $semesters = Semester::all();
    $courses = Course::all();
    $yearLevels = YearLevel::all();

    return view('super-admin.enroll-students', compact('students', 'semesters', 'courses', 'yearLevels', 'request'));
}

// Add this method right after enrollStudents
public function storeEnrollStudents(Request $request)
{
    $request->validate([
        'selected_users' => 'required|array|min:1',
        'semester_id' => 'required|exists:semesters,id',
        'course_id' => 'required|exists:courses,id',
    ]);

    $selectedUserIds = $request->selected_users;
    $semesterId = $request->semester_id;
    $courseId = $request->course_id;

    $enrolledCount = 0;
    foreach ($selectedUserIds as $userId) {
        // Update or create enrollment: If student is already enrolled in this semester, update; else create
        Enrollment::updateOrCreate(
            ['user_id' => $userId, 'semester_id' => $semesterId],
            ['course_id' => $courseId, 'status' => 'enrolled']
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

// --- ENROLLMENT RECORDS (BY ACADEMIC YEAR) ---

public function enrollmentRecords()
{
    // Get distinct academic years from semesters table
    $academicYears = Semester::select('academic_year')
        ->distinct()
        ->orderBy('academic_year', 'desc')
        ->get()
        ->pluck('academic_year');

    return view('super-admin.enrollment-records', compact('academicYears'));
}

public function enrollmentRecordsByYear($academicYear)
{
    // Get all enrollments where the semester has this academic year
    $enrollments = Enrollment::with('user', 'semester', 'course')
        ->whereHas('semester', function ($q) use ($academicYear) {
            $q->where('academic_year', $academicYear);
        })
        ->orderByDesc('id')
        ->paginate(15);

    return view('super-admin.enrollment-records-year', compact('enrollments', 'academicYear'));
}



// Users CRUD Methods
public function createUser()
{
    $userTypes = UserType::all();
    $colleges = College::all();
    $yearLevels = YearLevel::all();
    $courses = Course::all();

    $studentUserType = UserType::where('name', 'Student')->first();
    $studentUserTypeId = $studentUserType ? $studentUserType->id : null;
    
    return view('super-admin.users-create', compact('userTypes', 'colleges', 'yearLevels', 'courses', 'studentUserTypeId'));
}

public function editUser($id)
{
    $user = User::findOrFail($id);
    $userTypes = UserType::all();
    $colleges = College::all();
    $yearLevels = YearLevel::all();
    $courses = Course::all();
    return view('super-admin.users-edit', compact('user', 'userTypes', 'colleges', 'yearLevels', 'courses'));
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
        'course_id' => $isStudent ? 'required|exists:courses,id' : 'nullable|exists:courses,id',
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
            'course_id' => $request->course_id,
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
        'course_id' => 'nullable|exists:courses,id',
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
    $courses = Course::all();
    return view('super-admin.users-bulk-upload', compact('colleges', 'yearLevels', 'courses'));
}

// Bulk Upload for Students (Updated for Dynamic CSV)
public function bulkUploadUsers(Request $request)
{
    $request->validate([
        'csv_file' => 'required|file|mimes:csv,txt',
        'college_id' => 'required|exists:colleges,id',
        'year_level_id' => 'required|exists:year_levels,id',
        'course_id' => $request->course_id,
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
            'course_id' => $request->course_id,
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