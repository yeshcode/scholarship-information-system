<?php
namespace App\Http\Controllers;

use App\Models\Scholar;
use App\Models\Enrollment;
use App\Models\ScholarshipBatch;
use App\Models\Stipend;
use App\Models\StipendsRelease;
use App\Models\Announcement;
use App\Models\Scholarship;
use App\Models\User;
use App\Models\Semester;  // Add if needed for FKs
use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;  // Add this for emails
use App\Mail\AnnouncementNotification;  // Add this for the Mailable
use Illuminate\Support\Str;
use App\Jobs\SendAnnouncementNotifications;
use Illuminate\Support\Facades\Log;
use App\Models\College;
use App\Models\Course;
use App\Models\YearLevel;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Concerns\UsesActiveSemester;
use Carbon\Carbon;
use App\Models\StipendReleaseForm;
use App\Models\StipendReleaseFormColumn;
use Illuminate\Support\Facades\Storage;
use Barryvdh\DomPDF\Facade\Pdf;
use PhpOffice\PhpWord\PhpWord;
use PhpOffice\PhpWord\IOFactory;



class CoordinatorController extends Controller
{

    use UsesActiveSemester;

    public function dashboard()
    {
        $activeSemesterId = $this->activeSemesterId(); // from UsesActiveSemester
        $activeSemester = $activeSemesterId ? Semester::find($activeSemesterId) : null;

        $activeAcademicYear = $activeSemester?->academic_year;
        /**
         * FILTER RULE:
         * - If active semester is selected:
         *   ✅ Include non-batch scholars ALWAYS (batch_id is NULL)
         *   ✅ Include batch scholars ONLY if batch.semester_id = activeSemesterId
         * - If no active semester:
         *   ✅ Include all scholars
         */
        // ✅ AY-based filter
            $scholarScope = Scholar::query()
                ->activeRoster()
                ->when($activeAcademicYear, function ($q) use ($activeAcademicYear) {
                    $q->where(function ($w) use ($activeAcademicYear) {
                        $w->whereNull('scholars.batch_id')
                        ->orWhereHas('scholarshipBatch.semester', function ($sem) use ($activeAcademicYear) {
                            $sem->where('academic_year', $activeAcademicYear);
                        });
                    });
                });

        // ✅ COUNTS (Filtered)
        $totalScholars = (clone $scholarScope)->count();

        // Students are not semester-tied (keep overall)
        $totalStudents = User::whereHas('userType', fn($t) => $t->where('name', 'Student'))->count();

        // Scholarships are not semester-tied (keep overall)
        $totalScholarships = Scholarship::count();

        // Batches are semester-tied: follow active semester when selected
        $totalBatches = ScholarshipBatch::query()
            ->when($activeSemesterId, fn($q) => $q->where('semester_id', $activeSemesterId))
            ->count();

        // Recent scholars (filtered too)
        $recentScholars = (clone $scholarScope)
            ->where('scholars.created_at', '>=', now()->subDays(7))
            ->count();

        // ✅ PIE (Filtered): scholars per scholarship
        $scholarsByScholarship = Scholarship::query()
            ->leftJoin('scholars', 'scholars.scholarship_id', '=', 'scholarships.id')
            ->leftJoin('scholarship_batches', 'scholarship_batches.id', '=', 'scholars.batch_id')
            ->leftJoin('semesters as batch_sem', 'batch_sem.id', '=', 'scholarship_batches.semester_id')
            ->when($activeAcademicYear, function ($q) use ($activeAcademicYear) {
                $q->where(function ($w) use ($activeAcademicYear) {
                    $w->whereNull('scholars.batch_id')
                    ->orWhere('batch_sem.academic_year', $activeAcademicYear);
                });
            })
            ->select(
                'scholarships.id',
                'scholarships.scholarship_name',
                DB::raw('COUNT(scholars.id) as total')
            )
            ->groupBy('scholarships.id', 'scholarships.scholarship_name')
            ->orderByDesc('total')
            ->get();

        $pieLabels = $scholarsByScholarship->pluck('scholarship_name')->values();
        $pieData   = $scholarsByScholarship->pluck('total')->values();

        // ✅ LINE (Filtered): scholars per course (top 10 for readability)
        $scholarsByCourse = Scholar::query()
            ->activeRoster()
            ->leftJoin('users', 'users.id', '=', 'scholars.student_id')
            ->leftJoin('courses', 'courses.id', '=', 'users.course_id')
            ->leftJoin('scholarship_batches', 'scholarship_batches.id', '=', 'scholars.batch_id')
            ->leftJoin('semesters as batch_sem', 'batch_sem.id', '=', 'scholarship_batches.semester_id')
            ->when($activeAcademicYear, function ($q) use ($activeAcademicYear) {
                $q->where(function ($w) use ($activeAcademicYear) {
                    $w->whereNull('scholars.batch_id')
                    ->orWhere('batch_sem.academic_year', $activeAcademicYear);
                });
            })
            ->select(
                DB::raw("COALESCE(courses.course_name, 'No Course') as course_name"),
                DB::raw('COUNT(scholars.id) as total')
            )
            ->groupBy('course_name')
            ->orderByDesc('total')
            ->get();

        $topCourseRows = $scholarsByCourse->take(10);
        $lineLabels = $topCourseRows->pluck('course_name')->values();
        $lineData   = $topCourseRows->pluck('total')->values();

        // ✅ NEW LINE (Filtered): scholars per college (top 10)
        $scholarsByCollege = Scholar::query()
            ->activeRoster()
            ->leftJoin('users', 'users.id', '=', 'scholars.student_id')
            ->leftJoin('colleges', 'colleges.id', '=', 'users.college_id')
            ->leftJoin('scholarship_batches', 'scholarship_batches.id', '=', 'scholars.batch_id')
            ->leftJoin('semesters as batch_sem', 'batch_sem.id', '=', 'scholarship_batches.semester_id')
            ->when($activeAcademicYear, function ($q) use ($activeAcademicYear) {
                $q->where(function ($w) use ($activeAcademicYear) {
                    $w->whereNull('scholars.batch_id')
                    ->orWhere('batch_sem.academic_year', $activeAcademicYear);
                });
            })
            ->select(
                DB::raw("COALESCE(colleges.college_name, 'No College') as college_name"),
                DB::raw('COUNT(scholars.id) as total')
            )
            ->groupBy('college_name')
            ->orderByDesc('total')
            ->get();

        $topCollegeRows = $scholarsByCollege->take(10);
        $collegeLabels = $topCollegeRows->pluck('college_name')->values();
        $collegeData   = $topCollegeRows->pluck('total')->values();

        // ✅ TABLE rows (same as pie but add percent)
        $grandTotal = max(1, (int) $pieData->sum());
        $tableRows = $scholarsByScholarship->map(function ($r) use ($grandTotal) {
            $r->percent = round(((int)$r->total / $grandTotal) * 100, 1);
            return $r;
        });

        return view('coordinator.dashboard', compact(
            'activeSemester',
            'activeSemesterId',
            'totalScholars',
            'totalStudents',
            'totalScholarships',
            'totalBatches',
            'recentScholars',
            'pieLabels',
            'pieData',
            'lineLabels',
            'lineData',
            'collegeLabels',
            'collegeData',
            'tableRows'
        ));
    }


    // Manage Scholars
    private function isBatchBasedScholarship(Scholarship $scholarship): bool
    {
        // You can change the logic here anytime
        $name = strtoupper(trim($scholarship->scholarship_name ?? ''));
        return in_array($name, ['TDP', 'TES']);
    }

    public function manageScholars(Request $request)
{
    $activeSemesterId = $this->activeSemesterId();  // ✅ FROM SESSION OR CURRENT
    $semesterId = $request->get('semester_id') ?: $activeSemesterId;
    $scholarshipId= $request->get('scholarship_id');
    $batchId      = $request->get('batch_id');
    $searchType   = $request->get('search_type', 'name'); // name | student_id
    $q            = trim((string) $request->get('q', ''));

    // Semesters for dropdown (and default to current semester)
    $semesters = Semester::orderByDesc('start_date')->get();
    $selectedSemester = $semesterId ? Semester::find($semesterId) : null;


    // Scholarship buttons/dropdown
    $scholarships = Scholarship::query()
        ->withCount('scholars')
        ->orderBy('scholarship_name')
        ->get();

    $selectedScholarship = $scholarshipId ? Scholarship::find($scholarshipId) : null;

    // Detect if selected scholarship is TDP/TES
    $isTdpTes = false;
    if ($selectedScholarship) {
        $name = strtoupper($selectedScholarship->scholarship_name ?? '');
        $isTdpTes = str_contains($name, 'TDP') || str_contains($name, 'TES');
    }

    // Batch options only if TDP/TES selected
    $batchOptions = collect();
    if ($isTdpTes && $selectedScholarship) {
        $batchOptions = ScholarshipBatch::query()
            ->where('scholarship_id', $selectedScholarship->id)
            ->withCount('scholars')
            ->orderByDesc('batch_number')
            ->get();
    } else {
        // if not TDP/TES, ignore any batch filter
        $batchId = null;
    }

    /**
     * MAIN SCHOLARS QUERY
     * Join enrollment (for selected semester) to show enrolled_status + semester label
     */
    $scholarsQuery = Scholar::query()
        ->activeRoster()
        ->with([
            'user.course',
            'user.yearLevel',
            'scholarship',
            'scholarshipBatch',
        ])
        ->leftJoin('users', 'users.id', '=', 'scholars.student_id')
        ->leftJoin('enrollments', function ($join) use ($semesterId) {
            $join->on('enrollments.user_id', '=', 'users.id')
                 ->where('enrollments.semester_id', '=', $semesterId);
        })
        ->leftJoin('semesters', 'semesters.id', '=', 'enrollments.semester_id')
        ->select([
            'scholars.*',
            'users.student_id as u_student_id',
            'users.lastname as u_lastname',
            'users.firstname as u_firstname',
            'enrollments.status as enrolled_status', // IMPORTANT: qualified by join
            'semesters.term as enrolled_term',
            'semesters.academic_year as enrolled_academic_year',
        ]);

        
    // Filter by scholarship
    if (!empty($scholarshipId)) {
        $scholarsQuery->where('scholars.scholarship_id', $scholarshipId);
    }

    // Filter by batch (only for TDP/TES)
    if (!empty($batchId)) {
        $scholarsQuery->where('scholars.batch_id', $batchId);
    }

    // Search
    if ($q !== '') {
        if ($searchType === 'student_id') {
            $scholarsQuery->where('users.student_id', 'ILIKE', "%{$q}%");
        } else {
            // name
            $scholarsQuery->where(function ($w) use ($q) {
                $w->where('users.lastname', 'ILIKE', "%{$q}%")
                  ->orWhere('users.firstname', 'ILIKE', "%{$q}%");
            });
        }
    }

    // Alphabetical sorting (your request)
    $scholarsQuery->orderBy('users.lastname')->orderBy('users.firstname');

    // Paginate
    $scholars = $scholarsQuery->paginate(15)->withQueryString();

    return view('coordinator.manage-scholars', compact(
        'scholars',
        'scholarships',
        'semesters',
        'selectedSemester',
        'selectedScholarship',
        'batchOptions',
        'isTdpTes'
    ));
}

    public function scholarsByScholarship(Scholarship $scholarship)
    {
        // If TDP/TES -> go to batches page instead
        if ($this->isBatchBasedScholarship($scholarship)) {
            return redirect()->route('coordinator.scholars.batches', $scholarship->id);
        }

        $scholarships = Scholarship::withCount('scholars')->orderBy('scholarship_name')->get();

        $scholars = Scholar::with([
            'user.course',
            'scholarshipBatch.semester',
            'scholarship'
        ])->where('scholarship_id', $scholarship->id)
        ->latest()
        ->paginate(10);

        return view('coordinator.manage-scholars', [
            'scholarships' => $scholarships,
            'scholars' => $scholars,
            'selectedScholarship' => $scholarship,
            'mode' => 'scholarship',
        ]);
    }

