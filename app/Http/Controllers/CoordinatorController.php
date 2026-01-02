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
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CoordinatorController extends Controller
{
    public function dashboard()
    {
        return view('coordinator.dashboard');
    }

    // Manage Scholars
    public function manageScholars()
    {
        $scholars = Scholar::with('user', 'scholarshipBatch')->paginate(10);
        return view('coordinator.manage-scholars', compact('scholars'));
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

        Scholar::create([
            'student_id' => $request->student_id,
            'batch_id' => $request->batch_id,
            'updated_by' => Auth::id(),
            'date_added' => $request->date_added,
            'status' => $request->status,
        ]);

        return redirect()->route('coordinator.manage-scholars')->with('success', 'Scholar added successfully.');
    }

    // View All Enrolled Users
    public function viewEnrolledUsers()
    {
        $enrolledUsers = Enrollment::with('user', 'semester')->paginate(10);
        $users = User::all();  // For manual add dropdown
        $semesters = Semester::all();
        $sections = \App\Models\Section::all();  // Assuming Section model exists
        return view('coordinator.enrolled-users', compact('enrolledUsers', 'users', 'semesters', 'sections'));
    }

    public function addEnrolledUser(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'semester_id' => 'required|exists:semesters,id',
            'section_id' => 'required|exists:sections,id',
            'status' => 'required|string',
        ]);

        Enrollment::create($request->all());
        return redirect()->route('coordinator.enrolled-users')->with('success', 'User enrolled successfully.');
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
        $announcements = Announcement::with('creator')->paginate(10);
        return view('coordinator.manage-announcements', compact('announcements'));
    }

    public function createAnnouncement()
    {
        return view('coordinator.create-announcement');
    }

    public function storeAnnouncement(Request $request)
    {
        $request->validate([
            'title' => 'required|string',
            'description' => 'required|string',
            'posted_at' => 'required|date',
        ]);

        Announcement::create([
            'created_by' => Auth::id(),
            'title' => $request->title,
            'description' => $request->description,
            'posted_at' => $request->posted_at,
        ]);

        return redirect()->route('coordinator.manage-announcements')->with('success', 'Announcement created successfully.');
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
}