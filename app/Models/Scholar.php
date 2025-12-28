<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Scholar extends Model
{
    protected $fillable = [
        'student_id',     // FK to users table (as student)
        'batch_id',       // FK to scholarship_batches table
        'updated_by', 
        'date_added',    // FK to users table (as coordinator/updater)
        'status',         // e.g., 'active', 'inactive'
    ];

    // Relationships
    // belongsTo: Scholar belongs to a user (as student, via student_id)
    public function user()
    {
        return $this->belongsTo(User::class, 'student_id', 'user_id');  // FK: student_id, related PK: user_id (since User uses custom PK)
    }

    // belongsTo: Scholar belongs to a scholarship batch (via batch_id)
    public function scholarshipBatch()
    {
        return $this->belongsTo(ScholarshipBatch::class, 'batch_id', 'id');
    }

    // belongsTo: Scholar belongs to a user (as updater/coordinator, via updated_by)
    public function updater()
    {
        return $this->belongsTo(User::class, 'updated_by', 'user_id');  // FK: updated_by, related PK: user_id
    }

    // hasMany: Scholar has many stipends (via scholar_id in stipends table)
    public function stipends()
    {
        return $this->hasMany(Stipend::class, 'scholar_id', 'id');  // FK in stipends: scholar_id, local PK: id
    }
}