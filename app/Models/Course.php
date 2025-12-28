<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Course extends Model
{
    protected $fillable = [
        'course_name',              // e.g., 'Computer Science'
        'course_description',       // e.g., 'Intro to CS'
        'college_id',               // FK to colleges table
    ];

    // Relationships
    // belongsTo: Course belongs to a college (via college_id)
    public function college()
    {
        return $this->belongsTo(College::class, 'college_id', 'id');  // FK: college_id, related PK: id
    }

    // hasMany: Course has many sections (via course_id in sections table)
    public function sections()
    {
        return $this->hasMany(Section::class, 'course_id', 'id');  // FK in sections: course_id, local PK: id
    }
}