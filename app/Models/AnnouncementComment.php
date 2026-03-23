<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AnnouncementComment extends Model
{
    protected $fillable = [
        'announcement_id',
        'user_id',
        'parent_id',
        'comment',
    ];

    public function announcement()
    {
        return $this->belongsTo(Announcement::class, 'announcement_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function parent()
    {
        return $this->belongsTo(AnnouncementComment::class, 'parent_id');
    }

    public function replies()
    {
        return $this->hasMany(AnnouncementComment::class, 'parent_id')->orderBy('created_at', 'asc');
    }
}