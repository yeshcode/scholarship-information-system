<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;


class Enrollment extends Model
{
    public const STATUS_ENROLLED  = 'enrolled';
    public const STATUS_GRADUATED = 'graduated';
    public const STATUS_DROPPED   = 'dropped';


    protected $fillable = [
        'user_id',       // FK to users table (matches migration and User model)
        'semester_id',   // FK to semesters table
        'course_id',     // FK to courses table
         'year_level_id',   // ✅ ADD THIS
        'status',       // e.g., 'active', 'inactive'
    ];

    // Relationships
    // belongsTo: Enrollment belongs to a user (via user_id, aligns with User model's enrollments())
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');  // FK: user_id, related PK: id
    }

    // belongsTo: Enrollment belongs to a semester (via semester_id)
    public function semester()
    {
        return $this->belongsTo(Semester::class, 'semester_id', 'id');
    }

    public function course()
    {
        return $this->belongsTo(Course::class, 'course_id', 'id');
    }

    public function college()
    {
        // Enrollment -> Course -> College
        return $this->hasOneThrough(
            College::class,
            Course::class,
            'id',         // Course primary key
            'id',         // College primary key
            'course_id',  // Enrollment.course_id
            'college_id'  // Course.college_id
        );
    }

    public function yearLevel()
{
    return $this->belongsTo(\App\Models\YearLevel::class, 'year_level_id');
}



}