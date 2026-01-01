<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Announcement extends Model
{
    protected $fillable = [
        'created_by',
        'title',
        'description',  // Fixed typo
        'posted_at',
    ];

    // Relationships
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by', 'id');  // Use 'id' as PK
    }

    public function notifications()
    {
        return $this->morphMany(Notification::class, 'related');
    }
}