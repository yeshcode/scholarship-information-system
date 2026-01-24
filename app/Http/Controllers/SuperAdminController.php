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
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\StudentsBulkImport;
 


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

//the users bulk upload helpers

private function norm(?string $v): string
{
    $v = trim((string) $v);
    $v = preg_replace('/\s+/', ' ', $v);
    return $v ?? '';
}

private function resolveCollegeId(string $collegeName, array &$collegeCache): ?int
{
    $key = Str::lower($collegeName);
    if (!$collegeName) return null;

    if (!isset($collegeCache[$key])) {
        $college = \App\Models\College::firstOrCreate(
            ['college_name' => $collegeName],
            ['college_name' => $collegeName]
        );
        $collegeCache[$key] = $college->id;
    }
    return $collegeCache[$key];
}

private function resolveYearLevelId(string $yearLevelName, array &$yearCache): ?int
{
    $key = Str::lower($yearLevelName);
    if (!$yearLevelName) return null;

    if (!isset($yearCache[$key])) {
        $yl = \App\Models\YearLevel::firstOrCreate(
            ['year_level_name' => $yearLevelName],
            ['year_level_name' => $yearLevelName]
        );
        $yearCache[$key] = $yl->id;
    }
    return $yearCache[$key];
}

private function resolveCourseId(string $courseName, ?int $collegeId, array &$courseCache): ?int
{
    if (!$courseName) return null;

    // Make cache key include college (because same course name might exist in other colleges)
    $key = Str::lower($courseName) . '|' . ($collegeId ?? 'null');

    if (!isset($courseCache[$key])) {
        // If collegeId is missing, we still create course without college_id ONLY if your DB allows it.
        // Better: require college in CSV and treat missing college as an error.
        if (!$collegeId) return null;

        $course = \App\Models\Course::firstOrCreate(
            ['course_name' => $courseName, 'college_id' => $collegeId],
            ['course_name' => $courseName, 'college_id' => $collegeId, 'course_description' => null]
        );

        $courseCache[$key] = $course->id;
    }

    return $courseCache[$key];
}

// Preview step for bulk upload
public function previewBulkUploadUsers(Request $request)
{
    // ✅ allow Excel + CSV
    $request->validate([
        'file' => 'required|file|mimes:xlsx,csv',
    ]);

    $studentType = \App\Models\UserType::where('name', 'Student')->first();
    if (!$studentType) {
        return back()->with('error', 'Student user type not found. Please add it first.');
    }

    // ✅ read EXCEL/CSV using Laravel-Excel
    $sheets = Excel::toArray(new StudentsBulkImport, $request->file('file'));
    $rows = $sheets[0] ?? [];

    if (empty($rows)) {
        return back()->with('error', 'File is empty or invalid.');
    }

    // ✅ required headers (must exist in Excel)
    $required = ['student_id','lastname','firstname','bisu_email','college','course','year_level'];

    // check first row keys
    $firstRow = $rows[0];
    $missing = [];
    foreach ($required as $col) {
        if (!array_key_exists($col, $firstRow)) {
            $missing[] = $col;
        }
    }
    if (!empty($missing)) {
        return back()->with('error', 'Missing required column(s): ' . implode(', ', $missing));
    }

    $preview = [];
    $issuesCount = 0;

    // Caches (speed)
    $collegeCache = [];
    $yearCache = [];
    $courseCache = [];

    foreach ($rows as $i => $r) {
        $lineNo = $i + 2; // row 1 = headers

        $student_id = $this->norm($r['student_id'] ?? '');
        $lastname   = $this->norm($r['lastname'] ?? '');
        $firstname  = $this->norm($r['firstname'] ?? '');
        $email      = $this->norm($r['bisu_email'] ?? '');
        $college    = $this->norm($r['college'] ?? '');
        $course     = $this->norm($r['course'] ?? '');
        $yearLevel  = $this->norm($r['year_level'] ?? '');

        $issues = [];

        // Basic validation
        if (!$student_id) $issues[] = 'Missing student_id';
        if (!$lastname)   $issues[] = 'Missing lastname';
        if (!$firstname)  $issues[] = 'Missing firstname';
        if (!$email || !filter_var($email, FILTER_VALIDATE_EMAIL)) $issues[] = 'Invalid bisu_email';
        if (!$college)    $issues[] = 'Missing college';
        if (!$course)     $issues[] = 'Missing course';
        if (!$yearLevel)  $issues[] = 'Missing year_level';

        // Resolve IDs dynamically
        $collegeId = null;
        $yearId = null;
        $courseId = null;

        if (empty($issues)) {
            $collegeId = $this->resolveCollegeId($college, $collegeCache);
            $yearId    = $this->resolveYearLevelId($yearLevel, $yearCache);
            $courseId  = $this->resolveCourseId($course, $collegeId, $courseCache);

            if (!$courseId) $issues[] = 'Course could not be resolved (check college/course pairing)';
        }

        // Check duplicates in DB
        if ($student_id && \App\Models\User::where('student_id', $student_id)->exists()) {
            $issues[] = 'student_id already exists';
        }
        if ($email && \App\Models\User::where('bisu_email', $email)->exists()) {
            $issues[] = 'bisu_email already exists';
        }

        if (!empty($issues)) $issuesCount++;

        $preview[] = [
            'line' => $lineNo,
            'student_id' => $student_id,
            'lastname' => $lastname,
            'firstname' => $firstname,
            'bisu_email' => $email,
            'college' => $college,
            'course' => $course,
            'year_level' => $yearLevel,
            'college_id' => $collegeId,
            'course_id' => $courseId,
            'year_level_id' => $yearId,
            'issues' => $issues,
        ];
    }

    // Store preview for confirm step
    session([
        'bulk_upload.preview' => $preview,
        'bulk_upload.student_type_id' => $studentType->id,
        'bulk_upload.hash' => hash('sha256', json_encode($preview)),
    ]);

    return view('super-admin.users-bulk-upload-preview', [
        'preview' => $preview,
        'issuesCount' => $issuesCount,
        'totalCount' => count($preview),
    ]);
}

