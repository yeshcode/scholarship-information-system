<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StipendReleaseForm extends Model
{
    protected $fillable = [
        'stipend_release_id',
        'original_name',
        'path',
        'mime',
        'uploaded_by',
    ];

    public function release()
    {
        return $this->belongsTo(StipendsRelease::class, 'stipend_release_id');
    }

    public function uploader()
    {
        return $this->belongsTo(User::class, 'uploaded_by');
    }
}