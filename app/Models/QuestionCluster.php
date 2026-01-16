<?php

// app/Models/QuestionCluster.php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class QuestionCluster extends Model
{
    use HasFactory;

    protected $fillable = [
        'label',
        'representative_question',
        'cluster_answer',
    ];

    public function questions()
    {
        return $this->hasMany(Question::class, 'cluster_id');
    }
}

