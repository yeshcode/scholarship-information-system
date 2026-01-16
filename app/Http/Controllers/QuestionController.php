<?php

// app/Http/Controllers/QuestionController.php

namespace App\Http\Controllers;

use App\Models\Question;
use App\Models\QuestionCluster;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class QuestionController extends Controller
{
    public function create()
    {
        return view('student.ask');
    }

    public function store(Request $request)
    {
        $request->validate([
            'question_text' => 'required|string|max:1000',
        ]);

        $text = $request->question_text;

        // 1) Find similar existing question using pg_trgm similarity
        $similarQuestion = Question::select('*')
            ->whereRaw("similarity(question_text, ?) > 0.4", [$text]) // threshold can be tuned
            ->orderByRaw("similarity(question_text, ?) DESC", [$text])
            ->first();

        $clusterId = null;

        if ($similarQuestion && $similarQuestion->cluster_id) {
            // Case 1: similar question already belongs to a cluster
            $clusterId = $similarQuestion->cluster_id;
        } elseif ($similarQuestion && !$similarQuestion->cluster_id) {
            // Case 2: similar question exists but no cluster yet -> create new cluster
            $cluster = QuestionCluster::create([
                'representative_question' => $similarQuestion->question_text,
            ]);

            $similarQuestion->cluster_id = $cluster->id;
            $similarQuestion->save();

            $clusterId = $cluster->id;
        } else {
            // Case 3: totally new question -> new cluster
            $cluster = QuestionCluster::create([
                'representative_question' => $text,
            ]);

            $clusterId = $cluster->id;
        }

        // 2) Save the new question
        $question = Question::create([
            'user_id'       => auth()->id(),
            'question_text' => $text,
            'cluster_id'    => $clusterId,
        ]);

        return redirect()->route('questions.my')
            ->with('success', 'Your question has been submitted.');
    }

    public function myQuestions()
    {
        $questions = Question::with('cluster')
            ->where('user_id', auth()->id())
            ->latest()
            ->get();

        return view('student.my-questions', compact('questions'));
    }
}