    public function batchesByScholarship(Scholarship $scholarship)
    {
        $scholarships = Scholarship::withCount('scholars')->orderBy('scholarship_name')->get();

        $batches = ScholarshipBatch::withCount('scholars')
            ->where('scholarship_id', $scholarship->id)
            ->orderBy('batch_number')
            ->get();

        return view('coordinator.manage-scholars', [
            'scholarships' => $scholarships,
            'batches' => $batches,
            'selectedScholarship' => $scholarship,
            'mode' => 'batches',
        ]);
    }

    public function scholarsByBatch(ScholarshipBatch $batch)
    {
        $scholarships = Scholarship::withCount('scholars')->orderBy('scholarship_name')->get();

        $scholars = Scholar::with([
            'user.course',
            'scholarshipBatch.semester',
            'scholarship'
        ])->where('batch_id', $batch->id)
        ->latest()
        ->paginate(10);

        return view('coordinator.manage-scholars', [
            'scholarships' => $scholarships,
            'scholars' => $scholars,
            'selectedBatch' => $batch,
            'selectedScholarship' => $batch->scholarship,
            'mode' => 'batch',
        ]);
    }

    public function createScholar(Request $request)
{
    $q = trim((string) $request->get('q', ''));

    // Current semester
    $currentSemester = Semester::where('is_current', true)->first();

     // ✅ Scholarships (for the new Scholarship dropdown in modal)
    $scholarships = Scholarship::query()
        ->orderBy('scholarship_name')
        ->get();

    // Batches (for dropdown in modal)
    $batches = ScholarshipBatch::with(['semester', 'scholarship'])
        ->orderByDesc('id')
        ->get();

    // Existing scholars table
    $scholars = Scholar::with([
            'user.college',
            'user.course',
            'scholarship',
            'scholarshipBatch.semester'
        ])
        ->orderByDesc('id')
        ->paginate(10)
        ->withQueryString();

    // Search results (candidates)
    $candidates = collect();

    if ($q !== '') {
        $users = User::query()
            ->whereHas('userType', fn($x) => $x->where('name', 'Student'))
            ->where(function ($x) use ($q) {
                $x->where('lastname', 'ILIKE', "%{$q}%")
                  ->orWhere('firstname', 'ILIKE', "%{$q}%")
                  ->orWhere('student_id', 'ILIKE', "%{$q}%");
            })
            ->with(['college', 'course', 'yearLevel'])
            ->orderBy('lastname')
            ->orderBy('firstname')
            ->limit(50)
            ->get();

        $userIds = $users->pluck('id')->all();

        // who is enrolled (current sem + status enrolled)
        $enrolledMap = [];
        if ($currentSemester) {
            $enrolledMap = Enrollment::query()
                ->where('semester_id', $currentSemester->id)
                ->whereIn('user_id', $userIds)
                ->where('status', 'enrolled')
                ->pluck('user_id')
                ->flip()
                ->toArray();
        }

        // who is already scholar
        $scholarMap = Scholar::query()
            ->whereIn('student_id', $userIds)
            ->pluck('student_id')
            ->flip()
            ->toArray();

        // Build candidate rows with flags
        $candidates = $users->map(function ($u) use ($enrolledMap, $scholarMap, $currentSemester) {
            return (object) [
                'user' => $u,
                'is_enrolled_current' => isset($enrolledMap[$u->id]),
                'is_scholar' => isset($scholarMap[$u->id]),
                'current_semester_label' => $currentSemester
                    ? (($currentSemester->term ?? $currentSemester->semester_name ?? 'Semester') . ' ' . ($currentSemester->academic_year ?? ''))
                    : 'No current semester set',
            ];
        });
    }

    return view('coordinator.create-scholar', compact(
        'q',
        'currentSemester',
        'scholarships',   
        'batches',
        'scholars',
        'candidates'
    ));
}

   public function storeScholar(Request $request)
{
    $request->validate([
        'student_id'     => 'required|exists:users,id',
        'scholarship_id' => 'required|exists:scholarships,id',   // ✅ NEW
        'batch_id'       => 'nullable|exists:scholarship_batches,id', // ✅ optional now
        'date_added'     => 'required|date',
    ]);

    $currentSemester = Semester::where('is_current', true)->first();
    if (!$currentSemester) {
        return back()->with('error', 'No current semester is set. Please set a current semester first.');
    }

    // Must be enrolled this current semester
    $isEnrolled = Enrollment::where('user_id', $request->student_id)
        ->where('semester_id', $currentSemester->id)
        ->where('status', 'enrolled')
        ->exists();

    if (!$isEnrolled) {
        return back()->with('error', 'Student is NOT enrolled in the current semester. Cannot add as scholar.');
    }

    // Must NOT already be a scholar
    $alreadyScholar = Scholar::where('student_id', $request->student_id)->exists();
    if ($alreadyScholar) {
        return back()->with('error', 'This student is already a scholar.');
    }

    // Scholarship picked by user
    $scholarship = Scholarship::findOrFail($request->scholarship_id);

    // Detect if scholarship is batch-based (TES/TDP)
    $name = strtoupper(trim($scholarship->scholarship_name ?? ''));
    $isBatchBased = str_contains($name, 'TDP') || str_contains($name, 'TES');

    // If TES/TDP, batch is required
    if ($isBatchBased && empty($request->batch_id)) {
        return back()->withInput()->with('error', 'Batch is required for TES/TDP scholarships.');
    }

    // If batch is present, validate it belongs to scholarship
    $batchId = null;
    if (!empty($request->batch_id)) {
        $batch = ScholarshipBatch::query()
            ->where('id', $request->batch_id)
            ->where('scholarship_id', $scholarship->id)
            ->first();

        if (!$batch) {
            return back()->withInput()->with('error', 'Selected batch does not belong to the selected scholarship.');
        }

        $batchId = $batch->id;
    }

    Scholar::create([
        'student_id'     => $request->student_id,
        'scholarship_id' => $scholarship->id, // ✅ from scholarship dropdown
        'batch_id'       => $isBatchBased ? $batchId : null, // ✅ only for TES/TDP
        'updated_by'     => Auth::id(),
        'date_added'     => $request->date_added,
        'status'         => 'active', // ✅ auto
    ]);

    return redirect()->route('coordinator.scholars.create')
        ->with('success', 'Scholar added successfully.');
}


    //new/from superadmin
   public function enrollmentRecords(Request $request)
{
    // ===== Current semester (used as default for modal target) =====
    $currentSemester = Semester::where('is_current', true)->first();

    // ===== Dropdown data =====
    $semesters  = Semester::orderByDesc('start_date')->get();
    $colleges   = College::orderBy('college_name')->get();
    $courses    = Course::orderBy('course_name')->get();
    $yearLevels = YearLevel::orderBy('id')->get();

    // IMPORTANT:
    // Status list for FILTER UI (includes derived "not_enrolled" - not stored in DB)
    // Remove 'inactive' like you requested.
    $statuses = ['enrolled', 'dropped', 'graduated', 'not_enrolled'];

    // ===== Filter inputs =====
    $semesterId  = $request->get('semester_id'); // optional
    $collegeId   = $request->get('college_id');
    $courseId    = $request->get('course_id');
    $yearLevelId = $request->get('year_level_id');
    $status      = $request->get('status');      // enrolled/dropped/graduated/not_enrolled
    $q           = trim((string) $request->get('q', ''));

    // If status is "not_enrolled", we MUST know which semester we are comparing to.
    // If user didn't select semester, default to current semester.
    $compareSemesterId = $semesterId ?: ($currentSemester?->id);    

    // ===== MAIN RECORDS QUERY =====
    // Goal:
    // - Show students + their enrollment (if exists) for selected/current semester.
    // - If "not_enrolled", show students with NO enrollment in that compare semester.
    //
    // NOTE: This avoids querying Enrollment.status = 'not_enrolled' (enum error).
   $recordsQuery = Enrollment::query()
    ->with(['user.college', 'user.course', 'user.yearLevel', 'semester'])
    ->when($semesterId, fn($x) => $x->where('enrollments.semester_id', $semesterId))
    ->when($collegeId, fn($x) => $x->whereHas('user', fn($u) => $u->where('college_id', $collegeId)))
    ->when($courseId, fn($x) => $x->whereHas('user', fn($u) => $u->where('course_id', $courseId)))
    ->when($yearLevelId, fn($x) => $x->whereHas('user', fn($u) => $u->where('year_level_id', $yearLevelId)));

// Status filter (stored statuses only)
if (!empty($status) && $status !== 'not_enrolled') {
    $recordsQuery->where('enrollments.status', $status); // ✅ FIX
}

// Search filter
if ($q !== '') {
    $recordsQuery->whereHas('user', function ($u) use ($q) {
        $u->where('firstname', 'ILIKE', "%{$q}%")
          ->orWhere('lastname', 'ILIKE', "%{$q}%")
          ->orWhere('student_id', 'ILIKE', "%{$q}%");
    });
}

// ✅ Alphabetical sort (join users then qualify columns)
$recordsQuery->join('users', 'users.id', '=', 'enrollments.user_id')
    ->orderBy('users.lastname')
    ->orderBy('users.firstname')
    ->select('enrollments.*');

    // If "not_enrolled": we don’t show enrollments table rows.
    // We instead build "derived" records as fake rows OR show from User query.
    // Since your Blade expects Enrollment rows ($row->status etc),
    // easiest is: if not_enrolled -> create a USERS list but wrap as objects similar to Enrollment.
    if ($status === 'not_enrolled') {

        if (!$compareSemesterId) {
            // no current semester in DB, can't compare safely
            $records = collect([])->paginate(15);
        } else {
            // Students who do NOT have enrollment in compare semester
            $usersNotEnrolled = User::query()
                ->whereHas('userType', fn($t) => $t->where('name', 'Student'))
                ->with(['college', 'course', 'yearLevel'])
                ->when($collegeId, fn($x) => $x->where('college_id', $collegeId))
                ->when($courseId, fn($x) => $x->where('course_id', $courseId))
                ->when($yearLevelId, fn($x) => $x->where('year_level_id', $yearLevelId))
                ->when($q !== '', function ($x) use ($q) {
                    $x->where(function ($w) use ($q) {
                        $w->where('firstname', 'ILIKE', "%{$q}%")
                          ->orWhere('lastname', 'ILIKE', "%{$q}%")
                          ->orWhere('student_id', 'ILIKE', "%{$q}%");
                    });
                })
                ->whereDoesntHave('enrollments', fn($e) => $e->where('semester_id', $compareSemesterId))
                ->orderBy('lastname')
                ->orderBy('firstname')
                ->paginate(15)
                ->withQueryString();

            // Convert users to "fake enrollment rows" so your table stays the same
            $records = $usersNotEnrolled->through(function ($u) use ($compareSemesterId) {
                $fake = new Enrollment();
                $fake->setRelation('user', $u);
                $fake->status = 'not_enrolled'; // derived label (NOT stored in DB)
                $fake->semester_id = $compareSemesterId;
                $fake->setRelation('semester', Semester::find($compareSemesterId));
                return $fake;
            });
        }

    } else {
        $records = $recordsQuery->paginate(15)->withQueryString();
    }

    // ===== MODAL SEARCH (Enroll/Promote one student) =====
    $modalCandidates = collect();
    $modalQ = trim((string) $request->get('modal_q', ''));

    if ($request->get('show_add') === '1') {

        // Target semester for eligibility check:
        // Default to current semester (matches your Blade default selection).
        $targetSemesterId = $currentSemester?->id;

        $usersQuery = User::query()
            ->whereHas('userType', fn($t) => $t->where('name', 'Student'))
            ->with(['college', 'course', 'yearLevel'])
            ->when($modalQ !== '', function ($x) use ($modalQ) {
                $x->where(function ($w) use ($modalQ) {
                    $w->where('firstname', 'ILIKE', "%{$modalQ}%")
                      ->orWhere('lastname', 'ILIKE', "%{$modalQ}%")
                      ->orWhere('student_id', 'ILIKE', "%{$modalQ}%");
                });
            })
            ->orderBy('lastname')
            ->orderBy('firstname')
            ->limit(50)
            ->get();

        $modalCandidates = $usersQuery->map(function ($u) use ($targetSemesterId) {

            // Latest enrollment record (previous)
            $latestEnrollment = Enrollment::with('semester')
                ->where('user_id', $u->id)
                ->orderByDesc('semester_id') // or created_at if you prefer
                ->first();

            // Already enrolled in target/current semester?
            $alreadyInCurrent = false;
            if ($targetSemesterId) {
                $alreadyInCurrent = Enrollment::where('user_id', $u->id)
                    ->where('semester_id', $targetSemesterId)
                    ->exists();
            }

            return (object) [
                'user' => $u,
                'latest_enrollment' => $latestEnrollment,
                'already_in_current' => $alreadyInCurrent,
            ];
        });
    }

    return view('coordinator.enrollment-records', compact(
        'records',
        'semesters',
        'colleges',
        'courses',
        'yearLevels',
        'statuses',
        'currentSemester',
        'modalCandidates'
    ));
}


