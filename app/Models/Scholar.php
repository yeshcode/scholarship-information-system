<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use App\Models\Semester;

class Scholar extends Model
{
    protected $fillable = [
        'student_id',
        'batch_id',
        'scholarship_id',
        'updated_by',
        'date_added',
        'date_removed',   // ✅ NEW
        'status',
    ];

    // ✅ Optional (recommended) if these are DATE columns in DB
    protected $casts = [
        'date_added'   => 'date',
        'date_removed' => 'date',
    ];

    /**
     * ✅ Scope: scholar is considered active/valid within a given semester
     * Rules:
     * - status = active
     * - date_added <= semester end_date
     * - date_removed is null OR date_removed > semester start_date
     */
    public function scopeActiveInSemester(Builder $q, ?Semester $sem): Builder
    {
        if (!$sem) return $q;

        // ✅ null-safe dates
        $start = $sem->start_date ?? $sem->end_date;       // prefer start_date
        $end   = $sem->end_date ?? $sem->start_date;       // prefer end_date

        // If still missing, don’t filter (avoid returning empty results)
        if (!$start || !$end) return $q;

        return $q
            // ✅ allow old rows where status is NULL (treat as active)
            ->where(function ($w) {
                $w->whereNull('scholars.status')
                ->orWhere('scholars.status', 'active');
            })
            ->whereDate('scholars.date_added', '<=', $end)
            ->where(function ($w) use ($start) {
                $w->whereNull('scholars.date_removed')
                ->orWhereDate('scholars.date_removed', '>', $start);
            });
    }

    public function scopeActiveRoster($q)
    {
        return $q->where(function($w){
                $w->whereNull('scholars.status')
                ->orWhere('scholars.status', 'active');
            })
            ->whereNull('scholars.date_removed');
    }

    // =========================
    // Relationships
    // =========================

    public function user()
    {
        return $this->belongsTo(User::class, 'student_id', 'id');
    }

    public function scholarshipBatch()
    {
        return $this->belongsTo(ScholarshipBatch::class, 'batch_id', 'id');
    }

    public function scholarship()
    {
        return $this->belongsTo(Scholarship::class, 'scholarship_id', 'id');
    }

    public function updater()
    {
        return $this->belongsTo(User::class, 'updated_by', 'id');
    }

    public function stipends()
    {
        return $this->hasMany(Stipend::class, 'scholar_id', 'id');
    }

    public function enrollments()
    {
        return $this->hasMany(\App\Models\Enrollment::class, 'user_id', 'student_id');
    }
}