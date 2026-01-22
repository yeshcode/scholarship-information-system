<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Announcement;
use App\Models\Scholarship;
use App\Models\Stipend;
use App\Models\Notification;
use Illuminate\Support\Facades\Auth;  // Add this import

class StudentController extends Controller
{
    public function dashboard()
    {
        $announcements = Announcement::where('audience', 'all_students')
            ->orWhere(function($query) {
                $query->where('audience', 'specific_scholars')
                      ->whereHas('notifications', function($q) {
                          $q->where('recipient_user_id', Auth::id());  // Use Auth::id()
                      });
            })->latest()->get();

        return view('student.dashboard', compact('announcements'));
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
        $stipends = Stipend::where('student_id', Auth::id())->with('stipendRelease')->paginate(10);  // Use Auth::id()
        return view('student.stipend-history', compact('stipends'));
    }

    public function notifications()
    {
        $notifications = Notification::where('recipient_user_id', Auth::id())->latest()->paginate(10);  // Use Auth::id()
        return view('student.notifications', compact('notifications'));
    }
}