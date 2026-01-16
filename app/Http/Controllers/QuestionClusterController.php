<?php

// app/Http/Controllers/QuestionClusterController.php

namespace App\Http\Controllers;

use App\Models\QuestionCluster;
use Illuminate\Http\Request;

class QuestionClusterController extends Controller
{
    public function index()
    {
        // Show clusters with question count
        $clusters = QuestionCluster::withCount('questions')
            ->orderByDesc('questions_count')
            ->get();

        return view('coordinator.clusters.index', compact('clusters'));
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
}

