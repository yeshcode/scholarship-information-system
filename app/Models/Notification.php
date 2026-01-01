<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    protected $fillable = [
        'recipient_user_id',
        'created_by',
        'type',
        'title',
        'message',  // Fixed typo
        'related_type',
        'related_id',
        'is_read',
        'date',
    ];

    // Relationships
    public function recipient()
    {
        return $this->belongsTo(User::class, 'recipient_user_id', 'id');  // Use 'id' as PK
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by', 'id');
    }

    public function related()
    {
        return $this->morphTo();
    }
}