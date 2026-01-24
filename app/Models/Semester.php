<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Semester extends Model
{
    protected $fillable = [
        'term',
        'academic_year',
        'start_date',
        'end_date',
        'is_current',
    ];

    public function enrollments()
    {
        return $this->hasMany(Enrollment::class, 'semester_id', 'id');
    }

    // ✅ Detect 1st semester (works with "1st Semester", "First Semester", "1st", etc.)
    public function isFirstTerm(): bool
    {
        $t = strtolower(trim((string) $this->term));

        return str_contains($t, '1st')
            || str_contains($t, 'first')
            || $t === '1';
    }

    // ✅ Get the starting year (works with "2023-2024" or "2023")
    public function startAcademicYear(): ?int
    {
        $ay = trim((string) $this->academic_year);

        // if "2023-2024"
        if (preg_match('/^(\d{4})\s*-\s*(\d{4})$/', $ay, $m)) {
            return (int) $m[1];
        }

        // if "2023"
        if (preg_match('/^\d{4}$/', $ay)) {
            return (int) $ay;
        }

        return null;
    }
}
