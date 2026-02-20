<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StipendsRelease extends Model
{
    protected $table = 'stipend_releases';  // NEW: Explicitly set the table name to match the renamed table

    protected $fillable = [
        'title',
        'batch_id',       // FK to scholarship_batches table
        'semester_id',
        'created_by',     // FK to users table (as creator)
        'updated_by',     // FK to users table (as updater)
        'date_release',   // e.g., '2023-10-01'
        'amount',   // e.g., 10000.00
        'status',
        'notes'         // e.g., 'pending', 'released'
    ];

    // Relationships
    // belongsTo: StipendRelease belongs to a scholarship batch (via batch_id)
    public function scholarshipBatch()
    {
        return $this->belongsTo(ScholarshipBatch::class, 'batch_id', 'id');
    }

    // belongsTo: StipendRelease belongs to a user (as creator, via created_by)
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by', 'id');  // Updated to use 'id' as PK
    }

    // belongsTo: StipendRelease belongs to a user (as updater, via updated_by)
    public function updater()
    {
        return $this->belongsTo(User::class, 'updated_by', 'id');  // Updated to use 'id' as PK
    }

    // hasMany: StipendRelease has many stipends (via release_id in stipends table)
    public function stipends()
    {
        return $this->hasMany(Stipend::class, 'stipend_release_id', 'id');  // FK in stipends: release_id, local PK: id
    }

    // morphMany: StipendRelease has many notifications (polymorphic, via related_id and related_type in notifications table)
    public function notifications()
    {
        return $this->morphMany(Notification::class, 'related');  // Assuming 'related' is the morph name; adjust if your table uses different column names (e.g., 'notifiable')
    }

    public function semester()
    {
        return $this->belongsTo(\App\Models\Semester::class, 'semester_id');
    }

    public function forms()
    {
        return $this->hasMany(\App\Models\StipendReleaseForm::class, 'stipend_release_id');
    }
    
}