<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserType extends Model
{
    protected $fillable = [
        'name',           // e.g., 'Admin', 'Student', 'Coordinator'
        'description',    // e.g., 'Administrator with full access'
        'dashboard_url', 
    ];

    // Relationships
    // hasMany: UserType has many users (via user_type_id in users table)
    public function users()
    {
        return $this->hasMany(User::class, 'user_type_id', 'id');  // FK in users: user_type_id, local PK: id
    }
}