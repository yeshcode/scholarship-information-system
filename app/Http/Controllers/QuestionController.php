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
     * Simple normalization to improve clustering across English/Cebuano/Tagalog.
     * Goal: make "what are requirements of TES" and "unsay requirements sa TES"
     * end up closer by removing fillers + punctuation + casing differences.
     */
    private function normalizeQuestion(string $text): string
    {
        $text = mb_strtolower(trim($text));

        // replace punctuation with spaces
        $text = preg_replace('/[^\p{L}\p{N}\s]+/u', ' ', $text);

        // collapse multiple spaces
        $text = preg_replace('/\s+/', ' ', trim($text));

        // remove common filler words (keep this list SHORT and safe)
       $stop = [
            // English
            'what','whats','is','are','the','a','an','of','for','to','please', 'when', 'how', 'where', 'who', 'which',
            // Cebuano / Bisaya
            'unsa','unsay','sa','para','palihug', 'kanus a', 
            // Tagalog
            'ano','ang','mga','para','po', 'ng', 'kailan', 'paano', 'saan', 'sino', 
        ];

        $words = array_values(array_filter(
            explode(' ', $text),
            fn ($w) => $w !== '' && !in_array($w, $stop, true)
        ));

        // optional: put words in consistent order (helps small variations)
        sort($words);

        return implode(' ', $words);
    }

    public function store(Request $request)
    {
        $request->validate([
            'question_text' => 'required|string|max:1000',
        ]);

        $text = trim((string) $request->question_text);

        // backend-only threshold (no UI)
        $threshold = (float) config('smis.question_similarity_threshold', 0.40);

        // ✅ Compare using normalized text
        $norm = $this->normalizeQuestion($text);

        // 1) Get multiple candidates (top 15) based on normalized similarity
        // NOTE: This assumes you have "question_text_norm" column.
        // If you DON'T have it yet, see the "No column yet" version below.
        $candidates = Question::query()
            ->select('id', 'cluster_id', 'question_text')
            ->selectRaw("similarity(question_text_norm, ?) as sim_score", [$norm])
            ->whereRaw("similarity(question_text_norm, ?) >= ?", [$norm, $threshold])
            ->orderByDesc('sim_score')
            ->limit(15)
            ->get();

        // 2) Pick best cluster among candidates
        $bestClusterId = $candidates
            ->filter(fn ($q) => !is_null($q->cluster_id))
            ->sortByDesc('sim_score')
            ->first()
            ?->cluster_id;

        // 3) If no good cluster found, create a new cluster
        if (!$bestClusterId) {
            $cluster = QuestionCluster::create([
                'representative_question' => $text,
                'representative_question_norm' => $norm,
                'label' => $this->generateClusterLabel($text),
            ]);
            $bestClusterId = $cluster->id;
        }

        // 4) Save new question as UNANSWERED (status is NOT NULL)
        Question::create([
            'user_id'            => Auth::id(),
            'question_text'      => $text,
            'question_text_norm' => $norm,  // store normalized
            'cluster_id'         => $bestClusterId,
            'answer'             => null,
            'status'             => 'unanswered',
        ]);

        return redirect()->route('questions.my')->with('success', 'Your question has been submitted.');
    }

    private function generateClusterLabel(string $text): string
    {
        $text = mb_strtolower($text);

        if (str_contains($text, 'tes') && str_contains($text, 'requirement')) {
            return 'TES Application Requirements';
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
