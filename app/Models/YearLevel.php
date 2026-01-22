<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class YearLevel extends Model
{
    protected $fillable = [
        'year_level_name',  // e.g., '1st Year', '2nd Year'
    ];

    // Added: hasMany users (reverse of User belongsTo yearLevel)
    public function users()
    {
        return $this->hasMany(User::class, 'year_level_id', 'id');  // FK in users: year_level_id, local PK: id
    }
}