<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class YearLevel extends Model
{
    protected $fillable = [
        'year_level_name',  // e.g., '1st Year', '2nd Year'
    ];

    // Relationships
    // hasMany: YearLevel has many sections (via year_level_id in sections table)
    public function sections()
    {
        return $this->hasMany(Section::class, 'year_level_id', 'id');  // FK in sections: year_level_id, local PK: id
    }
}