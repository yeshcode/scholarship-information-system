<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class College extends Model
{
    protected $fillable = [
        'college_name',  // e.g., 'College of Engineering'
    ];

    // Relationships
    // hasMany: College has many courses (via college_id in courses table)
    public function courses()
    {
        return $this->hasMany(Course::class, 'college_id', 'id');  // FK in courses: college_id, local PK: id
    }

    // Added: hasMany users (reverse of User belongsTo college)
    public function users()
    {
        return $this->hasMany(User::class, 'college_id', 'id');  // FK in users: college_id, local PK: id
    }
}