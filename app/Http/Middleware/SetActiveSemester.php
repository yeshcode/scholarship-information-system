<?php

namespace App\Http\Middleware;

use App\Models\Semester;
use Closure;
use Illuminate\Http\Request;

class SetActiveSemester
{
    public function handle(Request $request, Closure $next)
    {
        $mode = session('semester_filter_mode', 'auto'); // default auto

        // ✅ AUTO: always follow whatever is_current is in DB
        if ($mode === 'auto') {
            $current = Semester::where('is_current', true)->first()
                ?? Semester::orderByDesc('start_date')->first();

            if ($current) {
                session(['active_semester_id' => $current->id]);
            }
        }

        // ✅ MANUAL: keep session active_semester_id as-is
        return $next($request);
    }
}
