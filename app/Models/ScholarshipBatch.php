<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ScholarshipBatch extends Model
{
    protected $fillable = [
        'scholarship_id',  // FK to scholarships table
        'semester_id',     // FK to semesters table
        'batch_number',    // e.g., 'Batch 1',    // e.g., '2023-10-01'
    ];

    // Relationships
    // belongsTo: ScholarshipBatch belongs to a scholarship (via scholarship_id)
    public function scholarship()
    {
        return $this->belongsTo(Scholarship::class, 'scholarship_id', 'id');
    }

    // belongsTo: ScholarshipBatch belongs to a semester (via semester_id)
    public function semester()
    {
        return $this->belongsTo(Semester::class, 'semester_id', 'id');
    }

    // hasMany: ScholarshipBatch has many scholars (via batch_id in scholars table)
    public function scholars()
    {
        return $this->hasMany(Scholar::class, 'batch_id', 'id');
    }

    // hasMany: ScholarshipBatch has many stipend releases (via batch_id in stipend_releases table)
    public function stipendReleases()
    {
        return $this->hasMany(StipendsRelease::class, 'batch_id', 'id');
    }
}