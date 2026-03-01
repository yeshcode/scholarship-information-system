<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Announcement;
use App\Models\Scholarship;
use App\Models\Stipend;
use App\Models\Notification;
use Illuminate\Support\Facades\Auth;  // Add this import
use App\Http\Controllers\Concerns\UsesActiveSemester;
use App\Models\AnnouncementView;
use App\Models\Scholar;
use App\Models\Enrollment;   // <-- add this
use App\Models\Question;     // <-- add this (if not yet)
use Illuminate\Support\Facades\Schema;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;



class StudentController extends Controller
{

use UsesActiveSemester;

 public function dashboard()
{
    $userId = Auth::id();

    // ✅ Is the logged-in student a scholar?
    $isScholar = Scholar::where('student_id', $userId)->exists();

   // ✅ Latest Enrollment with joined course + year level (correct column names)
    $latestEnrollment = Enrollment::query()
        ->where('enrollments.user_id', $userId)
        ->whereNotNull('enrollments.course_id')       // ✅ avoid null course
        ->whereNotNull('enrollments.year_level_id')   // ✅ avoid null year level
        ->leftJoin('courses', 'enrollments.course_id', '=', 'courses.id')
        ->leftJoin('year_levels', 'enrollments.year_level_id', '=', 'year_levels.id')
        ->orderByDesc('enrollments.id')
        ->select(
            'enrollments.*',
            'courses.course_name as course_display',
            'year_levels.year_level_name as yearlevel_display'
        )
        ->first();

    // ✅ fallback (if student has enrollment but course_id is null)
    if (!$latestEnrollment) {
        $latestEnrollment = Enrollment::query()
            ->where('enrollments.user_id', $userId)
            ->leftJoin('courses', 'enrollments.course_id', '=', 'courses.id')
            ->leftJoin('year_levels', 'enrollments.year_level_id', '=', 'year_levels.id')
            ->orderByDesc('enrollments.id')
            ->select(
                'enrollments.*',
                'courses.course_name as course_display',
                'year_levels.year_level_name as yearlevel_display'
            )
            ->first();
    }

    $studentCourse = $latestEnrollment->course_display ?? 'N/A';
    $studentYearLevel = $latestEnrollment->yearlevel_display ?? 'N/A';
                
    // ✅ Announcements visible to student
    $announcements = Announcement::query()
        ->whereNotNull('posted_at')
        ->where('posted_at', '<=', now())
        ->where(function ($q) use ($userId, $isScholar) {
            $q->where('audience', 'all_students');

            if ($isScholar) {
                $q->orWhere('audience', 'all_scholars');
            }

            $q->orWhereHas('notifications', function ($n) use ($userId) {
                $n->where('recipient_user_id', $userId)
                  ->where('type', 'announcement');
            });
        })
        ->orderByDesc('posted_at')
        ->take(3)
        ->get();

    // ✅ Notifications preview + unread count
    $notifications = Notification::where('recipient_user_id', $userId)
        ->orderByDesc('id')
        ->take(3)
        ->get();

    $unreadCount = Notification::where('recipient_user_id', $userId)
        ->where('is_read', false)
        ->count();

    // ✅ Questions (your table uses user_id, not student_id)
    $myRecentQuestions = Question::where('user_id', $userId)
        ->orderByDesc('id')
        ->take(3)
        ->get();

    // ✅ Summary counts
    $announcementsCount = Announcement::query()
        ->whereNotNull('posted_at')
        ->where('posted_at', '<=', now())
        ->where(function ($q) use ($userId, $isScholar) {
            $q->where('audience', 'all_students');

            if ($isScholar) {
                $q->orWhere('audience', 'all_scholars');
            }

            $q->orWhereHas('notifications', function ($n) use ($userId) {
                $n->where('recipient_user_id', $userId)
                  ->where('type', 'announcement');
            });
        })
        ->count();

    // count using same logic as above (user_id then student_id)
    $questionsCount = Question::where('user_id', $userId)->count();

    $scholarshipsCount = Scholarship::count();

    return view('student.dashboard', compact(
        'announcements',
        'notifications',
        'unreadCount',
        'myRecentQuestions',
        'isScholar',
        'announcementsCount',
        'questionsCount',
        'scholarshipsCount',
        'studentCourse',
        'studentYearLevel'
    ));
}

   public function announcements()
{
    $userId = Auth::id();

    $isScholar = Scholar::where('student_id', $userId)->exists();

    $announcements = Announcement::query()
        ->whereNotNull('posted_at')
        ->where('posted_at', '<=', now())
        ->where(function ($q) use ($userId, $isScholar) {
            $q->where('audience', 'all_students');

            if ($isScholar) {
                $q->orWhere('audience', 'all_scholars');
            }

            $q->orWhereHas('notifications', function ($n) use ($userId) {
                $n->where('recipient_user_id', $userId)
                  ->where('type', 'announcement');
            });
        })
        ->orderByDesc('posted_at')
        ->paginate(10);

        // ✅ Get IDs that the student already opened/read
        $announcementIds = $announcements->getCollection()->pluck('id');

        $viewedIds = AnnouncementView::where('user_id', $userId)
            ->whereIn('announcement_id', $announcementIds)
            ->pluck('announcement_id')
            ->map(fn($id) => (int)$id)
            ->toArray();


    return view('student.announcements', compact('announcements', 'viewedIds'));
}



