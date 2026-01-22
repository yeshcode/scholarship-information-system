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
use App\Services\ScholarOcrService;
use Illuminate\Support\Str;
use App\Models\Section;
use App\Jobs\SendAnnouncementNotifications;
use Illuminate\Support\Facades\Log;



class CoordinatorController extends Controller
{
    public function dashboard()
    {
        return view('coordinator.dashboard');
    }

    // Manage Scholars

    private function isBatchBasedScholarship(Scholarship $scholarship): bool
    {
        // You can change the logic here anytime
        $name = strtoupper(trim($scholarship->scholarship_name ?? ''));
        return in_array($name, ['TDP', 'TES']);
    }

    public function manageScholars()
    {
        // Show ALL scholarships as buttons with scholar counts
        $scholarships = Scholarship::withCount('scholars')->orderBy('scholarship_name')->get();

        // Default: show nothing yet until they click a scholarship
        $scholars = Scholar::with([
            'user.section.course',
            'scholarshipBatch.semester',
            'scholarship'
        ])->latest()->paginate(10);

        return view('coordinator.manage-scholars', compact('scholarships', 'scholars'));
    }

    public function scholarsByScholarship(Scholarship $scholarship)
    {
        // If TDP/TES -> go to batches page instead
        if ($this->isBatchBasedScholarship($scholarship)) {
            return redirect()->route('coordinator.scholars.batches', $scholarship->id);
        }

        $scholarships = Scholarship::withCount('scholars')->orderBy('scholarship_name')->get();

        $scholars = Scholar::with([
            'user.section.course',
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
            'user.section.course',
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

    public function createScholar()
    {
        $users = User::whereHas('userType', function ($q) { $q->where('name', 'Student'); })->get();  // Students only
        $batches = ScholarshipBatch::all();
        return view('coordinator.create-scholar', compact('users', 'batches'));
    }

    public function storeScholar(Request $request)
    {
        $request->validate([
            'student_id' => 'required|exists:users,id',
            'batch_id' => 'required|exists:scholarship_batches,id',
            'date_added' => 'required|date',
            'status' => 'required|in:active,inactive,graduated',
        ]);

        // Fetch the batch to get the scholarship_id
        $batch = ScholarshipBatch::find($request->batch_id);

        Scholar::create([
            'student_id' => $request->student_id,
            'batch_id' => $request->batch_id,
            'scholarship_id' => $batch->scholarship_id, 
            'updated_by' => Auth::id(),
            'date_added' => $request->date_added,
            'status' => $request->status,
        ]);

        return redirect()->route('coordinator.manage-scholars')->with('success', 'Scholar added successfully.');
    }

    // Show OCR upload form
public function uploadOcr()
{
    return view('coordinator.ocr-upload-scholar');
}


// Process OCR and add scholars
public function processOcr(Request $request)
{
    $request->validate([
        'file' => 'required|mimes:pdf,xlsx,xls,jpg,jpeg,png,gif,bmp,tiff|max:10240',
    ]);

    $file = $request->file('file');
    $extension = strtolower($file->getClientOriginalExtension());  // Get file type (e.g., 'png', 'xlsx')

    $service = new ScholarOcrService();
    $results = $service->processFileWithOcr($file);
    $extractedData = $service->getExtractedData();

    return redirect()->route('coordinator.scholars.ocr-upload')
        ->with('results', $results)
        ->with('extracted_data', $extractedData)
        ->with('file_type', $extension)  // NEW: Pass file type to session
        ->with('success', 'File processed. Review matches below.');
}

// Add this new method
public function addSelectedOcrScholars(Request $request)
{
    $request->validate([
        'selected_ids' => 'array',
        'results' => 'required|string',  // JSON string
    ]);

    $selectedIds = $request->input('selected_ids', []);
    $results = json_decode($request->input('results'), true);

    // Filter only the selected results
    $selectedResults = [];
    foreach ($selectedIds as $index) {
        if (isset($results[$index])) {
            $selectedResults[] = $results[$index];
        }
    }

    // Redirect to confirmation page with selected results
    return redirect()->route('coordinator.scholars.confirm-add-ocr')->with('selectedResults', $selectedResults);
}

public function confirmAddOcrScholars(Request $request)
{
    $request->validate([
        'batch_id' => 'required|exists:scholarship_batches,id',
        'selected_results' => 'required|string',  // JSON string
    ]);

    $batchId = $request->input('batch_id');
    $selectedResults = json_decode($request->input('selected_results'), true);

    $service = new ScholarOcrService();
    $added = [];
    foreach ($selectedResults as $result) {
        if ($result['user'] && $result['is_enrolled'] && !$result['is_scholar']) {
            Scholar::create([
                'student_id' => $result['user']->id,
                'batch_id' => $batchId,  // Use selected batch
                'updated_by' => Auth::id(),
                'date_added' => now()->toDateString(),
                'status' => 'active',
                'enrollment_status' => $result['data']['enrollment_status'] ?? 'enrolled',
            ]);
            $added[] = $result['user']->firstname . ' ' . $result['user']->lastname;
        }
    }

    $message = count($added) > 0 ? 'Added scholars: ' . implode(', ', $added) : 'No scholars added.';
    return redirect()->route('coordinator.manage-scholars')->with('success', $message);
}

public function showConfirmAddOcr()
{
    $batches = ScholarshipBatch::with('semester')->get();  // Existing
    $scholarships = Scholarship::all();  // NEW: Load all scholarships for the dropdown
    return view('coordinator.confirm-add-ocr', compact('batches', 'scholarships'));  // Note: View path as per your earlier correction
}


    //new/from superadmin
    public function enrollmentRecords()
    {
        $enrolledUsers = Enrollment::with(['user', 'semester', 'section.course'])
            ->latest()
            ->paginate(10);

        // For "Add Enrollment" form
        $users = User::whereHas('userType', fn($q) => $q->where('name', 'Student'))->orderBy('lastname')->get();
        $semesters = Semester::orderBy('created_at', 'desc')->get();
        $sections = Section::with('course')->orderBy('section_name')->get();

        return view('coordinator.enrollment-records', compact('enrolledUsers', 'users', 'semesters', 'sections'));
    }

    public function addEnrollmentRecord(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'semester_id' => 'required|exists:semesters,id',
            'section_id' => 'required|exists:sections,id',
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
            'section_id' => $request->section_id,
            'status' => $request->status,
        ]);

        return redirect()->route('coordinator.enrollment-records')->with('success', 'Enrollment record added successfully.');
    }

    // Manage Scholarship Batches
    public function manageScholarshipBatches()
    {
        $batches = ScholarshipBatch::with('scholarship', 'semester')->paginate(10);
        return view('coordinator.scholarship-batches', compact('batches'));
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
    public function manageStipends()
    {
        $stipends = Stipend::with('scholar', 'stipendRelease')->paginate(10);
        return view('coordinator.manage-stipends', compact('stipends'));
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

        Stipend::create([
            'scholar_id' => $request->scholar_id,
            'student_id' => Scholar::find($request->scholar_id)->student_id,  // Auto-fill
            'stipend_release_id' => $request->stipend_release_id,
            'created_by' => Auth::id(),
            'updated_by' => Auth::id(),
            'amount_received' => $request->amount_received,
            'status' => $request->status,
        ]);

        return redirect()->route('coordinator.manage-stipends')->with('success', 'Stipend created successfully.');
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
        'scholar_id' => 'required|exists:scholars,id',
        'stipend_release_id' => 'required|exists:stipend_releases,id',
        'amount_received' => 'required|numeric',
        'status' => 'required|in:for_release,released,returned,waiting',
    ]);

    $stipend = Stipend::findOrFail($id);
    $stipend->update($request->only(['scholar_id', 'stipend_release_id', 'amount_received', 'status']));
    $stipend->update(['updated_by' => Auth::id()]);
    return redirect()->route('coordinator.manage-stipends')->with('success', 'Stipend updated successfully.');
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

    // Manage Stipend Releases
    public function manageStipendReleases()
    {
        $releases = StipendsRelease::with('scholarshipBatch')->paginate(10);
        return view('coordinator.manage-stipend-releases', compact('releases'));
    }

    public function createStipendRelease()
    {
        $batches = ScholarshipBatch::all();
        return view('coordinator.create-stipend-release', compact('batches'));
    }

    public function storeStipendRelease(Request $request)
    {
        $request->validate([
            'batch_id' => 'required|exists:scholarship_batches,id',
            'title' => 'required|string',
            'amount' => 'required|numeric',
            'status' => 'required|in:pending,released',
            'date_release' => 'required|date',
            'notes' => 'nullable|string',
        ]);

        StipendsRelease::create([
            'batch_id' => $request->batch_id,
            'created_by' => Auth::id(),
            'updated_by' => Auth::id(),
            'title' => $request->title,
            'amount' => $request->amount,
            'status' => $request->status,
            'date_release' => $request->date_release,
            'notes' => $request->notes,
        ]);

        return redirect()->route('coordinator.manage-stipend-releases')->with('success', 'Release created successfully.');
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
        'batch_id' => 'required|exists:scholarship_batches,id',
        'title' => 'required|string',
        'amount' => 'required|numeric',
        'status' => 'required|in:pending,released',
        'date_release' => 'required|date',
        'notes' => 'nullable|string',
    ]);

