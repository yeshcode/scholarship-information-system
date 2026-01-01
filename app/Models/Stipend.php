<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Stipend extends Model
{
    protected $fillable = [
        'scholar_id',     // FK to scholars table
        'student_id',     // FK to users table (as student)
        'created_by',     // FK to users table (as creator)
        'updated_by',     // FK to users table (as updater)
        'stipend_release_id',     // FK to stipend_releases table
        'amount_received',         // e.g., 500.00
        'status',         // e.g., 'pending', 'released'
    ];

    // Relationships
    // belongsTo: Stipend belongs to a scholar (via scholar_id)
    public function scholar()
    {
        return $this->belongsTo(Scholar::class, 'scholar_id', 'id');
    }

    // belongsTo: Stipend belongs to a user (as student, via student_id)
    public function student()
    {
        return $this->belongsTo(User::class, 'student_id', 'id');  // FK: student_id, related PK: user_id (since User uses custom PK)
    }

    // belongsTo: Stipend belongs to a user (as creator, via created_by)
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by', 'id');  // FK: created_by, related PK: user_id
    }

    // belongsTo: Stipend belongs to a user (as updater, via updated_by)
    public function updater()
    {
        return $this->belongsTo(User::class, 'updated_by', 'id');  // FK: updated_by, related PK: user_id
    }

    // belongsTo: Stipend belongs to a stipend release (via release_id)
    public function stipendRelease()
    {
        return $this->belongsTo(StipendsRelease::class, 'stipend_release_id', 'id');
    }
}