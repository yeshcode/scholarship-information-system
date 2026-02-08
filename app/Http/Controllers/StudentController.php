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



class StudentController extends Controller
{

use UsesActiveSemester;

    public function dashboard()
    {
        $announcements = Announcement::where('audience', 'all_students')
            ->orWhere(function($query) {
                $query->where('audience', 'specific_scholars')
                    ->whereHas('notifications', function($q) {
                        $q->where('recipient_user_id', Auth::id());
                    });
            })
            ->latest()
            ->take(5)
            ->get();

        // ✅ Add this
        $notifications = Notification::where('recipient_user_id', Auth::id())
            ->latest()
            ->take(5)
            ->get();

        $unreadCount = Notification::where('recipient_user_id', Auth::id())
            ->where('is_read', false)
            ->count();

        return view('student.dashboard', compact('announcements', 'notifications', 'unreadCount'));
    }


    public function announcements()
    {
        $announcements = Announcement::where('audience', 'all_students')
            ->orWhere(function($query) {
                $query->where('audience', 'specific_scholars')
                      ->whereHas('notifications', function($q) {
                          $q->where('recipient_user_id', Auth::id());  // Use Auth::id()
                      });
            })->latest()->paginate(10);

        return view('student.announcements', compact('announcements'));
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



}