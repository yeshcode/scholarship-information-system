<?php

namespace App\Http\Controllers\Concerns;

use App\Models\Semester;

trait UsesActiveSemester
{
    protected function activeSemesterId(): ?int
    {
        $id = session('active_semester_id');
        if ($id) return (int) $id;

        $current = Semester::where('is_current', true)->value('id');
        return $current ? (int) $current : null;
    }
}
