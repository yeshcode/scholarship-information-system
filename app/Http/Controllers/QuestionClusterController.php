<?php

// app/Http/Controllers/QuestionClusterController.php

namespace App\Http\Controllers;

use App\Models\QuestionCluster;
use Illuminate\Http\Request;

class QuestionClusterController extends Controller
{
    public function index(Request $request)
    {
        $status = $request->query('status'); // answered | unanswered | null
        $q = trim((string) $request->query('q')); // search keyword

        $clustersQuery = \App\Models\QuestionCluster::query()
            ->withCount('questions')
            ->orderByDesc('id');

        // ✅ filter answered/unanswered
        // ✅ filter answered/unanswered (NULL + empty string safe)
        if ($status === 'answered') {
            $clustersQuery->whereNotNull('cluster_answer')
                        ->where('cluster_answer', '<>', '');
        } elseif ($status === 'unanswered') {
            $clustersQuery->where(function ($q) {
                $q->whereNull('cluster_answer')
                ->orWhere('cluster_answer', '=', '');
            });
        }



        // ✅ search by label OR representative_question OR any question inside the cluster
        if ($q !== '') {
            $clustersQuery->where(function ($sub) use ($q) {
                $sub->where('label', 'ILIKE', "%{$q}%")
                    ->orWhere('representative_question', 'ILIKE', "%{$q}%")
                    ->orWhereHas('questions', function ($qq) use ($q) {
                        $qq->where('question_text', 'ILIKE', "%{$q}%");
                    });
            });
        }

        $clusters = $clustersQuery->paginate(10)->withQueryString();

        return view('coordinator.clusters.index', compact('clusters', 'status', 'q'));
    }


    public function show(QuestionCluster $cluster)
    {
        $cluster->load(['questions.user']);

        return view('coordinator.clusters.show', compact('cluster'));
    }

    public function answer(Request $request, QuestionCluster $cluster)
    {
        $request->validate([
            'cluster_answer' => 'required|string',
        ]);

        $cluster->cluster_answer = $request->cluster_answer;
        $cluster->save();

        // Propagate to individual questions in this cluster
        $cluster->questions()->update([
            'answer' => $cluster->cluster_answer,
            'status' => 'answered',
        ]);

        return redirect()->route('clusters.show', $cluster->id)
            ->with('success', 'Answer sent to all students in this cluster.');
    }

    public function answerOne(Request $request, \App\Models\Question $question)
    {
        $request->validate([
            'answer' => 'required|string|max:2000',
        ]);

        $question->update([
            'answer' => $request->answer,
            'answered_at' => now(),
            'answered_by' => auth()->id(),
        ]);

        return back()->with('success', 'Answer saved for this specific question.');
    }

}

