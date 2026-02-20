<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StipendReleaseFormColumn extends Model
{
    protected $fillable = ['label','key','sort_order','is_active','width'];
}