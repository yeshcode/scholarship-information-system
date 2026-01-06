<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;
use Spatie\Permission\Models\Role;

class User extends Authenticatable
{
    use HasFactory, Notifiable, HasRoles;

    

    protected $fillable = [
        'user_id',           // Unique string ID (for display/custom logic)
        'bisu_email',
        'user_type_id',
        'firstname',
        'lastname',
        'middlename', 
        'student_id',        // For students
        'status',
        'contact_no',
        'password',
        'college_id',        // Added for students
        'year_level_id',     // Added for students
        'section_id',        // Added for students
    ];

    protected $hidden = ['password', 'remember_token'];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    // public function getAuthIdentifierName()
    // {
    //     return 'bisu_email';
    // }

    // Relationships (fixed to use 'id' as local key for FKs)
    public function userType()
    {
        return $this->belongsTo(UserType::class, 'user_type_id', 'id');
    }

    public function college()
    {
        return $this->belongsTo(College::class, 'college_id', 'id');
    }

    public function yearLevel()
    {
        return $this->belongsTo(YearLevel::class, 'year_level_id', 'id');
    }

    public function section()
    {
        return $this->belongsTo(Section::class, 'section_id', 'id');
    }

    public function enrollments()
    {
        return $this->hasMany(Enrollment::class, 'user_id', 'id');  // FK: user_id, local PK: id
    }

    // Fixed: Use 'id' for created_by/updated_by relationships (assumes FKs in other tables point to users.id)
    public function announcements()
    {
        return $this->hasMany(Announcement::class, 'created_by', 'id');
    }

    public function notifications()
    {
        return $this->hasMany(Notification::class, 'recipient_user_id', 'id');
    }

    public function createdNotifications()
    {
        return $this->hasMany(Notification::class, 'created_by', 'id');
    }

    public function stipendsAsStudent()
    {
        return $this->hasMany(Stipend::class, 'student_id', 'id');  // Custom match (if stipends.student_id == users.user_id)
    }

    public function createdStipends()
    {
        return $this->hasMany(Stipend::class, 'created_by', 'id');
    }

    public function updatedStipends()
    {
        return $this->hasMany(Stipend::class, 'updated_by', 'id');
    }

    public function createdStipendReleases()
    {
        return $this->hasMany(StipendsRelease::class, 'created_by', 'id');
    }

    public function updatedStipendReleases()
    {
        return $this->hasMany(StipendsRelease::class, 'updated_by', 'id');
    }

    public function scholarsAsStudent()
    {
        return $this->hasMany(Scholar::class, 'student_id', 'id');  // Custom match
    }

    public function updatedScholars()
    {
        return $this->hasMany(Scholar::class, 'updated_by', 'id');
    }

    public function scholarships()
    {
        return $this->hasMany(Scholarship::class, 'user_id', 'id');  // Assuming FK is to 'id'
    }

    public function updatedScholarships()
    {
        return $this->hasMany(Scholarship::class, 'updated_by', 'id');
    }


    // Helper: Sync user_type with Spatie role on save (optional, for auto-assignment)
    protected static function booted()
    {
        static::saved(function ($user) {
            if ($user->userType) {
                $roleName = $user->userType->name;
                if (!$user->hasRole($roleName) && Role::where('name', $roleName)->exists()) {  // Use 'Role' instead of full namespace
                    $user->assignRole($roleName);
                }
            }
        });
    }

     // Helper: Check if user is a scholar (unchanged)
     public function isScholar()
     {
         return $this->scholarsAsStudent()->exists();
     }
}