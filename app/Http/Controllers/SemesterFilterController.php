<?php

namespace App\Http\Controllers;

use App\Models\Semester;
use Illuminate\Http\Request;

class SemesterFilterController extends Controller
{
    public function show()
    {
        // Only Coordinator + Super Admin can use the filter
        if (!auth()->user()->hasRole('Scholarship Coordinator') && !auth()->user()->hasRole('Super Admin')) {
            abort(403);
        }

        $semesters = Semester::orderByDesc('academic_year')
            ->orderByDesc('start_date')
            ->get();

        $activeSemesterId = session('active_semester_id');

        return view('semester.filter', compact('semesters', 'activeSemesterId'));
    }

    public function set(Request $request)
    {
        if (!auth()->user()->hasRole('Scholarship Coordinator') && !auth()->user()->hasRole('Super Admin')) {
            abort(403);
        }

        $request->validate([
            'semester_id' => 'required|integer|exists:semesters,id',
        ]);

        session(['active_semester_id' => $request->semester_id]);

        return redirect()->back()->with('success', 'Semester filter updated.');
    }

    public function clear()
    {
        if (!auth()->user()->hasRole('Scholarship Coordinator') && !auth()->user()->hasRole('Super Admin')) {
            abort(403);
        }

        session()->forget('active_semester_id');

        return redirect()->back()->with('success', 'Semester filter cleared.');
    }
}
    