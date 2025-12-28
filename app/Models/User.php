<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;  // Required for Spatie role assignments (e.g., assignRole())

class User extends Authenticatable
{
    use HasFactory, Notifiable, HasRoles;  // Includes HasRoles for roles and permissions

    // Fields that can be mass-assigned (must match your users migration exactly)
    protected $fillable = [
        'user_id',           // Unique user ID
        'bisu_email',        // Email for login
        'user_type_id',      // Foreign key to user_types table
        'firstname',         // User's first name
        'lastname',          // User's last name
        'student_id',        // Student ID (for students only)
        'status',            // e.g., 'active' or 'inactive'
        'contact_no',        // Contact number
        'password',          // Hashed password
    ];

    // Fields to hide in JSON responses (security)
    protected $hidden = [
        'password',
        'remember_token',
    ];

    // Casts for automatic type conversion
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    // Relationship: User belongs to a UserType (links to user_types table)
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
