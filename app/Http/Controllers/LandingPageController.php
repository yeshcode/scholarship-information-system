<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Scholar;
use App\Models\Scholarship;
use App\Models\Announcement;

class LandingPageController extends Controller
{
    public function index()
    {
        // ✅ Real-time Scholars Count
        // If you want only ACTIVE scholars, uncomment the where('status','active')
        $scholarsCount = Scholar::count();
        // $scholarsCount = Scholar::where('status', 'active')->count();

        // ✅ Scholarships info (real-time)
        // You said: scholarship_name, description, requirements, benefactor, status
        // We'll show latest 6 or you can order by scholarship_name
        $scholarships = Scholarship::query()
            ->select('id', 'scholarship_name', 'benefactor', 'status', 'description')
            ->orderBy('created_at', 'desc')
            ->take(6)
            ->get();

        // ✅ Announcements (real-time)
        // Prefer posted_at if set, else fallback to created_at
        $announcements = Announcement::query()
            ->select('id', 'title', 'description', 'posted_at', 'created_at')
            ->orderByRaw("COALESCE(posted_at, created_at) DESC")
            ->take(5)
            ->get();

        return view('landing', compact(
            'scholarsCount',
            'scholarships',
            'announcements'
        ));
    }
}
