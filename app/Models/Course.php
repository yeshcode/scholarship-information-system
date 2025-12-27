<?php

namespace App\Models;  // 1. Namespace: Groups this file with other models in the app/Models folder.

use Illuminate\Database\Eloquent\Model;  // 2. Import: Brings in Laravel's base Model class so we can extend it.

class Course extends Model  // 3. Class Declaration: Defines the Course model, extending Model (inherits Laravel's features).
{
    protected $fillable = [  // 4. Fillable Array: Lists columns that can be mass-assigned (e.g., when creating/updating via forms or seeders).
        'course_name',              // e.g., 'Computer Science' - Allows safe data insertion.
        'course_description',       // e.g., 'Intro to CS' - Allows safe data insertion.
        'college_id',        // FK to colleges table - Allows safe data insertion for the relationship.
    ];

    // Relationships: Define how this table connects to others (based on your description).
    // belongsTo: Course belongs to a college (one course per college).
    public function college()  // 5. Method Name: Matches the related table (lowercase, singular).
    {
        return $this->belongsTo(College::class, 'college_id', 'id');  // FK: college_id (in courses), related PK: id (in colleges)
    }

    // hasMany: Course has many sections (one course can have multiple sections).
    public function sections()  // 6. Method Name: Matches the related table (lowercase, plural).
    {
        return $this->hasMany(Section::class, 'course_id', 'id');  // FK in sections: course_id, local PK: id
    }
}