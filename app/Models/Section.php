<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Section extends Model
{
    protected $fillable = [
        'section_name',      // e.g., 'Section A'
        'course_id',         // FK to courses table
        'year_level_id',     // FK to year_levels table
    ];

    // Relationships
    // belongsTo: Section belongs to a course (via course_id)
    public function course()
    {
        return $this->belongsTo(Course::class, 'course_id', 'id');
    }

    // belongsTo: Section belongs to a year level (via year_level_id)
    public function yearLevel()
    {
        return $this->belongsTo(YearLevel::class, 'year_level_id', 'id');
    }

    // hasMany: Section has many enrollments (via section_id in enrollments table)
    public function enrollments()
    {
        return $this->hasMany(Enrollment::class, 'section_id', 'id');  // FK in enrollments: section_id, local PK: id
    }

    // Added: hasMany users (reverse of User belongsTo section)
    public function users()
    {
        return $this->hasMany(User::class, 'section_id', 'id');  // FK in users: section_id, local PK: id
    }
}