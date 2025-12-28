<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    protected $fillable = [
        'user_id',        // FK to users table (recipient or associated user)
        'related_id',     // ID of the related entity (e.g., stipend_release or announcement)
        'related_type',   // Type of the related entity (e.g., 'App\Models\StipendRelease' or 'App\Models\Announcement')
        'title',
        'recipient_user_id',
        'created_by', 
        'type',         // e.g., 'Stipend Released'
        'message',        // e.g., 'Your stipend has been released.'
        'is_read',
        'date'        // e.g., 0 or 1
    ];

    // Relationships
    // belongsTo: Notification belongs to a user (via user_id)
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'user_id');  // FK: user_id, related PK: user_id (since User uses custom PK)
    }

    public function receivedby()
    {
        return $this->belongsTo(User::class, 'recipient_user_id', 'user_id');  // FK: user_id, related PK: user_id (since User uses custom PK)
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by', 'user_id');  // FK: user_id, related PK: user_id (since User uses custom PK)
    }

    // morphTo: Notification belongs to a related entity (polymorphic, via related_id and related_type)
    // This can link to StipendRelease, Announcement, or other models
    public function related()
    {
        return $this->morphTo();
    }
}