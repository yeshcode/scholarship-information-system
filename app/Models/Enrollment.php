<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Enrollment extends Model
{
    protected $fillable = [
        'student_id',      // FK to users table (student)
        'semester_id',  // FK to semesters table
        'section_id',   // FK to sections table
        'status',       // e.g., 'active', 'inactive'
    ];

    // Relationships
    // belongsTo: Enrollment belongs to a user (student, via user_id)
    public function user()
    {
        return $this->belongsTo(User::class, 'student_id', 'user_id');  // FK: user_id, related PK: user_id (since User uses custom PK)
    }

    // belongsTo: Enrollment belongs to a semester (via semester_id)
    public function semester()
    {
        return $this->belongsTo(Semester::class, 'semester_id', 'id');
    }

    // belongsTo: Enrollment belongs to a section (via section_id)
    public function section()
    {
        return $this->belongsTo(Section::class, 'section_id', 'id');
    }
}