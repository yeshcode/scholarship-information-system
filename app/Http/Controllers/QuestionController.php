<?php

namespace App\Http\Controllers;

use App\Models\Question;
use App\Models\QuestionCluster;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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

        $text = trim((string) $request->question_text);

        // backend-only threshold (no UI)
        $threshold = (float) config('smis.question_similarity_threshold', 0.40);

        // 1) Get multiple candidates (top 15)
        $candidates = Question::query()
            ->select('id', 'cluster_id', 'question_text')
            ->selectRaw("similarity(question_text, ?) as sim_score", [$text])
            ->whereRaw("similarity(question_text, ?) >= ?", [$text, $threshold])
            ->orderByDesc('sim_score')
            ->limit(15)
            ->get();

        // 2) Pick best cluster among candidates
        $bestClusterId = null;

        if ($candidates->isNotEmpty()) {
            // choose the cluster of the best-scoring candidate that has a cluster_id
            $bestClusterId = $candidates
                ->filter(fn ($q) => !is_null($q->cluster_id))
                ->sortByDesc('sim_score')
                ->first()
                ?->cluster_id;
        }

        // 3) If no good cluster found, create a new cluster
        if (!$bestClusterId) {
            $cluster = QuestionCluster::create([
                'representative_question' => $text,
            ]);
            $bestClusterId = $cluster->id;
        }

        // 4) Save new question as UNANSWERED (status is NOT NULL)
        Question::create([
            'user_id'       => Auth::id(),
            'question_text' => $text,
            'cluster_id'    => $bestClusterId,
            'answer'        => null,
            'status'        => 'unanswered',
        ]);

        return redirect()->route('questions.my')->with('success', 'Your question has been submitted.');
    }


    public function myQuestions()
    {
        $myQuestions = Question::where('user_id', Auth::id())
            ->orderByDesc('id')
            ->paginate(10);

            
        return view('student.my-questions', compact('myQuestions'));
    }
}
