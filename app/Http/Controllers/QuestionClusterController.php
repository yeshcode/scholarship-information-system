<?php

namespace App\Http\Controllers;

use App\Models\Question;
use App\Models\QuestionCluster;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class QuestionClusterController extends Controller
{
    public function index(Request $request)
    {
        $status = $request->query('status'); // answered | unanswered | null
        $q = trim((string) $request->query('q', ''));

       $threshold = (float) config('smis.question_similarity_threshold', 0.40);

        $clustersQuery = QuestionCluster::query()
            ->withCount('questions')
            ->withCount([
                // ✅ count new unanswered questions AFTER cluster answer was posted
                'questions as new_unanswered_count' => function ($qq) {
                    $qq->where(function ($w) {
                            $w->whereNull('status')->orWhere('status', 'unanswered');
                        })
                        ->whereNull('answer')
                        ->whereColumn('questions.created_at', '>', 'question_clusters.cluster_answered_at');
                }
            ])
            ->orderByDesc('id');


        // Filter answered/unanswered (NULL + empty string safe)
        if ($status === 'answered') {
            $clustersQuery->whereNotNull('cluster_answer')->where('cluster_answer', '<>', '');
        } elseif ($status === 'unanswered') {
            $clustersQuery->where(function ($q) {
                $q->whereNull('cluster_answer')->orWhere('cluster_answer', '=', '');
            });
        }

        // ✅ Search:
        // - text mode: ILIKE like before
        // - similar mode: pg_trgm similarity() against representative_question + any question in cluster
            if ($q !== '') {
                        $clustersQuery->where(function ($sub) use ($q, $threshold) {
                        // keyword matches
                        $sub->where('label', 'ILIKE', "%{$q}%")
                            ->orWhere('representative_question', 'ILIKE', "%{$q}%")
                            ->orWhereHas('questions', function ($qq) use ($q) {
                                $qq->where('question_text', 'ILIKE', "%{$q}%");
                            });

                        // similarity matches (pg_trgm)
                        $sub->orWhereRaw("similarity(representative_question, ?) >= ?", [$q, $threshold])
                            ->orWhereHas('questions', function ($qq) use ($q, $threshold) {
                                $qq->whereRaw("similarity(question_text, ?) >= ?", [$q, $threshold]);
                            });
                })
                ->orderByRaw("similarity(representative_question, ?) DESC", [$q]);
            }

        $clusters = $clustersQuery->paginate(10)->withQueryString();

        return view('coordinator.clusters.index', compact('clusters', 'status', 'q'));

    }

    public function show(Request $request, QuestionCluster $cluster)
    {
        // ✅ flexible threshold for "marked similar" color in the thread UI
        $threshold = (float) $request->query('threshold', 0.40);
        $threshold = max(0.05, min(0.95, $threshold));

        // Load questions + similarity score against representative_question
        $cluster->load(['questions.user']);

        // Add computed similarity per question (pg_trgm)
        $rep = (string) $cluster->representative_question;

        $questionsWithSim = Question::query()
            ->where('cluster_id', $cluster->id)
            ->select('*')
            ->selectRaw("similarity(question_text, ?) as sim_score", [$rep])
            ->orderByDesc('id')
            ->get();

        // Replace relation for blade simplicity
        $cluster->setRelation('questions', $questionsWithSim);

        return view('coordinator.clusters.show', compact('cluster', 'threshold'));
    }

    public function answer(Request $request, QuestionCluster $cluster)
    {
        $request->validate([
            'cluster_answer' => 'required|string',
        ]);

        $cluster->cluster_answer = $request->cluster_answer;
        $cluster->cluster_answered_at = now();
        $cluster->cluster_answered_by = Auth::id();
        $cluster->save();

        // ✅ IMPORTANT CHANGE:
        // DO NOT automatically propagate to every question forever.
        // Only propagate to questions that existed BEFORE answered_at.
        // This keeps “new questions” as unanswered (checkbox selection required).
        $cluster->questions()
            ->where('created_at', '<=', $cluster->cluster_answered_at)
            ->update([
                'answer' => $cluster->cluster_answer,
                'status' => 'answered',
                'answered_at' => now(),
                'answered_by' => Auth::id(),
            ]);

        return redirect()
            ->route('clusters.show', $cluster->id)
            ->with('success', 'Answer posted. New questions after this will require selection before applying.');
    }

    // ✅ NEW: Apply the cluster answer to selected NEW questions only
    public function answerSelected(Request $request, QuestionCluster $cluster)
    {
        $request->validate([
            'question_ids' => 'required|array|min:1',
            'question_ids.*' => 'integer|exists:questions,id',
        ]);

        if (is_null($cluster->cluster_answer) || trim($cluster->cluster_answer) === '') {
            return back()->with('error', 'Cluster has no saved answer yet.');
        }

        Question::query()
            ->where('cluster_id', $cluster->id)
            ->whereIn('id', $request->question_ids)
            ->update([
                'answer' => $cluster->cluster_answer,
                'status' => 'answered',
                'answered_at' => now(),
                'answered_by' => Auth::id(),
            ]);

        return back()->with('success', 'Selected questions were answered using the saved cluster answer.');
    }

    public function answerOne(Request $request, Question $question)
    {
        $request->validate([
            'answer' => 'required|string|max:2000',
        ]);

        $question->update([
            'answer' => $request->answer,
            'status' => 'answered',
            'answered_at' => now(),
            'answered_by' => Auth::id(),
        ]);

        return back()->with('success', 'Answer saved for this specific question.');
    }

    public function bulkAnswer(Request $request, QuestionCluster $cluster)
    {
        $request->validate([
            'answer' => 'required|string|max:2000',
            'question_ids' => 'required|array|min:1',
            'question_ids.*' => 'integer|exists:questions,id',
        ]);

        // Only allow answering questions that belong to this cluster
        $ids = Question::query()
            ->where('cluster_id', $cluster->id)
            ->whereIn('id', $request->question_ids)
            ->pluck('id')
            ->toArray();

        if (count($ids) === 0) {
            return back()->with('error', 'No valid questions selected.');
        }

        // Apply answer to selected questions only
        Question::query()
            ->whereIn('id', $ids)
            ->update([
                'answer' => $request->answer,
                'status' => 'answered',
                'answered_at' => now(),
                'answered_by' => Auth::id(),
            ]);

        // Optional: also save as cluster answer (so future new posts can use it)
        $cluster->cluster_answer = $request->answer;
        $cluster->cluster_answered_at = now();
        $cluster->cluster_answered_by = Auth::id();
        $cluster->save();

        return back()->with('success', 'Selected questions were answered successfully.');
    }

    public function rename(Request $request, QuestionCluster $cluster)
    {
        $request->validate([
            'label' => 'required|string|max:255',
        ]);

        $cluster->label = $request->label;
        $cluster->save();

        return back()->with('success', 'Topic renamed successfully.');
    }


}
