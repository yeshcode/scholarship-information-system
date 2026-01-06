<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Scholarship extends Model
{
    protected $fillable = [
        'scholarship_name',          // e.g., 'Merit Scholarship'
        'description',   // e.g., 'For high-achieving students'
        'requirements',
        'benefactor',
        'status',  // e.g., 'GPA 3.5+'
        'created_by',     
        'updated_by'  // FK to users table (creator)
    ];

    // Relationships
    // belongsTo: Scholarship belongs to a user (via user_id)
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by', 'id');  // FK: user_id, related PK: user_id (since User uses custom PK)
    }

    public function updater()
    {
        return $this->belongsTo(User::class, 'updated_by', 'id');  // FK: updated_by, related PK: user_id
    }

    // hasMany: Scholarship has many scholarship batches (via scholarship_id in scholarship_batches table)
    public function batches()
    {
        return $this->hasMany(ScholarshipBatch::class, 'scholarship_id', 'id');  // FK in scholarship_batches: scholarship_id, local PK: id
    }

    // NEW: hasMany: Scholarship has many scholars (direct, via scholarship_id in scholars table)
    public function scholars()
    {
        return $this->hasMany(Scholar::class, 'scholarship_id', 'id');  // FK in scholars: scholarship_id, local PK: id
    }
}