    $release = StipendsRelease::findOrFail($id);
    $release->update($request->only(['batch_id', 'title', 'amount', 'status', 'date_release', 'notes']));
    $release->update(['updated_by' => Auth::id()]);
    return redirect()->route('coordinator.manage-stipend-releases')->with('success', 'Release updated successfully.');
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

    // Manage Announcements
    public function manageAnnouncements()
    {
        $scholars = Scholar::with('user')->get();

        $announcements = Announcement::with('creator')
            ->orderByDesc('id')          // ALWAYS newest first
            ->paginate(10);

        return view('coordinator.manage-announcements', compact('scholars', 'announcements'));
    }


 
     // Store announcement and send notifications (UPDATED: Add audience, scholar selection, emails, and notifications)
     public function storeAnnouncement(Request $request)
{
    $request->validate([
        'title' => 'required|string|max:255',
        'description' => 'required|string',
        'audience' => 'required|in:all_students,specific_scholars',
        'selected_scholars' => 'nullable|array',
    ]);

    $announcement = Announcement::create([
        'created_by' => Auth::id(),
        'title' => $request->title,
        'description' => $request->description,
        'audience' => $request->audience,
        'posted_at' => now(), // auto today
    ]);


    // recipients
    $recipients = collect();

    if ($request->audience === 'all_students') {
        $recipients = User::whereHas('userType', function ($q) {
            $q->where('name', 'Student');
        })->get();
    } else {
        $recipients = Scholar::whereIn('id', $request->selected_scholars ?? [])
            ->with('user')
            ->get()
            ->pluck('user');
    }

    // ✅ OPTIONAL: include YOU for testing (even if not selected)
    // (remove this later if you don't want to receive your own announcements)
    $recipients = $recipients->push(Auth::user())->unique('id');

    $coordinatorEmail = Auth::user()->bisu_email ?? config('mail.from.address');

    foreach ($recipients as $user) {

        // 1) ALWAYS create system notification (kahit walang email)
        Notification::create([
            'recipient_user_id' => $user->id,
            'created_by' => Auth::id(),
            'type' => 'announcement',
            'title' => $announcement->title,
            'message' => $announcement->description,
            'related_type' => Announcement::class,
            'related_id' => $announcement->id,
            'is_read' => false,
            'sent_at' => now(),
        ]);

        // 2) TRY to send email (but never break the loop)
        $email = trim((string) ($user->bisu_email ?? ''));

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            Log::warning("Skipped email (invalid/missing)", ['user_id' => $user->id, 'email' => $email]);
            continue;
        }

        try {
            Mail::to($email)->send(
                new AnnouncementNotification($announcement->toArray(), $coordinatorEmail)
            );
        } catch (\Throwable $e) {
            Log::error("Email send failed", [
                'user_id' => $user->id,
                'email' => $email,
                'error' => $e->getMessage(),
            ]);
            // continue sending to others
        }
    }

    return redirect()->route('coordinator.manage-announcements', ['page' => 1])
        ->with('success', 'Announcement posted. Notifications created and emails attempted.');
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
    ]);

    \App\Models\Scholarship::create([
        'scholarship_name' => $request->scholarship_name,
        'description' => $request->description,
        'requirements' => $request->requirements,
        'benefactor' => $request->benefactor,
        'status' => $request->status,
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
    ]);

    $scholarship = \App\Models\Scholarship::findOrFail($id);
    $scholarship->update([
        'scholarship_name' => $request->scholarship_name,
        'description' => $request->description,
        'requirements' => $request->requirements,
        'benefactor' => $request->benefactor,
        'status' => $request->status,
        'updated_by' => Auth::id(),
    ]);

    return redirect()->route('coordinator.manage-scholarships')->with('success', 'Scholarship updated successfully.');
}

public function confirmDeleteScholarship($id)
{
    $scholarship = Scholarship::findOrFail($id);
    return view('coordinator.confirm-delete-scholarship', compact('scholarship'));
}

//reports
public function reports()
{
    return view('coordinator.reports');
}



}