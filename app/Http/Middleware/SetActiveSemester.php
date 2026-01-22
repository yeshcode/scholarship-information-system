<?php

namespace App\Http\Middleware;

use App\Models\Semester;
use Closure;
use Illuminate\Http\Request;

class SetActiveSemester
{
    public function handle(Request $request, Closure $next)
    {
        if (!session()->has('active_semester_id')) {
            $current = Semester::where('is_current', true)->first();

            if (!$current) {
                $current = Semester::orderByDesc('start_date')->first();
            }

            if ($current) {
                session(['active_semester_id' => $current->id]);
            }
        }

        return $next($request);
    }
}
