<?php

// app/Models/Question.php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Question extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'cluster_id',
        'question_text',
        'status',
        'answer',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function cluster()
    {
        return $this->belongsTo(QuestionCluster::class, 'cluster_id');
    }
}