//Confirm and save bulk upload
public function confirmBulkUploadUsers(Request $request)
{
    // ⏱️ Prevent timeout (dev/local)
    set_time_limit(300);

    $preview       = session('bulk_upload.preview', []);
    $hash          = session('bulk_upload.hash');
    $studentTypeId = session('bulk_upload.student_type_id');

    // ✅ session validation
    if (!$preview || !$hash || $hash !== hash('sha256', json_encode($preview))) {
        return redirect()->route('admin.users.bulk-upload-form')
            ->with('error', 'Upload session expired or invalid. Please upload again.');
    }

    // ✅ Stop if preview has issues
    $hasIssues = collect($preview)->contains(fn ($r) => !empty($r['issues']));
    if ($hasIssues) {
        return back()->with('error', 'Fix the issues first. Rows with errors cannot be confirmed.');
    }

    // 🚀 SPEED-UP: load role ONCE (no repeated DB queries)
    $studentRole = \Spatie\Permission\Models\Role::where('name', 'Student')->first();

    // Optional: counts for message
    $created = 0;
    $updated = 0;

    DB::transaction(function () use ($preview, $studentTypeId, $studentRole, &$created, &$updated) {

        foreach ($preview as $row) {

            // ✅ REQUIRED fields (from preview)
            $studentId = $row['student_id'];
            $email     = $row['bisu_email'];

            // ✅ Ensure user_id exists because your DB says NOT NULL
            // If you want user_id to be student_id, do this:
            $userId = $row['user_id'] ?? $studentId;

            // ✅ contact_no might be nullable now (as you said adviser recommended)
            // But if your DB is still NOT NULL, set a safe default:
            $contactNo = $row['contact_no'] ?? 'N/A';

            // ✅ Build user data
            $userData = [
                'user_id'       => $userId,
                'user_type_id'  => $studentTypeId,
                'student_id'    => $studentId,
                'lastname'      => $row['lastname'],
                'firstname'     => $row['firstname'],
                'bisu_email'    => $email,
                'college_id'    => $row['college_id'],
                'course_id'     => $row['course_id'],
                'year_level_id' => $row['year_level_id'],
                'status'        => 'active',

                // If your migration already made contact_no nullable, you can store null:
                // 'contact_no' => $row['contact_no'] ?? null,
                'contact_no'    => $contactNo,
            ];

            /**
             * ✅ Upsert rule:
             * Use ONE unique key. Student ID is safest in your system.
             */
            $user = \App\Models\User::where('student_id', $studentId)->first();

            if ($user) {
                // Existing user: update fields
                $user->fill($userData);

                // Only reset password if you want to force default every upload:
                // (optional)
                // $user->password = Hash::make($studentId);

                $user->save();
                $updated++;
            } else {
                // New user: create + set default password
                $userData['password'] = Hash::make($studentId);
                $user = \App\Models\User::create($userData);
                $created++;
            }

            // ✅ Assign role WITHOUT querying role each loop
            if ($studentRole) {
                // syncRoles triggers DB writes; assignRole is lighter if no role yet
                // but syncRoles is fine if you want to enforce exactly one role.
                $user->syncRoles([$studentRole->name]);
            }
        }
    });

    // ✅ Clear preview session
    session()->forget(['bulk_upload.preview', 'bulk_upload.hash', 'bulk_upload.student_type_id']);

    return redirect()->route('admin.dashboard', ['page' => 'manage-users'])
        ->with('success', "Bulk upload confirmed! Created: {$created}, Updated: {$updated}");
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


// Add this for the delete confirmation page
public function deleteUser($id)
{
    $user = User::findOrFail($id);
    return view('super-admin.users-delete', compact('user'));
}
    // Add similar methods for other tables (e.g., createYearLevel, storeYearLevel, etc.)
}