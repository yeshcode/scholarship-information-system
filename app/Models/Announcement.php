<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Announcement extends Model
{
    protected $fillable = [
        'created_by',
        'title',
        'description',  // Fixed typo
        'image_path',
        'posted_at',
        'audience',
        'scholarship_id',
    ];

    // Add this: Cast 'posted_at' to a datetime for automatic Carbon handling
    protected $casts = [
        'posted_at' => 'datetime',  // Treats it as a Carbon instance
    ];

    // Relationships
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by', 'id');  // Use 'id' as PK
    }

   public function notifications()
    {
        return $this->hasMany(Notification::class, 'related_id', 'id')
            ->where('related_type', 'announcement');
    }


    public function views()
    {
        return $this->hasMany(AnnouncementView::class);
    }

    public function recipients()
    {
        return $this->belongsToMany(\App\Models\User::class, 'announcement_recipients')
            ->withTimestamps();
    }

    public function scholarship()
    {
        return $this->belongsTo(Scholarship::class, 'scholarship_id', 'id');
    }

    // NEW: top-level comments only
    public function comments()
    {
        return $this->hasMany(AnnouncementComment::class, 'announcement_id')
            ->whereNull('parent_id')
            ->orderBy('created_at', 'desc');
    }

    // optional: all comments
    public function allComments()
    {
        return $this->hasMany(AnnouncementComment::class, 'announcement_id')
            ->orderBy('created_at', 'asc');
    }

}