   public function enrollOneStudent(Request $request)
{
    $request->validate([
        'user_id' => 'required|exists:users,id',
        'semester_id' => 'required|exists:semesters,id',
    ]);

    $userId = (int) $request->user_id;
    $targetSemesterId = (int) $request->semester_id;

    // Block duplicates
    $exists = Enrollment::where('user_id', $userId)
        ->where('semester_id', $targetSemesterId)
        ->exists();

    if ($exists) {
        return back()->with('error', 'This student is already enrolled in the selected semester.');
    }

    $user = User::findOrFail($userId);

    // Auto course:
    // 1) user's current course_id
    // 2) fallback to latest enrollment course_id
    $courseId = $user->course_id;

    if (!$courseId) {
        $last = Enrollment::where('user_id', $userId)->orderByDesc('id')->first();
        $courseId = $last?->course_id;
    }

    if (!$courseId) {
        return back()->with('error', 'Cannot enroll this student because course is missing.');
    }

    Enrollment::create([
        'user_id' => $userId,
        'semester_id' => $targetSemesterId,
        'course_id' => $courseId,
        'year_level_id' => $user->year_level_id, // ✅ store per semester
        'status' => 'enrolled',
    ]);

    return redirect()->route('coordinator.enrollment-records')
        ->with('success', 'Student enrolled successfully.');
}


    public function addEnrollmentRecord(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'semester_id' => 'required|exists:semesters,id',
            'course_id' => 'required|exists:courses,id',
            'status' => 'required|string',
        ]);

        // Optional but recommended: prevent duplicate enrollment per semester
        $exists = Enrollment::where('user_id', $request->user_id)
            ->where('semester_id', $request->semester_id)
            ->exists();

        if ($exists) {
            return back()->withErrors(['user_id' => 'This student is already enrolled for the selected semester.']);

        }

        Enrollment::create([
            'user_id' => $request->user_id,
            'semester_id' => $request->semester_id,
            'course_id' => $request->course_id,
            'status' => $request->status,
        ]);

        return redirect()->route('coordinator.enrollment-records')->with('success', 'Enrollment record added successfully.');
    }

    // Manage Scholarship Batches
    public function manageScholarshipBatches(Request $request)
    {
        $q = trim((string) $request->get('q', ''));

        $batchesQuery = ScholarshipBatch::with(['scholarship', 'semester'])
            ->orderByDesc('id');

        if ($q !== '') {
            $batchesQuery->whereHas('scholarship', fn($s) => $s->where('scholarship_name', 'ILIKE', "%{$q}%"))
                ->orWhereHas('semester', fn($sem) => $sem->where('term', 'ILIKE', "%{$q}%")
                    ->orWhere('academic_year', 'ILIKE', "%{$q}%"))
                ->orWhere('batch_number', 'ILIKE', "%{$q}%");
        }

        $batches = $batchesQuery->paginate(10)->withQueryString();

        $scholarships = Scholarship::orderBy('scholarship_name')->get();
        $semesters = Semester::orderByDesc('start_date')->get();

        return view('coordinator.scholarship-batches', compact('batches', 'scholarships', 'semesters'));
    }


    public function createScholarshipBatch()


    {
        $scholarships = \App\Models\Scholarship::all();
        $semesters = Semester::all();
        return view('coordinator.create-scholarship-batch', compact('scholarships', 'semesters'));
    }

    public function storeScholarshipBatch(Request $request)
    {
        $request->validate([
            'scholarship_id' => 'required|exists:scholarships,id',
            'semester_id' => 'required|exists:semesters,id',
            'batch_number' => 'required|string',
        ]);

        $sch = Scholarship::findOrFail($request->scholarship_id);
        $name = strtoupper(trim($sch->scholarship_name ?? ''));

        if (!str_contains($name, 'TDP') && !str_contains($name, 'TES')) {
            return back()->with('error', 'Only TDP/TES scholarships can have batches.');
        }

        ScholarshipBatch::create($request->all());
        return redirect()->route('coordinator.scholarship-batches')->with('success', 'Batch created successfully.');
    }

    public function editScholarshipBatch($id)
{
    $batch = ScholarshipBatch::findOrFail($id);
    $scholarships = \App\Models\Scholarship::all();
    $semesters = Semester::all();
    return view('coordinator.edit-scholarship-batch', compact('batch', 'scholarships', 'semesters'));
}

public function updateScholarshipBatch(Request $request, $id)
{
    $request->validate([
        'scholarship_id' => 'required|exists:scholarships,id',
        'semester_id' => 'required|exists:semesters,id',
        'batch_number' => 'required|string',
    ]);

    $batch = ScholarshipBatch::findOrFail($id);
    $batch->update($request->only(['scholarship_id', 'semester_id', 'batch_number']));
    return redirect()->route('coordinator.scholarship-batches')->with('success', 'Batch updated successfully.');
}

public function destroyScholarshipBatch($id)
{
    ScholarshipBatch::findOrFail($id)->delete();
    return redirect()->route('coordinator.scholarship-batches')->with('success', 'Batch deleted successfully.');
}