    public function scholarships()
    {
        $scholarships = Scholarship::paginate(10);
        return view('student.scholarships', compact('scholarships'));
    }

    public function index()
    {
        $scholarships = Scholarship::orderByDesc('id')->paginate(10);
        return view('student.scholarships.index', compact('scholarships'));
    }

    public function show($id)
    {
        $scholarship = Scholarship::findOrFail($id);
        return view('student.scholarships.show', compact('scholarship'));
    }


    public function stipendHistory()
{
    $activeSemesterId = $this->activeSemesterId();

    $stipends = Stipend::query()
        ->where('student_id', Auth::id())
        ->with('stipendRelease.scholarshipBatch.semester')
        ->when($activeSemesterId, function($q) use ($activeSemesterId){
            $q->whereHas('stipendRelease.scholarshipBatch', fn($b) => $b->where('semester_id', $activeSemesterId));
        })
        ->orderByDesc('id')
        ->paginate(10)
        ->withQueryString();

    return view('student.stipend-history', compact('stipends', 'activeSemesterId'));
}


    public function notifications()
    {
        $notifications = Notification::where('recipient_user_id', Auth::id())->latest()->paginate(10);  // Use Auth::id()
        return view('student.notifications', compact('notifications'));
    }

   public function announcementShow(Announcement $announcement)
    {
        AnnouncementView::firstOrCreate(
            [
                'announcement_id' => $announcement->id,
                'user_id' => Auth::id(),
            ],
            [
                'seen_at' => now(),
            ]
        );

        return view('student.announcement-show', compact('announcement'));
    }


public function open($id)
{
    $notification = Notification::where('id', $id)
        ->where('recipient_user_id', Auth::id())
        ->firstOrFail();

    if (!$notification->is_read) {
        $notification->update(['is_read' => true]);
    }

    // ✅ Use 'type' because that's what exists in your DB
    if ($notification->type === 'announcement') {
        // You can redirect to announcements list (safe)
        return redirect()->route('student.announcements');
    }

    if ($notification->type === 'stipend') {
        return redirect()->route('student.stipend-history');
    }

    return redirect()->route('student.notifications');
}


public function claimStipend(Request $request, Stipend $stipend)
{
    $userId = Auth::id();

    // ✅ Security: stipend must belong to the logged-in student
    if ((int)$stipend->student_id !== (int)$userId) {
        abort(403, 'Unauthorized.');
    }

    // ✅ Only allow claim if stipend is RELEASED (coordinator already released it)
    if ($stipend->status !== 'released') {
        return back()->with('error', 'You can only claim a stipend that is already RELEASED.');
    }

    // ✅ Prevent double-claim
    if (!empty($stipend->claimed_at)) {
        return back()->with('error', 'This stipend is already marked as CLAIMED.');
    }

    // Load for better notification message
    $stipend->load(['stipendRelease', 'scholar.user']);

    $claimedAt = now();

    // ✅ Coordinator receiver: use the last updater (the one who released),
    // fallback to created_by if needed
    $coordinatorUserId = $stipend->updated_by ?: $stipend->created_by;

    DB::transaction(function () use ($stipend, $userId, $claimedAt, $coordinatorUserId) {

        $stipend->update([
            'claimed_at' => $claimedAt,
            'claimed_by' => $userId,
        ]);

        // If no coordinator found, skip notification safely
        if ($coordinatorUserId) {
            $studentName = trim(($stipend->scholar->user->firstname ?? '') . ' ' . ($stipend->scholar->user->lastname ?? ''));
            $studentId   = $stipend->scholar->user->student_id ?? 'N/A';
            $releaseTitle= $stipend->stipendRelease->title ?? 'Stipend Release';
            $amount      = number_format((float)$stipend->amount_received, 2);

            \App\Models\Notification::create([
                'recipient_user_id' => $coordinatorUserId,
                'created_by' => $userId, // student created it
                'type' => 'stipend',
                'title' => 'Cheque Claimed',
                'message' =>
                    "Scholar {$studentName} (Student ID: {$studentId}) has CLAIMED the cheque for '{$releaseTitle}'. " .
                    "Amount: ₱{$amount}. Claimed on: " . Carbon::parse($claimedAt)->format('M d, Y h:i A') . ".",
                'related_type' => 'stipend',
                'related_id' => $stipend->id,
                'link' => route('coordinator.stipends.claim-notifications'),
                'is_read' => false,
                'sent_at' => now(),
            ]);
        }
    });

    return back()->with('success', 'Marked as claimed. Coordinator has been notified.');
}


}