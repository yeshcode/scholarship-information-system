<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Announcement extends Model
{
    protected $fillable = [
        'created_by',     // FK to users table (as creator)
        'title',          // e.g., 'New Scholarship Available'
        'descriptions',        // e.g., 'Details about the scholarship...'
        'posted_at',   // e.g., '2023-10-01 10:00:00'
        'when_release',  
        'when_received'       // e.g., 'draft', 'published'
    ];

    // Relationships
    // belongsTo: Announcement belongs to a user (as creator, via created_by)
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by', 'user_id');  // FK: created_by, related PK: user_id (since User uses custom PK)
    }

    // morphMany: Announcement has many notifications (polymorphic, via related_id and related_type in notifications table)
    public function notifications()
    {
        return $this->morphMany(Notification::class, 'related');  // Assuming 'related' is the morph name; adjust if your table uses different column names (e.g., 'notifiable')
    }
}