public function confirmDeleteScholarshipBatch($id)
{
    $batch = ScholarshipBatch::findOrFail($id);
    return view('coordinator.confirm-delete-scholarship-batch', compact('batch'));
}


    // Manage Stipends
  public function manageStipends(Request $request)
{
    $activeSemesterId = $this->activeSemesterId();

    $scholarshipId = $request->get('scholarship_id');
    $batchId       = $request->get('batch_id');
    $releaseId     = $request->get('stipend_release_id');
    $q             = trim((string) $request->get('q', ''));
    $stipendStatus = $request->get('stipend_status');

    $scholarships = Scholarship::orderBy('scholarship_name')->get();

    // ✅ Batches list (optional filter by active semester)
    $batchesQuery = ScholarshipBatch::with(['semester', 'scholarship'])
        ->when($activeSemesterId, fn($x) => $x->where('semester_id', $activeSemesterId))
        ->when($scholarshipId, fn($x) => $x->where('scholarship_id', $scholarshipId))
        ->whereHas('stipendReleases', function ($r) {
            $r->where('status', 'for_release');
        })
        ->orderByDesc('batch_number');

    $batches = $batchesQuery->get();

    // ✅ Releases list
    $releasesQuery = StipendsRelease::with(['scholarshipBatch.semester'])
        ->where('status', 'for_release')
        ->when($activeSemesterId, function ($x) use ($activeSemesterId) {
            $x->whereHas('scholarshipBatch', fn($b) => $b->where('semester_id', $activeSemesterId));
        })
        ->orderByDesc('id');

    if ($batchId) {
        $releasesQuery->where('batch_id', $batchId);
    }

    $releases = $releasesQuery->get();

    // ✅ Current semester badge (optional)
    $currentSemester = $activeSemesterId ? Semester::find($activeSemesterId) : null;

    // ===============================
    // ✅ MODAL SCHOLARS LIST (Step 1)
    // ===============================
    // IMPORTANT:
    // We DO NOT exclude scholars here using $batchId from page,
    // because the modal batch is chosen via JS dropdown.
    //
    // Instead: we add a computed column "has_stipend_in_batch_db"
    // and let JS hide them when a batch is selected inside the modal.

    $modalScholarsQuery = Scholar::query()
        ->with(['user', 'scholarship', 'scholarshipBatch'])

        // optional scope: only show scholars whose batch belongs to active semester
        ->when($activeSemesterId, function ($x) use ($activeSemesterId) {
            $x->whereHas('scholarshipBatch', fn($b) => $b->where('semester_id', $activeSemesterId));
        })

        ->leftJoin('users', 'users.id', '=', 'scholars.student_id')

        ->leftJoin('enrollments', function ($join) use ($activeSemesterId) {
            $join->on('enrollments.user_id', '=', 'users.id')
                ->where('enrollments.semester_id', '=', $activeSemesterId);
        })

        ->select([
            'scholars.*',

            // enrollment status
            DB::raw("COALESCE(enrollments.status::text, 'not_existing') as enrollment_status_db"),

            // selectable = enrolled or graduated
            DB::raw("CASE WHEN enrollments.status::text IN ('enrolled','graduated') THEN 1 ELSE 0 END as is_selectable_db"),

            // ✅ NEW: already has stipend scheduled in scholar's OWN batch?
            // (any stipend row under any release schedule of that same batch)
            DB::raw("CASE WHEN EXISTS (
                SELECT 1
                FROM stipends
                JOIN stipend_releases ON stipend_releases.id = stipends.stipend_release_id
                WHERE stipends.scholar_id = scholars.id
                  AND stipend_releases.batch_id = scholars.batch_id
            ) THEN 1 ELSE 0 END as has_stipend_in_batch_db"),

            // sorting buckets: eligible first
            DB::raw("CASE
                WHEN enrollments.status::text IN ('enrolled','graduated') THEN 1
                WHEN enrollments.status::text = 'dropped' THEN 2
                WHEN enrollments.status IS NULL THEN 3
                ELSE 4
            END as sort_bucket")
        ])

        // page search q affects modal too
        ->when($q !== '', function ($x) use ($q) {
            $x->where(function ($w) use ($q) {
                $w->where('users.firstname', 'ILIKE', "%{$q}%")
                    ->orWhere('users.lastname', 'ILIKE', "%{$q}%")
                    ->orWhere('users.student_id', 'ILIKE', "%{$q}%");
            });
        })

        ->orderBy('sort_bucket')
        ->orderByDesc('is_selectable_db')
        ->orderBy('users.lastname')
        ->orderBy('users.firstname');

    $eligibleScholars = $modalScholarsQuery
        ->limit(300)
        ->get()
        ->map(function ($s) {
            $status = $s->enrollment_status_db;

            $s->is_selectable = ((int) $s->is_selectable_db) === 1;
            $s->has_stipend_in_batch = ((int) $s->has_stipend_in_batch_db) === 1;

            if ($status === 'not_existing') {
                $s->enrollment_status_label = 'NOT ENROLLED';
                $s->note = 'Not enrolled in the release semester.';
            } else {
                $s->enrollment_status_label = strtoupper(str_replace('_', ' ', $status));
                $s->note = $s->is_selectable ? 'Selectable' : 'Not selectable for stipend scheduling.';
            }

            // extra note if already scheduled
            if ($s->has_stipend_in_batch) {
                $s->note = 'Already scheduled in this batch.';
            }

            return $s;
        });

    // ===============================
    // ✅ STIPENDS TABLE (main list)
    // ===============================
    $stipendsQuery = Stipend::query()
        ->with([
            'scholar.user',
            'scholar.scholarship',
            'scholar.scholarshipBatch.semester',
            'stipendRelease.scholarshipBatch.semester'
        ])
        ->when($activeSemesterId, function ($x) use ($activeSemesterId) {
            $x->whereHas('stipendRelease.scholarshipBatch', fn($b) => $b->where('semester_id', $activeSemesterId));
        })
        ->when($scholarshipId, fn($x) => $x->whereHas('scholar', fn($s) => $s->where('scholarship_id', $scholarshipId)))
        ->when($batchId, fn($x) => $x->whereHas('scholar', fn($s) => $s->where('batch_id', $batchId)))
        ->when($releaseId, fn($x) => $x->where('stipend_release_id', $releaseId));

    if (!empty($stipendStatus)) {
        $stipendsQuery->where('status', $stipendStatus);
    }

    if ($q !== '') {
        $stipendsQuery->whereHas('scholar.user', function ($u) use ($q) {
            $u->where('firstname', 'ILIKE', "%{$q}%")
                ->orWhere('lastname', 'ILIKE', "%{$q}%")
                ->orWhere('student_id', 'ILIKE', "%{$q}%");
        });
    }

    $stipends = $stipendsQuery->orderByDesc('id')->paginate(15)->withQueryString();

    $claimUnreadCount = Notification::where('recipient_user_id', Auth::id())
    ->where('type', 'stipend')
    ->where('title', 'Cheque Claimed')
    ->where('is_read', false)
    ->count();

    return view('coordinator.manage-stipends', compact(
        'activeSemesterId',
        'currentSemester',
        'scholarships',
        'batches',
        'releases',
        'eligibleScholars',
        'stipends',
        'claimUnreadCount'
    ));
}


public function bulkAssignStipends(Request $request)
{
    $request->validate([
        'scholarship_id' => 'required|exists:scholarships,id',
        'batch_id' => 'required|exists:scholarship_batches,id',
        'stipend_release_id' => 'required|exists:stipend_releases,id',

        // ✅ datetime-local input
        'release_at' => 'required|date',

        'amount_received' => 'required|numeric|min:0',

        // ✅ IMPORTANT: this MUST match your DB constraint allowed values
        // If your DB only allows old ones, you MUST update the constraint.
        'status' => 'required|in:for_release,released,returned,waiting',

        'scholar_ids' => 'required|array|min:1',
        'scholar_ids.*' => 'exists:scholars,id',
    ]);

    $currentSemester = Semester::where('is_current', true)->first();
    if (!$currentSemester) {
        return back()->withInput()->with('error', 'No current semester is set.');
    }

    // Check batch belongs to scholarship
    $batch = ScholarshipBatch::where('id', $request->batch_id)
        ->where('scholarship_id', $request->scholarship_id)
        ->first();

    if (!$batch) {
        return back()->withInput()->with('error', 'Selected batch does not belong to selected scholarship.');
    }

    // Check release belongs to batch
    $release = StipendsRelease::where('id', $request->stipend_release_id)
        ->where('batch_id', $batch->id)
        ->first();

    if (!$release) {
        return back()->withInput()->with('error', 'Selected release does not belong to selected batch.');
    }

    $added = 0;
    $skipped = 0;

    DB::transaction(function () use ($request, $currentSemester, &$added, &$skipped) {

        foreach ($request->scholar_ids as $sid) {

            $scholar = Scholar::find($sid);
            if (!$scholar) { $skipped++; continue; }

            // ✅ must be enrolled OR graduated in current semester
            $ok = Enrollment::where('user_id', $scholar->student_id)
                ->where('semester_id', $currentSemester->id)
                ->whereIn('enrollments.status', ['enrolled', 'graduated'])
                ->exists();

            if (!$ok) { $skipped++; continue; }

            // prevent duplicate per scholar + release
            $exists = Stipend::where('scholar_id', $scholar->id)
                ->where('stipend_release_id', $request->stipend_release_id)
                ->exists();

            if ($exists) { $skipped++; continue; }

            Stipend::create([
                'scholar_id' => $scholar->id,
                'student_id' => $scholar->student_id,
                'stipend_release_id' => $request->stipend_release_id,
                'created_by' => Auth::id(),
                'updated_by' => Auth::id(),
                'amount_received' => $request->amount_received,
                'status' => $request->status,

                // ✅ SAVE DATETIME HERE
                'release_at' => $request->release_at,

                'received_at' => $request->status === 'released' ? now() : null,
            ]);

            $added++;
        }
    });

    return redirect()->route('coordinator.manage-stipends')
        ->with('success', "Bulk stipends saved. Added: {$added} | Skipped: {$skipped}");
}


public function bulkAssignStipendsV2(Request $request)
{
    $request->validate([
        'scholarship_id'     => 'required|exists:scholarships,id',
        'batch_id'           => 'required|exists:scholarship_batches,id',
        'stipend_release_id' => 'required|exists:stipend_releases,id',
        'release_at'         => 'required|date',
        'scholar_ids'        => 'required|array|min:1',
        'scholar_ids.*'      => 'exists:scholars,id',
    ]);

    $creatorId = Auth::id();
    if (!$creatorId) {
        return back()->with('error', 'Session expired. Please login again.');
    }

    // ✅ Get release + batch + semester
    $release = StipendsRelease::with('scholarshipBatch')->findOrFail($request->stipend_release_id);
    $targetSemesterId = $release->scholarshipBatch?->semester_id;

    if (!$targetSemesterId) {
        return back()->with('error', 'Release has no semester assigned.');
    }

    $added = 0;
    $skipped = 0;

    DB::transaction(function () use (
        $request, $release, $creatorId, $targetSemesterId, &$added, &$skipped
    ) {
        foreach ($request->scholar_ids as $sid) {

            $scholar = Scholar::find($sid);
            if (!$scholar) {
                $skipped++;
                continue;
            }

            // ✅ 1. Must be enrolled or graduated in the RELEASE semester
            $isEligible = Enrollment::query()
                ->where('user_id', $scholar->student_id)
                ->where('semester_id', $targetSemesterId)
                ->whereIn('status', ['enrolled', 'graduated'])
                ->exists();

            if (!$isEligible) {
                $skipped++;
                continue;
            }

            // ==================================================
            // ✅ 2. PREVENT DUPLICATE STIPEND IN SAME SEMESTER
            // ==================================================
            $alreadyScheduledThisBatchThisSem = Stipend::query()
                ->where('stipends.scholar_id', $scholar->id)
                ->join('stipend_releases', 'stipend_releases.id', '=', 'stipends.stipend_release_id')
                ->where('stipend_releases.batch_id', $release->batch_id)
                ->where('stipend_releases.semester_id', $targetSemesterId)
                ->exists();

            if ($alreadyScheduledThisBatchThisSem) { $skipped++; continue; }


            // ✅ 3. Create stipend (SAFE now)
            $stipend = Stipend::create([
                'scholar_id'         => $scholar->id,
                'student_id'         => $scholar->student_id,
                'stipend_release_id' => $release->id,
                'created_by'         => $creatorId,
                'updated_by'         => $creatorId,
                'amount_received'    => $release->amount,
                'status'             => 'for_release',
                'release_at'         => $request->release_at,
                'received_at'        => null,
            ]);

            Notification::create([
                'recipient_user_id' => $scholar->student_id,
                'created_by'        => $creatorId,
                'type'              => 'stipend', // ok (if your DB allows this column too)
                'title'             => 'Stipend Scheduled: ' . ($release->title ?? 'Stipend Release'),
                'message'           => 'You have a scheduled stipend release for ' . ($release->title ?? 'a release schedule') .
                                    '. Release date: ' . \Carbon\Carbon::parse($request->release_at)->format('M d, Y h:i A') . '.',

                // ✅ MUST be one of: announcement, stipend, scholarship
                'related_type'      => 'stipend',

                // ✅ You can keep this as the stipend_release id (since it’s the thing related)
                'related_id'        => $release->id,

                'link'              => route('student.notifications'),
                'is_read'           => false,
                'sent_at'           => now(),
            ]);
            $added++;
        }
    });

    return redirect()->route('coordinator.manage-stipends', [
            'scholarship_id'     => $request->scholarship_id,
            'batch_id'           => $request->batch_id,
            'stipend_release_id' => $request->stipend_release_id,
        ])
        ->with('success', "Scheduled stipends saved. Added: {$added} | Skipped: {$skipped}");
}

public function releasesByBatch(Request $request)
{
    $request->validate([
        'batch_id' => 'required|exists:scholarship_batches,id',
    ]);

    $releases = StipendsRelease::query()
        ->where('batch_id', $request->batch_id)
        ->where('status', 'for_release') // optional: keep only schedulable releases
        ->orderByDesc('id')
        ->get(['id', 'title', 'semester_id', 'amount', 'status', 'batch_id']);

    return response()->json($releases);
}

public function stipendPickMeta(Request $request)
{
    $request->validate([
        'release_id' => 'required|exists:stipend_releases,id',
    ]);

    $release = StipendsRelease::query()->findOrFail($request->release_id);

    $semesterId = (int) $release->semester_id;  // release-for semester
    $batchId    = (int) $release->batch_id;

    // Scholars in this batch
    $scholars = Scholar::query()
        ->where('batch_id', $batchId)
        ->get(['id', 'student_id']);

    $studentIds = $scholars->pluck('student_id')->values();

    // Enrollment status in RELEASE semester
    // user_id => status (enrolled/graduated/dropped) ; missing => not_enrolled
    $enrollmentStatusByUser = Enrollment::query()
        ->where('semester_id', $semesterId)
        ->whereIn('user_id', $studentIds)
        ->pluck('status', 'user_id')
        ->toArray();

    // Build status_map per scholar_id
    $statusMap = [];
    foreach ($scholars as $s) {
        $raw = $enrollmentStatusByUser[$s->student_id] ?? 'not_enrolled';

        $label = strtoupper(str_replace('_', ' ', (string)$raw));
        if ($raw === 'not_enrolled') $label = 'NOT ENROLLED';

        $statusMap[(string)$s->id] = $label;
    }

    // Eligible scholar ids = enrolled/graduated only
    $eligibleScholarIds = $scholars
        ->filter(function ($s) use ($enrollmentStatusByUser) {
            $st = $enrollmentStatusByUser[$s->student_id] ?? 'not_enrolled';
            return in_array($st, ['enrolled', 'graduated']);
        })
        ->pluck('id')
        ->values();

    // Blocked = already has stipend in SAME batch + SAME semester
    $blockedScholarIds = Stipend::query()
        ->join('stipend_releases', 'stipend_releases.id', '=', 'stipends.stipend_release_id')
        ->where('stipend_releases.batch_id', $batchId)
        ->where('stipend_releases.semester_id', $semesterId)
        ->pluck('stipends.scholar_id')
        ->unique()
        ->values();

    return response()->json([
        'semester_id'  => $semesterId,
        'eligible_ids' => $eligibleScholarIds,
        'blocked_ids'  => $blockedScholarIds,
        'status_map'   => $statusMap, // ✅ NEW
    ]);
}



    public function createStipend()
    {
        $scholars = Scholar::all();
        $releases = StipendsRelease::all();
        return view('coordinator.create-stipend', compact('scholars', 'releases'));
    }

   public function storeStipend(Request $request)
{
    $request->validate([
        'scholar_id' => 'required|exists:scholars,id',
        'stipend_release_id' => 'required|exists:stipend_releases,id',
        'amount_received' => 'required|numeric',
        'status' => 'required|in:for_release,released,returned,waiting',
    ]);

    $release = StipendsRelease::with('scholarshipBatch.semester')->findOrFail($request->stipend_release_id);
    $scholar = Scholar::with('user')->findOrFail($request->scholar_id);

    // ✅ Must match batch (important)
    if ((int)$scholar->batch_id !== (int)$release->batch_id) {
        return back()->withInput()->with('error', 'Scholar does not belong to the selected release batch.');
    }

    // ✅ Must be enrolled OR graduated in the schedule semester
    $targetSemesterId = $release->scholarshipBatch?->semester_id;

    if (!$targetSemesterId) {
        return back()->withInput()->with('error', 'Release schedule has no semester. Please fix the batch semester first.');
    }

    $ok = Enrollment::where('user_id', $scholar->student_id)
        ->where('semester_id', $targetSemesterId)
        ->whereIn('status', ['enrolled', 'graduated'])
        ->exists();

    if (!$ok) {
        return back()->withInput()->with('error', 'Scholar is not ENROLLED/GRADUATED in the schedule semester.');
    }

    // ✅ Create stipend row
    Stipend::create([
        'scholar_id' => $scholar->id,
        'student_id' => $scholar->student_id,
        'stipend_release_id' => $release->id,
        'created_by' => Auth::id(),
        'updated_by' => Auth::id(),
        'amount_received' => $request->amount_received,
        'status' => $request->status,

        // If you already have these columns, uncomment:
        // 'date_release' => now(),
        // 'received_at' => ($request->status === 'released' ? now() : null),
    ]);

    return redirect()->route('coordinator.manage-stipends')
        ->with('success', 'Stipend created successfully.');
}

public function eligibleScholarsForRelease(Request $request)
{
    $request->validate([
        'release_id' => 'required|exists:stipend_releases,id',
    ]);

    $release = StipendsRelease::with('scholarshipBatch')->findOrFail($request->release_id);

    // only allow for_release schedules
    if ($release->status !== 'for_release') {
        return response()->json(['eligible_ids' => []]);
    }

    $targetSemesterId = $release->semester_id;   // ✅ release semester (NOT current)
    $batchId = $release->batch_id;

    // scholars in this batch
    $scholars = Scholar::where('batch_id', $batchId)->get(['id','student_id']);

    $studentIds = $scholars->pluck('student_id');

    // which students are enrolled/graduated in that semester
    $eligibleStudentIds = Enrollment::query()
        ->where('semester_id', $targetSemesterId)
        ->whereIn('user_id', $studentIds)
        ->whereIn('status', ['enrolled','graduated'])
        ->pluck('user_id')
        ->toArray();

    // convert to scholar ids
    $eligibleScholarIds = $scholars
        ->filter(fn($s) => in_array($s->student_id, $eligibleStudentIds))
        ->pluck('id')
        ->values();

    return response()->json(['eligible_ids' => $eligibleScholarIds]);
}



    public function editStipend($id)
{
    $stipend = Stipend::findOrFail($id);
    $scholars = Scholar::all();
    $releases = StipendsRelease::all();
    return view('coordinator.edit-stipend', compact('stipend', 'scholars', 'releases'));
}

public function updateStipend(Request $request, $id)
{
    $request->validate([
        'status'      => 'required|in:for_release,released,returned,waiting',
        'received_at' => 'nullable|date',
    ]);

    $stipend = Stipend::findOrFail($id);

    $stipend->update([
        'status'      => $request->status,
        'received_at' => $request->status === 'released'
            ? $request->received_at
            : null,
        'updated_by'  => Auth::id(),
    ]);

    return redirect()
        ->route('coordinator.manage-stipends')
        ->with('success', 'Stipend updated successfully.');
}



public function destroyStipend($id)
{
    Stipend::findOrFail($id)->delete();
    return redirect()->route('coordinator.manage-stipends')->with('success', 'Stipend deleted successfully.');
}

public function confirmDeleteStipend($id)
{
    $stipend = Stipend::findOrFail($id);
    return view('coordinator.confirm-delete-stipend', compact('stipend'));
}

public function claimNotifications()
{
    $notifications = Notification::query()
    ->where('recipient_user_id', Auth::id())
    ->where('type', 'stipend')
    ->where('title', 'Cheque Claimed')
    ->orderByDesc('id')
    ->paginate(12);

    $unreadCount = Notification::where('recipient_user_id', Auth::id())
        ->where('type', 'stipend')
        ->where('title', 'Cheque Claimed')
        ->where('is_read', false)
        ->count();

    return view('coordinator.stipend-claim-notifications', compact('notifications', 'unreadCount'));
}

public function markNotificationRead($id)
{
    $n = Notification::where('id', $id)
        ->where('recipient_user_id', Auth::id())
        ->firstOrFail();

    if (!$n->is_read) {
        $n->update(['is_read' => true]);
    }

    return back()->with('success', 'Notification marked as read.');
}



    // Manage Stipend Releases
    public function manageStipendReleases(Request $request)
{
    $semesterId = $request->get('semester_id');

    $semesters = Semester::orderByDesc('start_date')->get();

    $releases = StipendsRelease::query()
        ->with([
            'semester', // ✅ release-for semester
            'scholarshipBatch.scholarship', // ✅ scholarship name
        ])
        ->when($semesterId, fn($q) => $q->where('semester_id', $semesterId))
        ->orderByDesc('id')
        ->paginate(10)
        ->withQueryString();

    return view('coordinator.manage-stipend-releases', compact(
        'releases',
        'semesters',
        'semesterId'
    ));
}

public function releaseStipend(Request $request, Stipend $stipend)
{
    $request->validate([
        'received_at' => 'required|date', // datetime-local
    ]);

    // ✅ Allow only FOR_RELEASE to be released (avoid double release)
    if ($stipend->status !== 'for_release') {
        return back()->with('error', 'This stipend is not in FOR RELEASE status.');
    }

    // Load relationships for nice notification message
    $stipend->load([
        'scholar.user',
        'stipendRelease',
        'scholar.scholarship',
        'scholar.scholarshipBatch',
    ]);

    $creatorId = Auth::id();

    DB::transaction(function () use ($request, $stipend, $creatorId) {

        $receivedAt = Carbon::parse($request->received_at);

        // ✅ Update stipend as released
        $stipend->update([
            'status'     => 'released',
            'received_at'=> $receivedAt,
            'updated_by' => $creatorId,
            // optional: if you want to also overwrite release_at:
            // 'release_at' => $receivedAt,
        ]);

        // ✅ Notify student
        $studentUserId = $stipend->scholar->student_id; // this is users.id

        $releaseTitle = $stipend->stipendRelease->title ?? 'Stipend Release';
        $amount = number_format((float) $stipend->amount_received, 2);

        Notification::create([
            'recipient_user_id' => $studentUserId,
            'created_by'        => $creatorId,
            'type'              => 'stipend', // your own label/category
            'title'             => 'Stipend Released',
            'message'           => "Your stipend has been released ({$releaseTitle}). Amount: ₱{$amount}. Released on: " .
                                   $receivedAt->format('M d, Y h:i A') . ".",
            // ✅ keep these consistent with your system
            'related_type'      => 'stipend',
            'related_id'        => $stipend->id,
            'link'              => route('student.stipend-history'),
            'is_read'           => false,
            'sent_at'           => now(),
        ]);
    });

    return back()->with('success', 'Stipend released and student notified.');
}


//stipend release
    public function createStipendRelease()
{
    // ✅ Only TDP/TES scholarships
    $scholarships = Scholarship::query()
        ->where(function($q){
            $q->whereRaw("UPPER(scholarship_name) LIKE '%TDP%'")
              ->orWhereRaw("UPPER(scholarship_name) LIKE '%TES%'");
        })
        ->orderBy('scholarship_name')
        ->get();

    // ✅ Batches (no need to show semester here in UI later)
    $batches = ScholarshipBatch::with(['scholarship'])
        ->orderByDesc('id')
        ->get();

    // ✅ Semester dropdown for RELEASE-FOR semester (can be past)
    $semesters = Semester::orderByDesc('start_date')->get();

    return view('coordinator.create-stipend-release', compact(
        'scholarships',
        'batches',
        'semesters'
    ));
}

   public function storeStipendRelease(Request $request)
{
    $request->validate([
        'scholarship_id' => 'required|exists:scholarships,id',
        'batch_id'       => 'required|exists:scholarship_batches,id',
        'semester_id'    => 'required|exists:semesters,id',
        'title'          => 'required|string|max:255',
        'amount'         => 'required|numeric|min:0',
        'status'         => 'required|in:for_billing,for_check,for_release,received',
        'notes'          => 'nullable|string',
    ]);

    $batch = ScholarshipBatch::where('id', $request->batch_id)
        ->where('scholarship_id', $request->scholarship_id)
        ->first();

    if (!$batch) {
        return back()->withInput()->with('error', 'Selected batch does not belong to the selected scholarship.');
    }

    StipendsRelease::create([
        'batch_id'    => $batch->id,
        'semester_id' => (int) $request->semester_id, // ✅ ensure numeric
        'title'       => $request->title,
        'amount'      => $request->amount,
        'status'      => $request->status,
        'notes'       => $request->notes,
        'created_by'  => Auth::id(),
        'updated_by'  => Auth::id(),
    ]);

    return redirect()->route('coordinator.manage-stipend-releases')
        ->with('success', 'Schedule created successfully.');
}

    public function editStipendRelease($id)
{
    $release = StipendsRelease::findOrFail($id);
    $batches = ScholarshipBatch::all();
    return view('coordinator.edit-stipend-release', compact('release', 'batches'));
}

public function updateStipendRelease(Request $request, $id)
{
    $request->validate([
        'batch_id'    => 'required|exists:scholarship_batches,id',
        'semester_id' => 'required|exists:semesters,id', // ✅ NEW
        'title'       => 'required|string',
        'amount'      => 'required|numeric',
        'status'      => 'required|in:for_billing,for_check,for_release,received',
        'notes'       => 'nullable|string',
    ]);

    $release = StipendsRelease::findOrFail($id);

    $release->update([
        'batch_id'    => $request->batch_id,
        'semester_id' => $request->semester_id, // ✅ NEW
        'title'       => $request->title,
        'amount'      => $request->amount,
        'status'      => $request->status,
        'notes'       => $request->notes,
        'updated_by'  => Auth::id(),
    ]);

    return redirect()->route('coordinator.manage-stipend-releases')
        ->with('success', 'Release updated successfully.');
}


public function destroyStipendRelease($id)
{
    StipendsRelease::findOrFail($id)->delete();
    return redirect()->route('coordinator.manage-stipend-releases')->with('success', 'Release deleted successfully.');
}

public function confirmDeleteStipendRelease($id)
{
    $release = StipendsRelease::findOrFail($id);
    return view('coordinator.confirm-delete-stipend-release', compact('release'));
}



//stipend release forms
public function releaseForm(StipendsRelease $release)
{
    $release->load(['scholarshipBatch.scholarship', 'semester', 'forms.uploader']);

    // dynamic active columns
    $columns = StipendReleaseFormColumn::query()
        ->where('is_active', true)
        ->orderBy('sort_order')
        ->get();

    // Scholars under this release batch
    $scholars = Scholar::query()
    ->with([
        'user.college',
        'user.course',
        'user.yearLevel', // fallback only
        'enrollments' => function ($q) use ($release) {
            $q->where('semester_id', $release->semester_id)
              ->with('yearLevel'); // ✅ use semester-based year level
        }
    ])
    ->where('batch_id', $release->batch_id)
    ->leftJoin('users', 'users.id', '=', 'scholars.student_id')
    ->select('scholars.*')
    ->orderBy('users.lastname')
    ->orderBy('users.firstname')
    ->get();

    return view('coordinator.stipend-release-form', compact('release','columns','scholars'));
}

public function releaseFormPrint(StipendsRelease $release)
{
    $release->load(['scholarshipBatch.scholarship', 'semester']);

    $columns = StipendReleaseFormColumn::query()
        ->where('is_active', true)
        ->orderBy('sort_order')
        ->get();

    $scholars = Scholar::query()
        ->with(['user.college','user.course','user.yearLevel'])
        ->where('batch_id', $release->batch_id)
        ->leftJoin('users', 'users.id', '=', 'scholars.student_id')
        ->select('scholars.*')
        ->orderBy('users.lastname')
        ->orderBy('users.firstname')
        ->get();

    return view('coordinator.stipend-release-form-print', compact('release','columns','scholars'));
}

public function releaseFormExcel(StipendsRelease $release)
{
    $columns = StipendReleaseFormColumn::query()
        ->where('is_active', true)
        ->orderBy('sort_order')
        ->get();

    return Excel::download(
        new \App\Exports\StipendReleaseFormExport($release->id, $columns->toArray()),
        'stipend_release_form_'.$release->id.'.xlsx'
    );
}

public function uploadReleaseForm(Request $request, StipendsRelease $release)
{
    $request->validate([
        'file' => 'required|mimes:xlsx,xls,csv,pdf|max:10240',
    ]);

    $file = $request->file('file');
    $path = $file->store("public/stipend-release-forms/{$release->id}");

    StipendReleaseForm::create([
        'stipend_release_id' => $release->id,
        'original_name'      => $file->getClientOriginalName(),
        'path'               => $path,
        'mime'               => $file->getClientMimeType(),
        'uploaded_by'        => Auth::id(),
    ]);

    return back()->with('success', 'Form uploaded and saved for reuse.');
}

public function downloadReleaseFormFile(StipendReleaseForm $form)
{
    // security: ensure coordinator only accesses via role middleware (already in your group)
    if (!Storage::exists($form->path)) {
        return back()->with('error', 'File not found.');
    }

    return Storage::download($form->path, $form->original_name);
}

    // Manage Announcements
       public function manageAnnouncements(Request $request)
{
    $tab = $request->get('tab', 'posted'); // posted | scheduled

    $base = Announcement::with('creator')
        ->withCount('views')
        ->orderByDesc('posted_at')
        ->orderByDesc('id');

    // ✅ POSTED = posted_at is now or past
    $postedAnnouncements = (clone $base)
        ->whereNotNull('posted_at')
        ->where('posted_at', '<=', now())
        ->paginate(10, ['*'], 'posted_page')
        ->withQueryString();

    // ✅ SCHEDULED = posted_at is future
    $scheduledAnnouncements = (clone $base)
        ->whereNotNull('posted_at')
        ->where('posted_at', '>', now())
        ->paginate(10, ['*'], 'scheduled_page')
        ->withQueryString();

    return view('coordinator.manage-announcements', compact(
        'tab',
        'postedAnnouncements',
        'scheduledAnnouncements'
    ));
}




 
     // Store announcement and send notifications (UPDATED: Add audience, scholar selection, emails, and notifications)
public function storeAnnouncement(Request $request)
{
    $request->validate([
        'title'               => 'required|string|max:255',
        'description'         => 'required|string',
        'audience'            => 'required|in:all_students,all_scholars,specific_students,specific_scholars',
        'posted_at'           => 'required|date',
        'selected_users'      => 'nullable|array',
        'selected_users.*'    => 'integer|exists:users,id',
        'selected_scholars'   => 'nullable|array',
        'selected_scholars.*' => 'integer|exists:scholars,id',
    ]);

    // If specific audience, require at least 1 recipient
    if (in_array($request->audience, ['specific_students', 'specific_scholars'])) {
        $count = $request->audience === 'specific_students'
            ? count($request->selected_users ?? [])
            : count($request->selected_scholars ?? []);

        if ($count < 1) {
            return back()->withInput()->with('error', 'Please select at least 1 recipient.');
        }
    }

    $postedAt = \Carbon\Carbon::parse($request->posted_at);

    $announcement = Announcement::create([
        'created_by'  => Auth::id(),
        'title'       => $request->title,
        'description' => $request->description,
        'audience'    => $request->audience,
        'posted_at'   => $postedAt,     // scheduled/posted time
        'notified_at' => null,          // not yet sent
    ]);

    if ($request->audience === 'specific_students') {
         $announcement->recipients()->sync($request->selected_users ?? []);
    }

    if ($request->audience === 'specific_scholars') {
        $userIds = \App\Models\Scholar::whereIn('id', $request->selected_scholars ?? [])
            ->pluck('student_id')
            ->toArray();

        $announcement->recipients()->sync($userIds);
    }


    // ✅ If posted time is NOW or past → send now
    if ($postedAt->lte(now())) {
        // Use dispatchSync for testing, switch to dispatch later if you want
        SendAnnouncementNotifications::dispatchSync(
            $announcement->id,
            Auth::id(),
            $request->audience,
            $request->selected_users ?? [],
            $request->selected_scholars ?? []
        );

        $announcement->update(['notified_at' => now()]);

        return redirect()->route('coordinator.manage-announcements')
            ->with('success', 'Announcement posted and notifications sent.');
    }

    // ✅ If future → scheduled only
    return redirect()->route('coordinator.manage-announcements')
        ->with('success', 'Announcement scheduled successfully.');
}




public function searchAnnouncementRecipients(Request $request)
{
    $type = $request->get('type'); // students | scholars
    $q = trim((string)$request->get('q',''));

    if ($type === 'students') {
        $users = User::whereHas('userType', fn($x) => $x->where('name','Student'))
            ->when($q !== '', function($x) use ($q){
                $x->where(function($w) use ($q){
                    $w->where('firstname','ILIKE',"%{$q}%")
                      ->orWhere('lastname','ILIKE',"%{$q}%")
                      ->orWhere('student_id','ILIKE',"%{$q}%")
                      ->orWhere('bisu_email','ILIKE',"%{$q}%");
                });
            })
            ->orderBy('lastname')->orderBy('firstname')
            ->limit(25)
            ->get(['id','firstname','lastname','student_id','bisu_email']);

        return response()->json($users);
    }

    if ($type === 'scholars') {
        $scholars = Scholar::with('user:id,firstname,lastname,student_id,bisu_email')
            ->when($q !== '', function($x) use ($q){
                $x->whereHas('user', function($u) use ($q){
                    $u->where('firstname','ILIKE',"%{$q}%")
                      ->orWhere('lastname','ILIKE',"%{$q}%")
                      ->orWhere('student_id','ILIKE',"%{$q}%")
                      ->orWhere('bisu_email','ILIKE',"%{$q}%");
                });
            })
            ->orderByDesc('id')
            ->limit(25)
            ->get(['id','student_id']);

        // return flattened info
        return response()->json($scholars->map(function($s){
            return [
                'id' => $s->id,
                'user_id' => $s->user?->id,
                'firstname' => $s->user?->firstname,
                'lastname' => $s->user?->lastname,
                'student_id' => $s->user?->student_id,
                'bisu_email' => $s->user?->bisu_email,
            ];
        }));
    }

    return response()->json([]);
}

public function destroyAnnouncement(Announcement $announcement)
{
    // Optional: only allow owner or coordinator role checks here
    // if ($announcement->created_by !== Auth::id()) abort(403);

    DB::transaction(function () use ($announcement) {
        // delete related notifications (if you want cleanup)
        Notification::where('related_type', 'announcement')
            ->where('related_id', $announcement->id)
            ->delete();

        $announcement->delete();
    });

    return redirect()->route('coordinator.manage-announcements')
        ->with('success', 'Announcement deleted successfully.');
}

public function cancelAnnouncementSchedule(Announcement $announcement)
{
    // Safety: only allow cancel if it's still future
    if ($announcement->posted_at && $announcement->posted_at->isFuture()) {
        // Option A: cancel schedule => post NOW
        // $announcement->update(['posted_at' => now()]);

        // Option B: cancel schedule => remove schedule (it will not show in posted/scheduled)
        $announcement->update(['posted_at' => null]);
    }

    return back()->with('success', 'Schedule cancelled.');
}

public function rescheduleAnnouncement(Request $request, Announcement $announcement)
{
    $request->validate([
        'posted_at' => ['required', 'date', 'after:now'],
    ]);

    $announcement->update([
        'posted_at' => $request->posted_at,
        // optional: if you use notified_at to ensure re-send only once,
        // set it back to null when rescheduling so it can notify again at new time:
        'notified_at' => null,
    ]);

    return back()->with('success', 'Scheduled time updated.');
}





    // Manage Scholarships
public function manageScholarships()
{
    $scholarships = \App\Models\Scholarship::with('creator')->paginate(10);
    return view('coordinator.manage-scholarships', compact('scholarships'));
}

public function createScholarship()
{
    return view('coordinator.create-scholarship');
}

public function storeScholarship(Request $request)
{
    $request->validate([
        'scholarship_name' => 'required|string',
        'description' => 'required|string',
        'requirements' => 'required|string',
        'benefactor' => 'required|string',
        'status' => 'required|in:open,closed',
        'application_date' => 'nullable|date',
        'deadline' => 'nullable|date|after_or_equal:application_date', // optional but nice
    ]);


    \App\Models\Scholarship::create([
        'scholarship_name' => $request->scholarship_name,
        'description' => $request->description,
        'requirements' => $request->requirements,
        'benefactor' => $request->benefactor,
        'status' => $request->status,
        'application_date' => $request->application_date,
        'deadline' => $request->deadline,
        'created_by' => Auth::id(),
        'updated_by' => Auth::id(),
    ]);

    return redirect()->route('coordinator.manage-scholarships')->with('success', 'Scholarship created successfully.');
}

public function editScholarship($id)
{
    $scholarship = \App\Models\Scholarship::findOrFail($id);
    return view('coordinator.edit-scholarship', compact('scholarship'));
}

public function updateScholarship(Request $request, $id)
{
    $request->validate([
        'scholarship_name' => 'required|string',
        'description' => 'required|string',
        'requirements' => 'required|string',
        'benefactor' => 'required|string',
        'status' => 'required|in:open,closed',
        'application_date' => 'nullable|date',
        'deadline' => 'nullable|date|after_or_equal:application_date',
    ]);

    $scholarship = \App\Models\Scholarship::findOrFail($id);
    $scholarship->update([
        'scholarship_name' => $request->scholarship_name,
        'description' => $request->description,
        'requirements' => $request->requirements,
        'benefactor' => $request->benefactor,
        'status' => $request->status,
        'application_date' => $request->application_date,
        'deadline' => $request->deadline,
        'updated_by' => Auth::id(),
    ]);

    return redirect()->route('coordinator.manage-scholarships')->with('success', 'Scholarship updated successfully.');
}


public function confirmDeleteScholarship($id)
{
    $scholarship = Scholarship::findOrFail($id);
    return view('coordinator.confirm-delete-scholarship', compact('scholarship'));
}

public function destroyScholarship($id)
{
    $scholarship = Scholarship::findOrFail($id);
    $scholarship->delete();

    return redirect()->route('coordinator.manage-scholarships')
        ->with('success', 'Scholarship deleted successfully.');
}


//reports

public function reports()
{
    $activeSemesterId = $this->activeSemesterId();
    $semesters = Semester::orderByDesc('start_date')->get();
    $activeSemester = $activeSemesterId ? Semester::find($activeSemesterId) : null;

    return view('coordinator.reports', compact('semesters', 'activeSemesterId', 'activeSemester'));
}

public function reportSummaryOfScholarships(Request $request)
{
    $activeSemesterId = $this->activeSemesterId();
    $semesterId = (int) ($request->get('semester_id') ?: $activeSemesterId);

    $semester = $semesterId ? Semester::findOrFail($semesterId) : null;

    // If no semester selected/found, fallback safe values
    $academicYear = $semester?->academic_year;

    // ✅ Find both semesters of the SAME academic year (Docx wants 1st + 2nd)
    // We pick by start_date ordering to avoid fragile "term text" matching.
    $semestersOfAY = $academicYear
        ? Semester::where('academic_year', $academicYear)
            ->orderBy('start_date')
            ->get()
        : collect();

    $sem1 = $semestersOfAY->get(0); // usually 1st semester
    $sem2 = $semestersOfAY->get(1); // usually 2nd semester

    $sem1Id = $sem1?->id;
    $sem2Id = $sem2?->id;

    // ✅ Build rows: one scholarship per row with 2 counts (sem1 + sem2)
    // RULE (same as your reports):
    // - include non-batch scholars ALWAYS (scholars.batch_id is NULL)
    // - include batch scholars ONLY if scholarship_batches.semester_id = target semester
    $rows = Scholarship::query()
        ->leftJoin('scholars', 'scholars.scholarship_id', '=', 'scholarships.id')
        ->leftJoin('scholarship_batches', 'scholarship_batches.id', '=', 'scholars.batch_id')
        ->select([
            'scholarships.id',
            'scholarships.scholarship_name',
            'scholarships.benefactor',

            // ✅ 1st sem count
            DB::raw("
                COUNT(scholars.id) FILTER (
                    WHERE " . ($sem1Id ? "(scholars.batch_id IS NULL OR scholarship_batches.semester_id = {$sem1Id})" : "false") . "
                ) as total_sem1
            "),

            // ✅ 2nd sem count
            DB::raw("
                COUNT(scholars.id) FILTER (
                    WHERE " . ($sem2Id ? "(scholars.batch_id IS NULL OR scholarship_batches.semester_id = {$sem2Id})" : "false") . "
                ) as total_sem2
            "),
        ])
        ->groupBy('scholarships.id', 'scholarships.scholarship_name', 'scholarships.benefactor')
        ->orderBy('scholarships.scholarship_name')
        ->get();

    $grandSem1 = (int) $rows->sum('total_sem1');
    $grandSem2 = (int) $rows->sum('total_sem2');

    return view('coordinator.reports.summary-of-scholarships', compact(
        'semesterId',
        'semester',
        'academicYear',
        'sem1',
        'sem2',
        'rows',
        'grandSem1',
        'grandSem2'
    ));
}

public function reportListOfScholars(Request $request)
{
    $activeSemesterId = $this->activeSemesterId();
    $semesterId = (int) ($request->get('semester_id') ?: $activeSemesterId);

    $semester = $semesterId ? Semester::findOrFail($semesterId) : null;

    // ✅ define academicYear BEFORE the query chain
    $academicYear = $semester?->academic_year;

    $scholars = Scholar::query()
        ->with([
            'scholarship',
            'scholarshipBatch.semester',
            'user' => function ($q) use ($semesterId) {
                $q->with([
                    'course',
                    'yearLevel', // fallback
                    'enrollments' => function ($e) use ($semesterId) {
                        $e->where('semester_id', $semesterId)
                          ->with('yearLevel');
                    }
                ]);
            },
        ])

        // ✅ AY-based filter (same logic as dashboard)
        ->when($academicYear, function ($q) use ($academicYear) {
            $q->where(function ($w) use ($academicYear) {
                $w->whereNull('scholars.batch_id')
                  ->orWhereHas('scholarshipBatch.semester', function ($sem) use ($academicYear) {
                      $sem->where('academic_year', $academicYear);
                  });
            });
        })

        // ✅ keep alphabetical
        ->leftJoin('users', 'users.id', '=', 'scholars.student_id')
        ->select('scholars.*')
        ->orderBy('users.lastname')
        ->orderBy('users.firstname')
        ->get();

    return view('coordinator.reports.list-of-scholars', compact(
        'semester',
        'semesterId',
        'scholars'
    ));
}

public function reportSummaryOfScholarshipsPdf(Request $request)
{
    $activeSemesterId = $this->activeSemesterId();
    $semesterId = (int) ($request->get('semester_id') ?: $activeSemesterId);

    $semester = $semesterId ? Semester::findOrFail($semesterId) : null;
    $academicYear = $semester?->academic_year;

    $semestersOfAY = $academicYear
        ? Semester::where('academic_year', $academicYear)->orderBy('start_date')->get()
        : collect();

    $sem1 = $semestersOfAY->get(0);
    $sem2 = $semestersOfAY->get(1);

    $sem1Id = $sem1?->id;
    $sem2Id = $sem2?->id;

    $rows = Scholarship::query()
        ->leftJoin('scholars', 'scholars.scholarship_id', '=', 'scholarships.id')
        ->leftJoin('scholarship_batches', 'scholarship_batches.id', '=', 'scholars.batch_id')
        ->select([
            'scholarships.id',
            'scholarships.scholarship_name',
            'scholarships.benefactor',

            DB::raw("
                COUNT(scholars.id) FILTER (
                    WHERE " . ($sem1Id ? "(scholars.batch_id IS NULL OR scholarship_batches.semester_id = {$sem1Id})" : "false") . "
                ) as total_sem1
            "),

            DB::raw("
                COUNT(scholars.id) FILTER (
                    WHERE " . ($sem2Id ? "(scholars.batch_id IS NULL OR scholarship_batches.semester_id = {$sem2Id})" : "false") . "
                ) as total_sem2
            "),
        ])
        ->groupBy('scholarships.id', 'scholarships.scholarship_name', 'scholarships.benefactor')
        ->orderBy('scholarships.scholarship_name')
        ->get();

    $grandSem1 = (int) $rows->sum('total_sem1');
    $grandSem2 = (int) $rows->sum('total_sem2');

    $pdf = Pdf::loadView('coordinator.reports.pdf.summary-of-scholarships', compact(
        'semesterId',
        'semester',
        'academicYear',
        'sem1',
        'sem2',
        'rows',
        'grandSem1',
        'grandSem2'
    ))->setPaper('a4', 'portrait');

    $fileName = 'summary_of_scholarships_' . ($academicYear ?: 'report') . '.pdf';
    return $pdf->download($fileName);
}

public function reportListOfScholarsPdf(Request $request)
{
    $activeSemesterId = $this->activeSemesterId();
    $semesterId = (int) ($request->get('semester_id') ?: $activeSemesterId);

    $semester = $semesterId ? Semester::findOrFail($semesterId) : null;
    $academicYear = $semester?->academic_year;

    $scholars = Scholar::query()
        ->with([
            'scholarship',
            'scholarshipBatch.semester',
            'user' => function ($q) use ($semesterId) {
                $q->with([
                    'course',
                    'yearLevel',
                    'enrollments' => function ($e) use ($semesterId) {
                        $e->where('semester_id', $semesterId)->with('yearLevel');
                    }
                ]);
            },
        ])
        ->when($academicYear, function ($q) use ($academicYear) {
            $q->where(function ($w) use ($academicYear) {
                $w->whereNull('scholars.batch_id')
                  ->orWhereHas('scholarshipBatch.semester', function ($sem) use ($academicYear) {
                      $sem->where('academic_year', $academicYear);
                  });
            });
        })
        ->leftJoin('users', 'users.id', '=', 'scholars.student_id')
        ->select('scholars.*')
        ->orderBy('users.lastname')
        ->orderBy('users.firstname')
        ->get();

    $pdf = Pdf::loadView('coordinator.reports.pdf.list-of-scholars', compact(
        'semester',
        'semesterId',
        'academicYear',
        'scholars'
    ))->setPaper('a4', 'portrait');

    $fileName = 'list_of_scholars_' . ($academicYear ?: 'report') . '.pdf';
    return $pdf->download($fileName);
}


public function reportSummaryOfScholarshipsDocx(Request $request)
{
    $activeSemesterId = $this->activeSemesterId();
    $semesterId = (int) ($request->get('semester_id') ?: $activeSemesterId);

    $semester = $semesterId ? Semester::findOrFail($semesterId) : null;
    $academicYear = $semester?->academic_year;

    $semestersOfAY = $academicYear
        ? Semester::where('academic_year', $academicYear)->orderBy('start_date')->get()
        : collect();

    $sem1 = $semestersOfAY->get(0);
    $sem2 = $semestersOfAY->get(1);

    $sem1Id = $sem1?->id;
    $sem2Id = $sem2?->id;

    $rows = Scholarship::query()
        ->leftJoin('scholars', 'scholars.scholarship_id', '=', 'scholarships.id')
        ->leftJoin('scholarship_batches', 'scholarship_batches.id', '=', 'scholars.batch_id')
        ->select([
            'scholarships.id',
            'scholarships.scholarship_name',
            'scholarships.benefactor',
            DB::raw("
                COUNT(scholars.id) FILTER (
                    WHERE " . ($sem1Id ? "(scholars.batch_id IS NULL OR scholarship_batches.semester_id = {$sem1Id})" : "false") . "
                ) as total_sem1
            "),
            DB::raw("
                COUNT(scholars.id) FILTER (
                    WHERE " . ($sem2Id ? "(scholars.batch_id IS NULL OR scholarship_batches.semester_id = {$sem2Id})" : "false") . "
                ) as total_sem2
            "),
        ])
        ->groupBy('scholarships.id', 'scholarships.scholarship_name', 'scholarships.benefactor')
        ->orderBy('scholarships.scholarship_name')
        ->get();

    $grandSem1 = (int) $rows->sum('total_sem1');
    $grandSem2 = (int) $rows->sum('total_sem2');

    // ======================
    // DOCX BUILD
    // ======================
    $phpWord = new PhpWord();
    $section = $phpWord->addSection([
        'marginTop' => 720, 'marginBottom' => 720, 'marginLeft' => 720, 'marginRight' => 720
    ]);

    $title = "SUMMARY OF SCHOLARSHIPS";
    $subtitle = "Candijay Campus • " . ($academicYear ?: 'N/A');

    $section->addText($title, ['bold' => true, 'size' => 14], ['alignment' => 'center']);
    $section->addText($subtitle, ['size' => 11], ['alignment' => 'center']);
    $section->addTextBreak(1);

    $sem1Label = $sem1 ? (($sem1->term ?? '1st Semester') . " AY " . ($sem1->academic_year ?? '')) : "1st Semester";
    $sem2Label = $sem2 ? (($sem2->term ?? '2nd Semester') . " AY " . ($sem2->academic_year ?? '')) : "2nd Semester";

    $tableStyle = ['borderSize' => 6, 'borderColor' => '000000', 'cellMargin' => 80];
    $phpWord->addTableStyle('ReportTable', $tableStyle);
    $table = $section->addTable('ReportTable');

    // Header row
    $table->addRow();
    $table->addCell(5200)->addText('Scholarship', ['bold' => true]);
    $table->addCell(2500)->addText($sem1Label, ['bold' => true]);
    $table->addCell(2500)->addText($sem2Label, ['bold' => true]);

    // Data rows
    foreach ($rows as $r) {
        $table->addRow();
        $table->addCell(5200)->addText((string) $r->scholarship_name);
        $table->addCell(2500)->addText((string) $r->total_sem1);
        $table->addCell(2500)->addText((string) $r->total_sem2);
    }

    // Grand total row
    $table->addRow();
    $table->addCell(5200)->addText('GRAND TOTAL', ['bold' => true]);
    $table->addCell(2500)->addText((string) $grandSem1, ['bold' => true]);
    $table->addCell(2500)->addText((string) $grandSem2, ['bold' => true]);

    // Save to temp + download
    $fileName = 'summary_of_scholarships_' . ($academicYear ?: 'AY') . '.docx';
    $tempPath = storage_path('app/temp/' . $fileName);

    if (!is_dir(dirname($tempPath))) {
        mkdir(dirname($tempPath), 0775, true);
    }

    $writer = IOFactory::createWriter($phpWord, 'Word2007');
    $writer->save($tempPath);

    return response()->download($tempPath, $fileName)->deleteFileAfterSend(true);
}

public function reportListOfScholarsDocx(Request $request)
{
    $activeSemesterId = $this->activeSemesterId();
    $semesterId = (int) ($request->get('semester_id') ?: $activeSemesterId);

    $semester = $semesterId ? Semester::findOrFail($semesterId) : null;
    $academicYear = $semester?->academic_year;

    $scholars = Scholar::query()
        ->with(['scholarship', 'scholarshipBatch.semester', 'user.course'])
        ->when($academicYear, function ($q) use ($academicYear) {
            $q->where(function ($w) use ($academicYear) {
                $w->whereNull('scholars.batch_id')
                  ->orWhereHas('scholarshipBatch.semester', function ($sem) use ($academicYear) {
                      $sem->where('academic_year', $academicYear);
                  });
            });
        })
        ->leftJoin('users', 'users.id', '=', 'scholars.student_id')
        ->select('scholars.*')
        ->orderBy('users.lastname')
        ->orderBy('users.firstname')
        ->get();

    // ======================
    // DOCX BUILD
    // ======================
    $phpWord = new PhpWord();
    $section = $phpWord->addSection([
        'marginTop' => 720, 'marginBottom' => 720, 'marginLeft' => 720, 'marginRight' => 720
    ]);

    $title = "LIST OF SCHOLARS AND GRANTEES";
    $subtitle = "Candijay Campus • " . (($semester?->term ?? 'Semester') . " AY " . ($academicYear ?? 'N/A'));

    $section->addText($title, ['bold' => true, 'size' => 14], ['alignment' => 'center']);
    $section->addText($subtitle, ['size' => 11], ['alignment' => 'center']);
    $section->addTextBreak(1);

    $phpWord->addTableStyle('ScholarTable', ['borderSize' => 6, 'borderColor' => '000000', 'cellMargin' => 80]);
    $table = $section->addTable('ScholarTable');

    // Header
    $table->addRow();
    $table->addCell(700)->addText('#', ['bold' => true]);
    $table->addCell(3000)->addText('Student Name', ['bold' => true]);
    $table->addCell(2600)->addText('Course', ['bold' => true]);
    $table->addCell(2800)->addText('Scholarship', ['bold' => true]);

    $i = 1;
    foreach ($scholars as $s) {
        $user = $s->user;
        $name = $user ? ($user->lastname . ', ' . $user->firstname) : 'N/A';
        $course = $user?->course?->course_name ?? 'N/A';
        $schName = $s->scholarship?->scholarship_name ?? 'N/A';

        $table->addRow();
        $table->addCell(700)->addText((string) $i++);
        $table->addCell(3000)->addText($name);
        $table->addCell(2600)->addText($course);
        $table->addCell(2800)->addText($schName);
    }

    $fileName = 'list_of_scholars_' . (($semester?->term ?? 'Semester') . '_AY_' . ($academicYear ?? 'AY')) . '.docx';
    $fileName = str_replace(['/', '\\'], '-', $fileName);

    $tempPath = storage_path('app/temp/' . $fileName);
    if (!is_dir(dirname($tempPath))) {
        mkdir(dirname($tempPath), 0775, true);
    }

    $writer = IOFactory::createWriter($phpWord, 'Word2007');
    $writer->save($tempPath);

    return response()->download($tempPath, $fileName)->deleteFileAfterSend(true);
}

public function updateScholarStatus(Request $request, Scholar $scholar)
{
    $request->validate([
        'status'       => 'required|in:active,inactive',
        'date_removed' => 'nullable|date',
    ]);

    // If setting inactive, date_removed required (use today if not provided)
    if ($request->status === 'inactive') {
        $dateRemoved = $request->date_removed ?: now()->toDateString();

        $scholar->update([
            'status'       => 'inactive',
            'date_removed' => $dateRemoved,
            'updated_by'   => Auth::id(),
        ]);

        return back()->with('success', 'Scholar marked as NO LONGER a scholar.');
    }

    // If setting active again (restore)
    $scholar->update([
        'status'       => 'active',
        'date_removed' => null,
        'updated_by'   => Auth::id(),
    ]);

    return back()->with('success', 'Scholar restored as ACTIVE.');
}

public function destroyScholar(Scholar $scholar)
{
    // Safety: don’t allow delete if there are stipends
    $hasStipends = Stipend::where('scholar_id', $scholar->id)->exists();
    if ($hasStipends) {
        return back()->with('error', 'Cannot delete this scholar because stipend records exist. Use Remove (inactive) instead.');
    }

    $scholar->delete();
    return back()->with('success', 'Scholar record deleted permanently.');
}



//BULK UPLOAD SCHOLARS

public function uploadScholars()
{
    $currentSemester = Semester::where('is_current', true)->first();

    // For the modal dropdown batch list
    $batches = ScholarshipBatch::with(['semester', 'scholarship'])
        ->orderByDesc('id')
        ->get();

    // optional (only if your blade uses $scholarships)
    $scholarships = Scholarship::orderBy('scholarship_name')->get();

    return view('coordinator.upload-scholars', compact('batches', 'currentSemester', 'scholarships'));
}



public function processUploadedScholars(Request $request)
{
    $request->validate([
        'file' => 'required|mimes:xlsx,xls,csv|max:10240',
    ]);

    $currentSemester = Semester::where('is_current', true)->first();
    if (!$currentSemester) {
        return back()->with('error', 'No current semester is set.');
    }

    // Read excel/csv as raw array (first sheet only)
    $sheets = Excel::toArray([], $request->file('file'));
    $rows = $sheets[0] ?? [];

    if (count($rows) < 2) {
        return back()->with('error', 'File looks empty or has no data rows.');
    }

    $headers = $rows[0]; // first row is header row

    // ✅ Allowed header variants (you can add more anytime)
    $firstAliases = ['firstname', 'first name', 'first_name', 'givenname', 'given name', 'fname', 'first'];
    $lastAliases  = ['lastname', 'last name', 'last_name', 'surname', 'lname', 'last'];
    $yearAliases  = ['yearlevel', 'year level', 'year_level', 'yrlevel', 'yr level', 'year', 'grade'];
    $statAliases  = ['enrollmentstatus', 'enrollment status', 'enrollstatus', 'enroll status', 'status'];

    $idxFirst = $this->findColIndex($headers, $firstAliases);
    $idxLast  = $this->findColIndex($headers, $lastAliases);
    $idxYear  = $this->findColIndex($headers, $yearAliases);
    $idxStat  = $this->findColIndex($headers, $statAliases); // optional

    if ($idxFirst === null || $idxLast === null) {
        return back()->with('error', 'Cannot detect FIRSTNAME/LASTNAME columns. Please check your header row.');
    }

    $results = [];

    // Start from row 2 (index 1) because row 1 is headers
    foreach (array_slice($rows, 1) as $rIndex => $row) {

        $first = trim((string)($row[$idxFirst] ?? ''));
        $last  = trim((string)($row[$idxLast] ?? ''));

        if ($first === '' && $last === '') {
            continue; // skip empty rows
        }

        $yearUploaded = $idxYear !== null ? trim((string)($row[$idxYear] ?? '')) : '';
        $statusUploadedRaw = $idxStat !== null ? (string)($row[$idxStat] ?? '') : '';
        $statusUploaded = $this->normEnrollStatus($statusUploadedRaw); // normalize uploaded status

        // ✅ Match user in database (basic exact match; you can improve later with fuzzy matching)
        $user = User::query()
            ->whereHas('userType', fn($q) => $q->where('name', 'Student'))
            ->where('firstname', 'ILIKE', $first)
            ->where('lastname', 'ILIKE', $last)
            ->first();

        // Default: not enrolled
        $dbEnrollStatus = 'not_enrolled';

        // Scholar status + scholarship name
        $isScholar = false;
        $existingScholarshipName = null;

        if ($user) {
            // DB enrollment status for current semester
            $enrollment = Enrollment::query()
                ->where('user_id', $user->id)
                ->where('semester_id', $currentSemester->id)
                ->first();

            $dbEnrollStatus = $enrollment?->status ?? 'not_enrolled';

            // Check if already scholar + get scholarship name
            $existingScholar = Scholar::with('scholarship')
                ->where('student_id', $user->id)
                ->first();

            if ($existingScholar) {
                $isScholar = true;
                $existingScholarshipName = $existingScholar->scholarship->scholarship_name ?? 'SCHOLAR';
            }
        }

        $verified = !empty($user);
        $canSelect = $verified && ($dbEnrollStatus === 'enrolled') && !$isScholar;

        $sortLast  = mb_strtolower($last);
        $sortFirst = mb_strtolower($first);


        $results[] = [
            'row' => $rIndex + 2,
            'data' => [
                'first_name' => $first,
                'last_name' => $last,
                'year_level' => $yearUploaded,
                'uploaded_status' => $statusUploaded,
            ],
            'user' => $user ? [
                'id' => $user->id,
                'firstname' => $user->firstname,
                'lastname' => $user->lastname,
            ] : null,
            'enrollment_status' => $dbEnrollStatus,
            'is_scholar' => $isScholar,
            'existing_scholarship_name' => $existingScholarshipName,

            // ✅ NEW
            'can_select' => $canSelect,
            'sort_last'  => $sortLast,
            'sort_first' => $sortFirst,
        ];
    }

    usort($results, function ($a, $b) {
    $aSel = !empty($a['can_select']);
    $bSel = !empty($b['can_select']);

    // 1) selectable first
    if ($aSel !== $bSel) return $aSel ? -1 : 1;

    // 2) only enforce alphabetical priority strongly on selectable
    if ($aSel && $bSel) {
        $c = strcmp($a['sort_last'] ?? '', $b['sort_last'] ?? '');
        if ($c !== 0) return $c;
        return strcmp($a['sort_first'] ?? '', $b['sort_first'] ?? '');
    }

    // 3) (optional) keep non-selectable stable-ish by name too (looks cleaner)
    $c = strcmp($a['sort_last'] ?? '', $b['sort_last'] ?? '');
    if ($c !== 0) return $c;
    return strcmp($a['sort_first'] ?? '', $b['sort_first'] ?? '');
});


    return redirect()->route('coordinator.scholars.upload')
        ->with('results', $results)
        ->with('success', 'File processed. Review comparison results.');
}



public function addSelectedUploadedScholars(Request $request)
{
    $request->validate([
        'scholarship_id'    => 'required|exists:scholarships,id',
        'batch_id'          => 'nullable|exists:scholarship_batches,id',
        'selected_indexes'  => 'required|array|min:1',
        'results_json'      => 'required|string',
    ]);

    // ✅ scholarship is always required
    $scholarship = Scholarship::findOrFail($request->scholarship_id);

    // ✅ Determine if this scholarship requires batch (TDP/TES only)
    $name = strtoupper(trim($scholarship->scholarship_name ?? ''));
    $isBatchBased = str_contains($name, 'TDP') || str_contains($name, 'TES');

    // ✅ If batch-based, batch_id must be provided
    if ($isBatchBased && empty($request->batch_id)) {
        return back()->withInput()->with('error', 'Batch is required for TDP/TES scholarships.');
    }

    // ✅ If NOT batch-based, ignore batch_id
    $batch = null;

    if ($isBatchBased) {
        // Validate that the selected batch belongs to the selected scholarship
        $batch = ScholarshipBatch::query()
            ->where('id', $request->batch_id)
            ->where('scholarship_id', $scholarship->id)
            ->first();

        if (!$batch) {
            return back()->withInput()->with('error', 'Selected batch does not belong to the selected scholarship.');
        }
    }

    // ✅ decode upload results
    $results = json_decode($request->results_json, true);
    if (!is_array($results)) {
        return back()->with('error', 'Upload results invalid. Please upload the file again.');
    }

    // ✅ Auto-set date_added since UI no longer sends it
    $dateAdded = now()->toDateString();

    $added = [];
    $skipped = 0;

    DB::transaction(function () use ($request, $results, $scholarship, $batch, $isBatchBased, $dateAdded, &$added, &$skipped) {

        foreach ($request->selected_indexes as $i) {

            if (!isset($results[$i])) { $skipped++; continue; }

            $item = $results[$i];

            // Must have matched user
            $user = $item['user'] ?? null;
            if (!$user || empty($user['id'])) { $skipped++; continue; }

            $userId = (int) $user['id'];

            // Must be enrolled in current semester (based on your processed results)
            if (($item['enrollment_status'] ?? 'not_enrolled') !== 'enrolled') { $skipped++; continue; }

            // Must NOT already be scholar
            if (Scholar::where('student_id', $userId)->exists()) { $skipped++; continue; }

            Scholar::create([
                'student_id'     => $userId,
                'scholarship_id' => $scholarship->id,     // ✅ always set
                'batch_id'       => $isBatchBased ? $batch->id : null, // ✅ only TDP/TES
                'updated_by'     => Auth::id(),
                'date_added'     => $dateAdded,
                'status'         => 'active', // forced
            ]);

            $added[] = trim(($user['firstname'] ?? '') . ' ' . ($user['lastname'] ?? ''));
        }
    });

    $msg = 'Added: ' . count($added) . ' | Skipped: ' . $skipped;
    return redirect()->route('coordinator.manage-scholars')->with('success', $msg);
}



private function normHeader(string $h): string
{
    // "First Name" "FIRSTNAME" "first_name" -> "firstname"
    $h = strtolower(trim($h));
    $h = preg_replace('/[^a-z0-9]+/', '', $h); // remove spaces, underscores, symbols
    return $h;
}

private function findColIndex(array $headers, array $aliases): ?int
{
    $normHeaders = array_map(fn($x) => $this->normHeader((string)$x), $headers);

    foreach ($aliases as $alias) {
        $aliasNorm = $this->normHeader($alias);
        $idx = array_search($aliasNorm, $normHeaders, true);
        if ($idx !== false) return $idx;
    }
    return null;
}

private function normEnrollStatus(?string $s): string
{
    $s = strtolower(trim((string)$s));

    // allow many variants from excel
    if (Str::contains($s, 'enrol')) return 'enrolled';
    if (Str::contains($s, 'drop'))  return 'dropped';
    if (Str::contains($s, 'grad'))  return 'graduated';

    return 'not_enrolled';
}

}

