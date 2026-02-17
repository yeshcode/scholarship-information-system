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
use App\Http\Controllers\Concerns\UsesActiveSemester;



 // Add your model imports (e.g., YearLevel, etc.)

class SuperAdminController extends Controller
{
    use UsesActiveSemester;

    private function buildDashboardData(): array
{
    $data = [];
    $activeSemesterId = $this->activeSemesterId();

    // =====================
    // KPI / COUNTS (for Super Admin dashboard)
    // =====================
    $data['countUsers'] = User::count();
    $data['countColleges'] = College::count();
    $data['countCourses'] = Course::count();
    $data['countYearLevels'] = YearLevel::count();
    $data['countSemesters'] = Semester::count();

    // Total enrollments (filtered by active semester if set)
    $data['countEnrollments'] = Enrollment::query()
        ->when($activeSemesterId, fn($q) => $q->where('semester_id', $activeSemesterId))
        ->count();

    // KPIs you already used previously (keep these if you still show them)
    $data['kpiTotalUsers'] = $data['countUsers'];
    $data['kpiActiveUsers'] = User::where('status', 'active')->count();
    $data['kpiInactiveUsers'] = User::where('status', 'inactive')->count();

    $studentType = UserType::where('name', 'Student')->first();
    $studentTypeId = $studentType?->id;

    $data['kpiTotalStudents'] = User::when($studentTypeId, fn($q) => $q->where('user_type_id', $studentTypeId))->count();

    // enrolled this semester
    $data['kpiEnrolledThisSemester'] = Enrollment::query()
        ->when($activeSemesterId, fn($q) => $q->where('semester_id', $activeSemesterId))
        ->where('status', 'enrolled')
        ->count();

    // incomplete profiles (simple rule: any missing college/course/year_level)
    $data['kpiIncompleteStudents'] = User::query()
        ->when($studentTypeId, fn($q) => $q->where('user_type_id', $studentTypeId))
        ->where(function($q){
            $q->whereNull('college_id')
            ->orWhereNull('course_id')
            ->orWhereNull('year_level_id');
        })
        ->count();


    // =====================
    // Enrollment Status Overview
    // =====================
    $enrollmentBase = Enrollment::query()
        ->when($activeSemesterId, fn($q) => $q->where('semester_id', $activeSemesterId));

    $data['statEnrolled'] = (clone $enrollmentBase)->where('status', 'enrolled')->count();
    $data['statDropped'] = (clone $enrollmentBase)->where('status', 'dropped')->count();
    $data['statGraduated'] = (clone $enrollmentBase)->where('status', 'graduated')->count();

    // Not enrolled: students who have NO enrollment row in active semester
    if ($activeSemesterId && $studentTypeId) {
        $data['statNotEnrolled'] = User::where('user_type_id', $studentTypeId)
            ->whereDoesntHave('enrollments', fn($e) => $e->where('semester_id', $activeSemesterId))
            ->count();
    } else {
        $data['statNotEnrolled'] = 0;
    }


    // If no session semester filter, use DB current semester
    $semester = $activeSemesterId
        ? Semester::find($activeSemesterId)
        : Semester::where('is_current', true)->first();

    $semesterIdForDash = $semester?->id;

    $data['activeSemesterName'] = $semester
        ? ($semester->term . ' ' . $semester->academic_year)
        : null;

    /**
     * =========================
     * KPI CARDS
     * =========================
     */
    $data['kpiTotalUsers'] = User::count();

    // Adjust these status values based on your system
    $data['kpiActiveUsers'] = User::where('status', 'active')->count();
    $data['kpiInactiveUsers'] = User::where('status', '!=', 'active')->count();

    $studentType = UserType::where('name', 'Student')->first();
    $studentTypeId = $studentType?->id;

    $data['kpiTotalStudents'] = $studentTypeId
        ? User::where('user_type_id', $studentTypeId)->count()
        : 0;

    $data['kpiEnrolledThisSemester'] = $semesterIdForDash
        ? Enrollment::where('semester_id', $semesterIdForDash)
            ->where('status', 'enrolled')
            ->count()
        : 0;

    // Incomplete profiles (students missing key academic fields)
    $data['kpiIncompleteStudents'] = $studentTypeId
        ? User::where('user_type_id', $studentTypeId)
            ->where(function ($q) {
                $q->whereNull('college_id')
                  ->orWhereNull('course_id')
                  ->orWhereNull('year_level_id')
                  ->orWhereNull('student_id')
                  ->orWhereNull('bisu_email');
            })
            ->count()
        : 0;

    /**
     * =========================
     * CHART 1: Enrollments by College (Current Semester)
     * =========================
     */
    $enrollByCollege = Enrollment::query()
        ->when($semesterIdForDash, fn($q) => $q->where('enrollments.semester_id', $semesterIdForDash))
        ->join('users', 'users.id', '=', 'enrollments.user_id')
        ->leftJoin('colleges', 'colleges.id', '=', 'users.college_id')
        ->select(DB::raw("COALESCE(colleges.college_name, 'Unknown College') as label"), DB::raw('COUNT(*) as total'))
        ->where('enrollments.status', 'enrolled')
        ->groupBy('label')
        ->orderBy('label')
        ->get();

    $data['enrollCollegeLabels'] = $enrollByCollege->pluck('label');
    $data['enrollCollegeCounts'] = $enrollByCollege->pluck('total');

    /**
     * =========================
     * CHART 2: Users by Role (based on user_types)
     * =========================
     */
    $usersByType = User::query()
        ->join('user_types', 'user_types.id', '=', 'users.user_type_id')
        ->select('user_types.name as label', DB::raw('COUNT(*) as total'))
        ->groupBy('user_types.name')
        ->orderBy('user_types.name')
        ->get();

    $data['roleLabels'] = $usersByType->pluck('label');
    $data['roleCounts'] = $usersByType->pluck('total');

    /**
     * =========================
     * ALERTS (Data Quality)
     * =========================
     */
    $data['alertMissingCourse'] = $studentTypeId
        ? User::where('user_type_id', $studentTypeId)->whereNull('course_id')->count()
        : 0;

    $data['alertMissingYearLevel'] = $studentTypeId
        ? User::where('user_type_id', $studentTypeId)->whereNull('year_level_id')->count()
        : 0;

    $data['alertMissingCollege'] = $studentTypeId
        ? User::where('user_type_id', $studentTypeId)->whereNull('college_id')->count()
        : 0;

    $data['alertMissingEmail'] = User::whereNull('bisu_email')->orWhere('bisu_email', '')->count();

    /**
     * =========================
     * RECENT ACTIVITY (last 10)
     * =========================
     */
    $recentUsers = User::orderByDesc('created_at')
        ->limit(5)
        ->get(['id', 'firstname', 'lastname', 'created_at']);

    $recentEnrollments = Enrollment::query()
        ->with(['user:id,firstname,lastname', 'semester:id,term,academic_year'])
        ->orderByDesc('created_at')
        ->limit(5)
        ->get();

    $activity = [];

    foreach ($recentUsers as $u) {
        $activity[] = [
            'type' => 'User',
            'detail' => trim(($u->lastname ?? '') . ', ' . ($u->firstname ?? '')),
            'date' => optional($u->created_at)->format('M d, Y h:i A'),
        ];
    }

    foreach ($recentEnrollments as $e) {
        $semLabel = $e->semester ? ($e->semester->term . ' ' . $e->semester->academic_year) : 'Semester';
        $student = $e->user ? trim(($e->user->lastname ?? '') . ', ' . ($e->user->firstname ?? '')) : 'Student';
        $activity[] = [
            'type' => 'Enrollment',
            'detail' => "{$student} • {$semLabel}",
            'date' => optional($e->created_at)->format('M d, Y h:i A'),
        ];
    }

    // sort by date string not perfect; if you want exact sort, I’ll convert to timestamps.
    $data['recentActivity'] = array_slice($activity, 0, 10);

    /**
     * =========================
     * KEEP YOUR EXISTING CHARTS (optional)
     * =========================
     * If you still want your previous charts (Students per Year, Pie by Course, etc.)
     * keep them here so your current blade won't break.
     */

    // Students added per year
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

    $data['studentYearLabels'] = $studentsByYear->pluck('year');
    $data['studentYearCounts'] = $studentsByYear->pluck('total');

    // Enrollments per course (pie)
    $enrollmentByCourse = Enrollment::query()
        ->when($semesterIdForDash, fn($q) => $q->where('semester_id', $semesterIdForDash))
        ->select('course_id', DB::raw('COUNT(*) as total'))
        ->with('course')
        ->groupBy('course_id')
        ->get();

    $data['coursePieLabels'] = $enrollmentByCourse->map(fn($row) =>
        $row->course ? $row->course->course_name : 'Unknown Course'
    );
    $data['coursePieCounts'] = $enrollmentByCourse->pluck('total');

    // Enrollments per course per semester (stacked bar)
    $enrollmentBySemesterCourse = Enrollment::query()
        ->select('semester_id', 'course_id', DB::raw('COUNT(*) as total'))
        ->with(['semester', 'course'])
        ->groupBy('semester_id', 'course_id')
        ->get();

    $semesterMap = [];
    $courseMap = [];

    foreach ($enrollmentBySemesterCourse as $row) {
        if (!isset($semesterMap[$row->semester_id])) {
            $semesterMap[$row->semester_id] = $row->semester
                ? ($row->semester->term . ' ' . $row->semester->academic_year)
                : 'Unknown Semester';
        }
        if (!isset($courseMap[$row->course_id])) {
            $courseMap[$row->course_id] = $row->course
                ? $row->course->course_name
                : 'Unknown Course';
        }
    }

    $semesterIds = array_keys($semesterMap);
    $courseIds = array_keys($courseMap);

    usort($semesterIds, fn($a, $b) => strcmp($semesterMap[$a], $semesterMap[$b]));
    usort($courseIds, fn($a, $b) => strcmp($courseMap[$a], $courseMap[$b]));

    $lookup = [];
    foreach ($enrollmentBySemesterCourse as $row) {
        $lookup[$row->course_id][$row->semester_id] = $row->total;
    }

    $matrix = [];
    foreach ($courseIds as $courseId) {
        $rowData = [];
        foreach ($semesterIds as $semesterId) {
            $rowData[] = $lookup[$courseId][$semesterId] ?? 0;
        }
        $matrix[] = $rowData;
    }

    $data['semCourseLabels'] = array_map(fn($id) => $semesterMap[$id], $semesterIds);
    $data['semCourseCourseNames'] = array_map(fn($id) => $courseMap[$id], $courseIds);
    $data['semCourseMatrix'] = $matrix;

    return $data;
}


public function dashboardData()
{
    return response()->json($this->buildDashboardData());
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

            // Filters
            $selectedSemesterId = request('semester_id');
            $collegeId = request('college_id');
            $courseId  = request('course_id');
            $status    = request('status'); // enrolled | dropped | graduated | not_enrolled

            // Semesters list
            $data['semesters'] = Semester::orderBy('start_date', 'desc')->get();

            // Default semester = current semester if not chosen (important for "not enrolled")
            if (!$selectedSemesterId) {
                $current = Semester::where('is_current', true)->first();
                $selectedSemesterId = $current?->id;
            }

            $data['selectedSemesterId'] = $selectedSemesterId;

            // Dropdowns
            $data['colleges']  = College::orderBy('college_name')->get();
            $data['courses']   = Course::orderBy('course_name')->get();

            // Status dropdown (includes derived)
            $data['statuses'] = ['enrolled', 'dropped', 'graduated', 'not_enrolled'];

            /**
             * ✅ MAIN LIST:
             * Show ALL students, with their enrollment (if exists) in the selected semester.
             * If no enrollment row exists => "not enrolled"
             */
            $query = User::query()
                ->whereHas('userType', function ($q) {
                    $q->where('name', 'Student');
                })
                ->with(['college', 'course', 'yearLevel'])
                ->leftJoin('enrollments', function ($join) use ($selectedSemesterId) {
                    $join->on('enrollments.user_id', '=', 'users.id')
                        ->where('enrollments.semester_id', '=', $selectedSemesterId);
                })
                ->leftJoin('semesters', 'semesters.id', '=', 'enrollments.semester_id')
                ->select([
                    'users.*',
                    'enrollments.status as enrollment_status',
                    'enrollments.id as enrollment_id',
                    'semesters.term as sem_term',
                    'semesters.academic_year as sem_academic_year',
                ]);

            // College filter
            if (!empty($collegeId)) {
                $query->where('users.college_id', $collegeId);
            }

            // Course filter
            if (!empty($courseId)) {
                $query->where('users.course_id', $courseId);
            }

            // Status filter
            if (!empty($status)) {
                if ($status === 'not_enrolled') {
                    $query->whereNull('enrollments.id'); // derived state
                } else {
                    $query->where('enrollments.status', $status); // MUST qualify column
                }
            }

            // Alphabetical (by last name)
            $query->orderBy('users.lastname')->orderBy('users.firstname');

            // Paginate 20
            $data['studentsForEnrollmentList'] = $query->paginate(20, ['*'], 'enrollments_page')
                ->withQueryString();
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
                
                // ✅ Courses dropdown: if college is selected, show only its courses
                $data['courses'] = Course::when(!empty($collegeId), function ($q) use ($collegeId) {
                        $q->where('college_id', $collegeId);
                    })
                    ->orderBy('course_name')
                    ->get();

                $data['selectedCollegeId']   = $collegeId;
                $data['selectedYearLevelId'] = $yearLevelId;
                $data['selectedCourseId']    = $courseId;

                $studentType = UserType::where('name', 'Student')->first();
                $data['studentUserTypeId'] = $studentType?->id; 


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

private function normHeaderKey(string $key): string
{
    // normalize: lowercase + remove spaces/_/-
    $k = strtolower(trim($key));
    $k = preg_replace('/[\s\-_]+/', '', $k); // "First Name" -> "firstname", "first_name" -> "firstname"
    return $k;
}

private function pickFromRow(array $row, array $aliases): string
{
    // build a normalized lookup of the row keys (once per call)
    static $cache = [];
    $hash = spl_object_id((object)$row); // cheap unique-ish per call
    if (!isset($cache[$hash])) {
        $map = [];
        foreach ($row as $k => $v) {
            $map[$this->normHeaderKey((string)$k)] = $v;
        }
        $cache[$hash] = $map;
    }

    $map = $cache[$hash];

    foreach ($aliases as $alias) {
        $a = $this->normHeaderKey($alias);
        if (array_key_exists($a, $map)) {
            return $this->norm((string)$map[$a]);
        }
    }
    return '';
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

    //headers
    $firstRow = $rows[0];

    // canonical field => accepted header aliases
    $requiredMap = [
        'student_id' => ['student_id', 'studentid', 'student id', 'idno', 'id no', 'id_number'],
        'lastname'   => ['lastname', 'last_name', 'last name', 'surname', 'familyname', 'family name'],
        'firstname'  => ['firstname', 'first_name', 'first name', 'givenname', 'given name'],
        'bisu_email' => ['bisu_email', 'bisu email', 'email', 'emailaddress', 'email address'],
        'college'    => ['college', 'college_name', 'college name'],
        'course'     => ['course', 'course_name', 'course name', 'program', 'program_name', 'program name'],
        'year_level' => ['year_level', 'yearlevel', 'year level', 'year', 'yearlevel_name', 'year level name'],
    ];

    // check which required fields cannot be found in the header row
    $missing = [];
    foreach ($requiredMap as $field => $aliases) {
        $found = false;
        foreach ($aliases as $alias) {
            $key = $this->normHeaderKey($alias);
            // compare against actual keys in the first row
            foreach (array_keys($firstRow) as $actualKey) {
                if ($this->normHeaderKey((string)$actualKey) === $key) {
                    $found = true;
                    break 2;
                }
            }
        }
        if (!$found) $missing[] = $field;
    }

    if (!empty($missing)) {
        return back()->with('error', 'Missing required column(s): ' . implode(', ', $missing)
            . '. Tip: allowed header formats include "First Name", "first_name", "FIRSTNAME", etc.');
    }


    $preview = [];
    $issuesCount = 0;

    // Caches (speed)
    $collegeCache = [];
    $yearCache = [];
    $courseCache = [];

    // ✅ allowed suffix values (optional). You can edit this list.
    $allowedSuffix = ['JR','SR','II','III','IV','V'];

    foreach ($rows as $i => $r) {
        $lineNo = $i + 2; // row 1 = headers

        $student_id = $this->pickFromRow($r, ['student_id', 'student id', 'studentid', 'idno', 'id no']);
        $lastname   = $this->pickFromRow($r, ['lastname', 'last name', 'last_name', 'surname', 'family name']);
        $firstname  = $this->pickFromRow($r, ['firstname', 'first name', 'first_name', 'given name']);
        $middlename = $this->pickFromRow($r, ['middlename', 'middle name', 'middle_name', 'mi', 'm.i.']); // optional
        $suffix     = strtoupper($this->pickFromRow($r, ['suffix', 'name suffix', 'name_suffix']));       // optional
        $email      = $this->pickFromRow($r, ['bisu_email', 'bisu email', 'email', 'email address']);
        $college    = $this->pickFromRow($r, ['college', 'college name', 'college_name']);
        $course     = $this->pickFromRow($r, ['course', 'course name', 'course_name', 'program', 'program name']);
        $yearLevel  = $this->pickFromRow($r, ['year_level', 'year level', 'yearlevel', 'year']);


        $issues = [];

        // Basic validation
        if (!$student_id) $issues[] = 'Missing student_id';
        if (!$lastname)   $issues[] = 'Missing lastname';
        if (!$firstname)  $issues[] = 'Missing firstname';
        if (!$email || !filter_var($email, FILTER_VALIDATE_EMAIL)) $issues[] = 'Invalid bisu_email';
        if (!$college)    $issues[] = 'Missing college';
        if (!$course)     $issues[] = 'Missing course';
        if (!$yearLevel)  $issues[] = 'Missing year_level';

        // ✅ optional validations
        if ($middlename && mb_strlen($middlename) > 255) $issues[] = 'middlename too long';
        if ($suffix && mb_strlen($suffix) > 50) $issues[] = 'suffix too long';

        // // normalize suffix and validate against allowed list (optional rule)
        // $suffixUpper = $suffix ? strtoupper($suffix) : '';
        // if ($suffixUpper && !in_array($suffixUpper, $allowedSuffix, true)) {
        //     // not an error if you want to allow any suffix; if yes, comment this out
        //     $issues[] = 'suffix not allowed (use Jr, Sr, II, III...)';
        // }

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
            'middlename' => $middlename,
            'suffix' => $suffix,
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
                'middlename'    => $row['middlename'] ?? null, // ✅ added
                'suffix'        => $row['suffix'] ?? null,     // ✅ added
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
        'is_current' => 'nullable|boolean',
    ]);

    DB::transaction(function () use ($request) {

        if ($request->filled('is_current')) {
            Semester::query()->update(['is_current' => false]);
            session()->forget('active_semester_id');
        }

        Semester::create([
            'term' => $request->term,
            'academic_year' => $request->academic_year,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'is_current' => $request->filled('is_current'),
        ]);
    });

    return redirect()
        ->route('admin.dashboard', ['page' => 'semesters'])
        ->with('success', 'Semester added successfully.');
}


public function updateSemester(Request $request, $id)
{
    $request->validate([
        'term' => 'required|string|max:255',
        'academic_year' => 'required|string|max:255',
        'start_date' => 'required|date',
        'end_date' => 'required|date|after:start_date',
        'is_current' => 'nullable|boolean',
    ]);

    DB::transaction(function () use ($request, $id) {

        $setAsCurrent = $request->filled('is_current'); // ✅ safer checkbox check

        // If this semester is marked as current
        if ($setAsCurrent) {

            // Turn OFF all other semesters
            Semester::where('id', '!=', $id)
                ->update(['is_current' => false]);

            // ✅ IMPORTANT: clear global session filter so navbar follows the DB current semester
            session()->forget('active_semester_id');
        }

        // Update this semester
        $semester = Semester::findOrFail($id);
        $semester->update([
            'term' => $request->term,
            'academic_year' => $request->academic_year,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'is_current' => $setAsCurrent,
        ]);
    });

    return redirect()
        ->route('admin.dashboard', ['page' => 'semesters'])
        ->with('success', 'Semester updated successfully.');
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

    // Build the enrollments query with relationships + alphabetical order by student
    $query = Enrollment::query()
        ->join('users', 'users.id', '=', 'enrollments.user_id')
        ->select('enrollments.*') // important: keep Enrollment model fields
        ->with(['user', 'semester', 'course']);

    // Apply semester filter if selected
    if ($selectedSemesterId) {
        $query->where('enrollments.semester_id', $selectedSemesterId);
    }
    // ✅ Alphabetical by student's name
    $query->orderBy('users.lastname')
        ->orderBy('users.firstname');

    // Paginate the results
    $enrollments = $query->paginate(15)->withQueryString();

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
    return view('super-admin.enrollments-edit', compact('enrollment'));
}

public function storeEnrollment(Request $request)
{
    $request->validate([
        'user_id' => 'required|exists:users,id',
        'semester_id' => 'required|exists:semesters,id',
        'course_id' => 'required|exists:courses,id',
        'status' => 'required|in:enrolled,dropped,graduated',
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
        'status' => 'required|in:enrolled,dropped'
    ]);

    $enrollment = Enrollment::findOrFail($id);
    $enrollment->status = $request->status;
    $enrollment->save();

    return redirect()->route('admin.dashboard', ['page' => 'enrollments', 'semester_id' => $enrollment->semester_id])
        ->with('success', 'Enrollment updated successfully.');
}


public function destroyEnrollment($id)
{
    Enrollment::findOrFail($id)->delete();
    return redirect()->route('admin.dashboard', ['page' => 'enrollments'])->with('success', 'Enrollment deleted successfully!');
}

// Add these new methods at the end of the class (before the closing })
public function enrollStudents(Request $request)
{
    $search = $request->get('search');
    $mode   = $request->get('mode', 'promote'); // promote | new
    $targetSemesterId = $request->get('semester_id'); // required for filtering
    $sourceSemesterId = $request->get('source_semester_id'); // optional

    $currentSemester = Semester::where('is_current', true)->first();

    // If no source semester chosen, use current
    $sourceSemester = $sourceSemesterId
        ? Semester::find($sourceSemesterId)
        : $currentSemester;

    $targetSemester = $targetSemesterId
        ? Semester::find($targetSemesterId)
        : null;

    // Base: students only
    $query = User::where('user_type_id', 3)
        ->with(['college', 'course', 'yearLevel']);

    // Search
    if ($search) {
        $query->where(function ($q) use ($search) {
            $q->where('firstname', 'ILIKE', "%{$search}%")
              ->orWhere('lastname', 'ILIKE', "%{$search}%")
              ->orWhere('bisu_email', 'ILIKE', "%{$search}%")
              ->orWhereHas('course', fn($c) => $c->where('course_name', 'ILIKE', "%{$search}%"))
              ->orWhereHas('college', fn($c) => $c->where('college_name', 'ILIKE', "%{$search}%"))
              ->orWhereHas('yearLevel', fn($y) => $y->where('year_level_name', 'ILIKE', "%{$search}%"));
        });
    }

    /**
     * ✅ FILTERING LOGIC
     * - Always hide students already enrolled in TARGET semester (so no duplicates)
     */
    if ($targetSemester) {
        $query->whereDoesntHave('enrollments', function ($e) use ($targetSemester) {
            $e->where('semester_id', $targetSemester->id);
        });
    }

    /**
     * ✅ Mode-specific logic
     */
    if ($mode === 'promote') {
        // Only show returning students who ARE enrolled in source semester
        if ($sourceSemester) {
            $query->whereHas('enrollments', function ($e) use ($sourceSemester) {
                $e->where('semester_id', $sourceSemester->id)
                  ->where('status', 'enrolled');
            });
        } else {
            // If no current semester set, show none to avoid wrong updates
            $query->whereRaw('1=0');
        }
    } else {
        // mode === 'new'
        // Show students who are NOT enrolled in target semester (already handled above)
        // Optional: exclude already graduated users
        if ($sourceSemester) {
        $query->whereDoesntHave('enrollments', function ($e) use ($sourceSemester) {
            $e->where('semester_id', $sourceSemester->id)
              ->where('status', 'enrolled');
        });
    }

    // Optional: exclude graduated users in users table
    $query->where('status', '!=', 'graduated');
    }

    $students = $query
        ->orderBy('lastname')
        ->orderBy('firstname')
        ->paginate(20);

    $semesters = Semester::orderBy('start_date', 'desc')->get();

    return view('super-admin.enroll-students', compact(
        'students',
        'semesters',
        'currentSemester',
        'sourceSemester',
        'targetSemester',
        'request'
    ));
}





// Add this method right after enrollStudents
public function storeEnrollStudents(Request $request)
{
    $request->validate([
        'selected_users' => 'required|array|min:1',
        'semester_id'    => 'required|exists:semesters,id',
        'mode'           => 'required|in:promote,new',
        'source_semester_id' => 'nullable|exists:semesters,id',
    ]);

    $mode = $request->mode;
    $targetSemester = Semester::findOrFail($request->semester_id);

    // source semester: request OR current
    $currentSemester = Semester::where('is_current', true)->first();
    $sourceSemester = $request->source_semester_id
        ? Semester::find($request->source_semester_id)
        : $currentSemester;

    $enrolledCount = 0;
    $graduatedCount = 0;
    $skippedCount = 0;

    DB::transaction(function () use (
        $request, $mode, $targetSemester, $sourceSemester,
        &$enrolledCount, &$graduatedCount, &$skippedCount
    ) {
        foreach ($request->selected_users as $userId) {
            $user = User::with('yearLevel')->findOrFail($userId);

            // Safety: skip if already enrolled in target (double protection)
            $alreadyInTarget = Enrollment::where('user_id', $user->id)
                ->where('semester_id', $targetSemester->id)
                ->exists();

            if ($alreadyInTarget) {
                $skippedCount++;
                continue;
            }

            // ✅ PROMOTE MODE: must be enrolled in source semester
            if ($mode === 'promote') {
                if (!$sourceSemester) {
                    $skippedCount++;
                    continue;
                }

                $isEnrolledInSource = Enrollment::where('user_id', $user->id)
                    ->where('semester_id', $sourceSemester->id)
                    ->where('status', 'enrolled')
                    ->exists();

                if (!$isEnrolledInSource) {
                    $skippedCount++;
                    continue;
                }
            }

            /**
             * ✅ YEAR LEVEL / GRADUATION LOGIC (dynamic)
             * Increase year level ONLY when:
             * - targetSemester is "1st Semester"
             * - and target academic_year != source academic_year (new AY)
             */
            $isFirstSem = str_contains(strtolower($targetSemester->term), '1st');
            $isNewAcademicYear = $sourceSemester && ($targetSemester->academic_year !== $sourceSemester->academic_year);

            $shouldPromoteYearLevel = ($mode === 'promote' && $isFirstSem && $isNewAcademicYear);

            // Determine current year number (1-4) from year_level_name
            $yearName = strtolower($user->yearLevel?->year_level_name ?? '');
            preg_match('/\d+/', $yearName, $matches);
            $yearNum = isset($matches[0]) ? (int)$matches[0] : null;

            // If promoting and student is 4th year -> graduate (don’t enroll to target)
            if ($shouldPromoteYearLevel && $yearNum === 4) {
                $user->update(['status' => 'graduated']);
                $graduatedCount++;
                continue;
            }

            // If should promote year level: 1->2, 2->3, 3->4 (keep if missing)
            if ($shouldPromoteYearLevel && $yearNum && $yearNum < 4) {
                $nextYearNum = $yearNum + 1;

                $nextYearLevel = YearLevel::whereRaw("year_level_name ILIKE ?", ["%{$nextYearNum}%"])
                    ->first();

                if ($nextYearLevel) {
                    $user->update(['year_level_id' => $nextYearLevel->id]);
                }
            }

            // ✅ ENROLL into target semester
            Enrollment::create([
                'user_id' => $user->id,
                'semester_id' => $targetSemester->id,
                'course_id' => $user->course_id, // keep their course
                'status' => 'enrolled',
            ]);

            $enrolledCount++;
        }
    });

    return redirect()->back()->with(
        'success',
        "Done! Enrolled: {$enrolledCount}, Graduated: {$graduatedCount}, Skipped: {$skippedCount}"
    );
}

private function promoteYearLevelId(?int $currentYearLevelId): ?int
{
    if (!$currentYearLevelId) return null;

    $current = YearLevel::find($currentYearLevelId);
    if (!$current) return null;

    $name = strtolower($current->year_level_name);

    // Adjust if your names are like "2nd Year", "Third Year", etc.
    if (str_contains($name, '1')) return YearLevel::where('year_level_name', 'ILIKE', '%2%')->value('id');
    if (str_contains($name, '2')) return YearLevel::where('year_level_name', 'ILIKE', '%3%')->value('id');
    if (str_contains($name, '3')) return YearLevel::where('year_level_name', 'ILIKE', '%4%')->value('id');

    return null;
}


public function deleteEnrollment($id)
{
    $enrollment = Enrollment::findOrFail($id);
    return view('super-admin.enrollments-delete', compact('enrollment'));
}

// --- ENROLLMENT RECORDS (BY ACADEMIC YEAR) ---

public function enrollmentRecords()
{
    $academicYears = Semester::select('academic_year')
        ->distinct()
        ->orderBy('academic_year', 'desc')
        ->pluck('academic_year');

    return view('super-admin.enrollment-records', compact('academicYears'));
}



public function enrollmentRecordsByYear($academicYear)
{
    // Get all enrollments where the semester has this academic year
    $enrollments = Enrollment::query()
        ->join('users', 'users.id', '=', 'enrollments.user_id')
        ->join('semesters', 'semesters.id', '=', 'enrollments.semester_id')
        ->select('enrollments.*')
        ->with(['user', 'semester', 'course'])
        ->where('semesters.academic_year', $academicYear)
        ->orderBy('users.lastname')
        ->orderBy('users.firstname')
        ->paginate(15)
        ->withQueryString();

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