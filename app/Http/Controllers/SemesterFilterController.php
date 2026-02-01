<?php

namespace App\Http\Controllers;

use App\Models\Semester;
use Illuminate\Http\Request;

class SemesterFilterController extends Controller
{
    public function show()
    {
        // ✅ Now ALL authenticated users can use the filter
        $semesters = Semester::orderByDesc('academic_year')
            ->orderByDesc('start_date')
            ->get();

        $activeSemesterId = session('active_semester_id');

        return view('semester.filter', compact('semesters', 'activeSemesterId'));
    }

    public function set(Request $request)
    {
        $request->validate([
            'semester_id' => 'required|integer|exists:semesters,id',
        ]);

        session(['active_semester_id' => (int) $request->semester_id]);

        return redirect()->back()->with('success', 'Semester filter updated.');
    }

    public function clear()
    {
        session()->forget('active_semester_id');

        return redirect()->back()->with('success', 'Semester filter cleared.');
    }

    public function search(Request $request)
{
    // Only Coordinator + Super Admin
    if (!auth()->user()->hasRole('Scholarship Coordinator') && !auth()->user()->hasRole('Super Admin')) {
        abort(403);
    }

    $q = trim((string) $request->get('q', ''));

    $items = Semester::query()
        ->when($q !== '', function ($x) use ($q) {
            $x->where(function ($w) use ($q) {
                $w->where('term', 'ILIKE', "%{$q}%")
                  ->orWhere('academic_year', 'ILIKE', "%{$q}%");
            });
        })
        ->orderByDesc('academic_year')
        ->orderByDesc('start_date')
        ->limit(20)
        ->get(['id', 'term', 'academic_year', 'is_current']);

    return response()->json([
        'data' => $items->map(function ($s) {
            return [
                'id' => $s->id,
                'label' => trim(($s->term ?? '') . ' ' . ($s->academic_year ?? '')),
                'is_current' => (bool) $s->is_current,
            ];
        }),
    ]);
}

}
