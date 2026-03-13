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

    /**
     * Normalize text for better similarity matching.
     * Makes small wording differences closer:
     * - lowercase
     * - remove punctuation
     * - remove filler words
     * - unify singular/plural
     * - sort words for stable comparison
     */
   private function normalizeQuestion(string $text): string
{
    $text = mb_strtolower(trim($text));

    // remove punctuation/special chars
    $text = preg_replace('/[^\p{L}\p{N}\s]+/u', ' ', $text);

    // collapse spaces
    $text = preg_replace('/\s+/', ' ', trim($text));

    $stop = [
        // English
        'what', 'whats', 'is', 'are', 'the', 'a', 'an', 'of', 'for', 'to', 'please',
        'when', 'how', 'where', 'who', 'which', 'there', 'any', 'do', 'does', 'did',
        'can', 'could', 'would', 'should',

        // Cebuano / Bisaya
        'unsa', 'unsay', 'sa', 'para', 'palihug', 'kanusa', 'naa', 'bay', 'ba',

        // Tagalog
        'ano', 'ang', 'mga', 'para', 'po', 'ng', 'kailan', 'paano', 'saan', 'sino',
        'meron', 'may', 'ba'
    ];

    $words = array_values(array_filter(explode(' ', $text), function ($w) use ($stop) {
        return $w !== '' && !in_array($w, $stop, true);
    }));

    $map = [
        'announcements' => 'announcement',
        'scholars' => 'scholar',
        'requirements' => 'requirement',
        'applications' => 'application',
        'deadlines' => 'deadline',
        'documents' => 'document',
        'updates' => 'update',
    ];

    $words = array_map(function ($w) use ($map) {
        return $map[$w] ?? $w;
    }, $words);

    $words = array_values(array_unique($words));

    sort($words);

    return implode(' ', $words);
}

    public function store(Request $request)
{
    $request->validate([
        'question_text' => 'required|string|max:1000',
    ]);

    $text = trim((string) $request->question_text);

    // Use a slightly safer threshold
    $threshold = (float) config('smis.question_similarity_threshold', 0.35);

    $norm = $this->normalizeQuestion($text);

    $bestClusterId = null;

    /**
     * STEP 1: Match existing clusters using NORMALIZED text only
     * This avoids unrelated questions being forced into old clusters.
     */
    $bestCluster = QuestionCluster::query()
        ->select('id', 'representative_question', 'representative_question_norm')
        ->selectRaw("similarity(COALESCE(representative_question_norm, ''), ?) as sim_score", [$norm])
        ->whereRaw("similarity(COALESCE(representative_question_norm, ''), ?) >= ?", [$norm, $threshold])
        ->orderByDesc('sim_score')
        ->first();

    if ($bestCluster) {
        $bestClusterId = $bestCluster->id;
    }

    /**
     * STEP 2: If no cluster matched, try existing questions using NORMALIZED text only
     */
    if (!$bestClusterId) {
        $bestQuestion = Question::query()
            ->select('id', 'cluster_id', 'question_text', 'question_text_norm')
            ->selectRaw("similarity(COALESCE(question_text_norm, ''), ?) as sim_score", [$norm])
            ->whereNotNull('cluster_id')
            ->whereRaw("similarity(COALESCE(question_text_norm, ''), ?) >= ?", [$norm, $threshold])
            ->orderByDesc('sim_score')
            ->first();

        if ($bestQuestion) {
            $bestClusterId = $bestQuestion->cluster_id;
        }
    }

    /**
     * STEP 3: Create new cluster if still no match
     */
    if (!$bestClusterId) {
        $cluster = QuestionCluster::create([
            'representative_question' => $text,
            'representative_question_norm' => $norm,
            'label' => $this->generateClusterLabel($text),
        ]);

        $bestClusterId = $cluster->id;
    }

    /**
     * STEP 4: Save the question
     */
    Question::create([
        'user_id'            => Auth::id(),
        'question_text'      => $text,
        'question_text_norm' => $norm,
        'cluster_id'         => $bestClusterId,
        'answer'             => null,
        'status'             => 'unanswered',
    ]);

    return redirect()
        ->route('questions.my')
        ->with('success', 'Your question has been submitted.');
}

    private function generateClusterLabel(string $text): string
    {
        $text = mb_strtolower($text);

        if (str_contains($text, 'tes') && str_contains($text, 'requirement')) {
            return 'TES Application Requirements';
        }

        if (str_contains($text, 'tdp') && str_contains($text, 'announcement')) {
            return 'TDP Scholar Announcements';
        }

        if (str_contains($text, 'deadline')) {
            return 'Application Deadlines';
        }

        if (str_contains($text, 'stipend')) {
            return 'Stipend Concerns';
        }

        if (str_contains($text, 'apply') || str_contains($text, 'application')) {
            return 'Application Process';
        }

        return 'General Inquiry';
    }

    public function myQuestions()
    {
        $myQuestions = Question::where('user_id', Auth::id())
            ->orderByDesc('id')
            ->paginate(10);

        return view('student.my-questions', compact('myQuestions'));
    }
}