<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Semester extends Model
{
    protected $fillable = [
        'term',
        'academic_year',       // e.g., 'Fall 2023'
        'start_date', // e.g., '2023-09-01'
        'end_date', 
        'is_current',  // e.g., '2023-12-31'
    ];

    // Relationships
    // hasMany: Semester has many enrollments (via semester_id in enrollments table)
    public function enrollments()
    {
        return $this->hasMany(Enrollment::class, 'semester_id', 'id');  // FK in enrollments: semester_id, local PK: id
    }

    // hasMany: Semester has many scholarship batches (via semester_id in scholarship_batches table)
    public function scholarshipBatches()
    {
        return $this->hasMany(ScholarshipBatch::class, 'semester_id', 'id');  // FK in scholarship_batches: semester_id, local PK: id
    }
}