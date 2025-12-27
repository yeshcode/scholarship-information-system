<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
// If using Laravel auth, change to: use Illuminate\Foundation\Auth\User as Authenticatable; and extend Authenticatable instead of Model.

class User extends Model
{
    protected $primaryKey = 'user_id';  // Custom PK as per your design
    public $incrementing = false;       // If user_id is not auto-incrementing
    protected $keyType = 'string';      // Adjust if user_id is string (e.g., UUID)

    protected $fillable = [
        'user_id',       // Custom PK
        'user_type_id',  // FK to user_types table
        'first_name',
        'last_name',          // e.g., 'John Doe'
        'bisu_email',         // e.g., 'john@example.com'
        'password', 
        'contact_no'     // Hashed password
        // Add other fields like phone, address, etc., if applicable
    ];

    // Relationships
    // belongsTo: User belongs to a user type (via user_type_id)
    public function userType()
    {
        return $this->belongsTo(UserType::class, 'user_type_id', 'id');
    }

    // hasMany: User has many announcements (via created_by in announcements table)
    public function announcements()
    {
        return $this->hasMany(Announcement::class, 'created_by', 'user_id');  // FK in announcements: created_by, local PK: user_id
    }

    // hasMany: User has many notifications (via recipient_user_id in notifications table, as recipient)
    public function notifications()
    {
        return $this->hasMany(Notification::class, 'recipient_user_id', 'user_id');  // Assuming recipient_user_id is the FK; adjust if it's user_id
    }

    // hasMany: User has many notifications (via created_by in notifications table, as creator)
    public function createdNotifications()
    {
        return $this->hasMany(Notification::class, 'created_by', 'user_id');  // FK in notifications: created_by, local PK: user_id
    }

    // hasMany: User has many stipends (via student_id in stipends table, as student)
    public function stipendsAsStudent()
    {
        return $this->hasMany(Stipend::class, 'student_id', 'user_id');  // FK in stipends: student_id, local PK: user_id
    }

    // hasMany: User has many stipends (via created_by in stipends table, as creator)
    public function createdStipends()
    {
        return $this->hasMany(Stipend::class, 'created_by', 'user_id');  // FK in stipends: created_by, local PK: user_id
    }

    // hasMany: User has many stipends (via updated_by in stipends table, as updater)
    public function updatedStipends()
    {
        return $this->hasMany(Stipend::class, 'updated_by', 'user_id');  // FK in stipends: updated_by, local PK: user_id
    }

    // hasMany: User has many stipend releases (via created_by in stipend_releases table, as creator)
    public function createdStipendReleases()
    {
        return $this->hasMany(StipendsRelease::class, 'created_by', 'user_id');  // FK in stipend_releases: created_by, local PK: user_id
    }

    // hasMany: User has many stipend releases (via updated_by in stipend_releases table, as updater)
    public function updatedStipendReleases()
    {
        return $this->hasMany(StipendsRelease::class, 'updated_by', 'user_id');  // FK in stipend_releases: updated_by, local PK: user_id
    }

    // hasMany: User has many scholars (via student_id in scholars table, as student)
    public function scholarsAsStudent()
    {
        return $this->hasMany(Scholar::class, 'student_id', 'user_id');  // FK in scholars: student_id, local PK: user_id
    }

    // hasMany: User has many scholars (via updated_by in scholars table, as updater)
    public function updatedScholars()
    {
        return $this->hasMany(Scholar::class, 'updated_by', 'user_id');  // FK in scholars: updated_by, local PK: user_id
    }

    // hasMany: User has many scholarships (via user_id in scholarships table, as creator)
    public function scholarships()
    {
        return $this->hasMany(Scholarship::class, 'user_id', 'user_id');  // FK in scholarships: user_id, local PK: user_id
    }

    // hasMany: User has many scholarships (via updated_by in scholarships table, as updater) - Add if your scholarships table has updated_by
    public function updatedScholarships()
    {
        return $this->hasMany(Scholarship::class, 'updated_by', 'user_id');  // FK in scholarships: updated_by, local PK: user_id
    }

    // hasMany: User has many enrollments (via student_id in enrollments table, as student)
    public function enrollments()
    {
        return $this->hasMany(Enrollment::class, 'student_id', 'user_id');  // FK in enrollments: student_id, local PK: user_id
    }
}