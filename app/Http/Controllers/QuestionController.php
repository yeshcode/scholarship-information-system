<?php

namespace App\Http\Controllers;

use App\Models\Question;
use App\Models\QuestionCluster;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

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
            'can', 'could', 'would', 'should', 'will', 'be',

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
            // plural / singular
            'announcements' => 'announcement',
            'updates' => 'update',
            'details' => 'detail',
            'requirements' => 'requirement',
            'documents' => 'document',
            'qualifications' => 'qualification',
            'scholars' => 'scholar',
            'benefits' => 'benefit',

            // release-related
            'released' => 'release',
            'releasing' => 'release',
            'schedule' => 'schedule',

            // status-related
            'accepted' => 'accepted',
            'qualified' => 'qualified',
            'verify' => 'verify',
            'verified' => 'verify',

            // deadline-related
            'submission' => 'submit',
            'applying' => 'apply',
            'applied' => 'apply',
        ];

        $words = array_map(function ($w) use ($map) {
            return $map[$w] ?? $w;
        }, $words);

        $wordSet = array_flip($words);

        // -------------------------
        // Intent tagging
        // -------------------------

        // 1. Stipend Release
        if (
            isset($wordSet['stipend']) &&
            (
                isset($wordSet['release']) ||
                isset($wordSet['schedule']) ||
                isset($wordSet['date']) ||
                isset($wordSet['available'])
            )
        ) {
            $words[] = 'intent_stipend_release';
        }

        // 2. Scholarship Requirements
        if (
            isset($wordSet['scholarship']) &&
            (
                isset($wordSet['requirement']) ||
                isset($wordSet['document']) ||
                isset($wordSet['submit']) ||
                isset($wordSet['qualification']) ||
                isset($wordSet['eligibility'])
            )
        ) {
            $words[] = 'intent_scholarship_requirement';
        }

        // 3. Scholarship Information
        if (
            isset($wordSet['scholarship']) &&
            (
                isset($wordSet['announcement']) ||
                isset($wordSet['update']) ||
                isset($wordSet['detail']) ||
                isset($wordSet['available'])
            )
        ) {
            $words[] = 'intent_scholarship_information';
        }

        // 4. Scholarship Deadlines
        if (
            isset($wordSet['scholarship']) &&
            (
                isset($wordSet['deadline']) ||
                isset($wordSet['last']) ||
                isset($wordSet['until']) ||
                isset($wordSet['apply']) ||
                isset($wordSet['submit'])
            )
        ) {
            $words[] = 'intent_scholarship_deadline';
        }

        // 5. Scholar Status
        if (
            isset($wordSet['scholarship']) &&
            (
                isset($wordSet['status']) ||
                isset($wordSet['accepted']) ||
                isset($wordSet['qualified']) ||
                isset($wordSet['verify'])
            )
        ) {
            $words[] = 'intent_scholar_status';
        }

        // 6. Scholarship Benefits
        if (
            isset($wordSet['scholarship']) &&
            (
                isset($wordSet['benefit']) ||
                isset($wordSet['receive']) ||
                isset($wordSet['include']) ||
                isset($wordSet['allowance']) ||
                isset($wordSet['stipend'])
            ) &&
            !in_array('intent_stipend_release', $words, true)
        ) {
            $words[] = 'intent_scholarship_benefit';
        }

        if (
            isset($wordSet['scholarship']) &&
            (
                isset($wordSet['apply']) ||
                isset($wordSet['application']) ||
                isset($wordSet['process']) ||
                isset($wordSet['submit'])
            )
        ) {
            $words[] = 'intent_scholarship_application';
        }

        $words = array_values(array_unique($words));
        sort($words);

        return implode(' ', $words);
    }



    private function detectIntent(string $norm): string
    {
        if (str_contains($norm, 'intent_stipend_release')) {
            return 'stipend_release';
        }

        if (str_contains($norm, 'intent_scholarship_requirement')) {
            return 'scholarship_requirement';
        }

        if (str_contains($norm, 'intent_scholarship_information')) {
            return 'scholarship_information';
        }

        if (str_contains($norm, 'intent_scholarship_deadline')) {
            return 'scholarship_deadline';
        }

        if (str_contains($norm, 'intent_scholar_status')) {
            return 'scholar_status';
        }

        if (str_contains($norm, 'intent_scholarship_benefit')) {
            return 'scholarship_benefit';
        }

        if (str_contains($norm, 'intent_scholarship_application')) {
            return 'scholarship_application';
        }

        return 'general';
    }


    public function store(Request $request)
    {
        $request->validate([
            'question_text' => 'required|string|max:1000',
        ]);

        $text = trim((string) $request->question_text);
        $threshold = (float) config('smis.question_similarity_threshold', 0.35);

        $norm = $this->normalizeQuestion($text);
        $intent = $this->detectIntent($norm);

        $bestClusterId = null;
        $bestCluster = null;
        $bestQuestion = null;

        /**
         * STEP 1: Match existing clusters of the SAME intent only
         */
        $bestCluster = QuestionCluster::query()
            ->where('intent', $intent)
            ->select('id', 'intent', 'representative_question', 'representative_question_norm')
            ->selectRaw("
                GREATEST(
                    similarity(COALESCE(representative_question_norm, ''), ?),
                    word_similarity(COALESCE(representative_question_norm, ''), ?)
                ) as sim_score
            ", [$norm, $norm])
            ->whereRaw("
                GREATEST(
                    similarity(COALESCE(representative_question_norm, ''), ?),
                    word_similarity(COALESCE(representative_question_norm, ''), ?)
                ) >= ?
            ", [$norm, $norm, $threshold])
            ->orderByDesc('sim_score')
            ->first();

        if ($bestCluster) {
            $bestClusterId = $bestCluster->id;
        }

        /**
         * STEP 2: If no cluster matched, try existing questions of the SAME intent only
         */
        if (!$bestClusterId) {
            $bestQuestion = Question::query()
                ->join('question_clusters', 'questions.cluster_id', '=', 'question_clusters.id')
                ->where('question_clusters.intent', $intent)
                ->select(
                    'questions.id',
                    'questions.cluster_id',
                    'questions.question_text',
                    'questions.question_text_norm'
                )
                ->selectRaw("
                    GREATEST(
                        similarity(COALESCE(questions.question_text_norm, ''), ?),
                        word_similarity(COALESCE(questions.question_text_norm, ''), ?)
                    ) as sim_score
                ", [$norm, $norm])
                ->whereRaw("
                    GREATEST(
                        similarity(COALESCE(questions.question_text_norm, ''), ?),
                        word_similarity(COALESCE(questions.question_text_norm, ''), ?)
                    ) >= ?
                ", [$norm, $norm, $threshold])
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
                'label' => $this->generateClusterLabel($text),
                'intent' => $intent,
                'representative_question' => $text,
                'representative_question_norm' => $norm,
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

        Log::info('Question clustering debug', [
            'original_text' => $text,
            'normalized_text' => $norm,
            'intent' => $intent,
            'threshold' => $threshold,
            'matched_cluster_id' => $bestClusterId,
        ]);

        if ($bestCluster) {
            Log::info('Best cluster match', [
                'cluster_id' => $bestCluster->id,
                'intent' => $bestCluster->intent,
                'rep_norm' => $bestCluster->representative_question_norm,
                'score' => $bestCluster->sim_score,
            ]);
        }

        if ($bestQuestion) {
            Log::info('Best question match', [
                'question_id' => $bestQuestion->id,
                'question_norm' => $bestQuestion->question_text_norm,
                'score' => $bestQuestion->sim_score,
                'cluster_id' => $bestQuestion->cluster_id,
            ]);
        }

        return redirect()
            ->route('questions.my')
            ->with('success', 'Your question has been submitted.');
    }

    private function generateClusterLabel(string $text): string
    {
        $norm = $this->normalizeQuestion($text);

        if (str_contains($norm, 'intent_stipend_release')) {
            return 'Stipend Release';
        }

        if (str_contains($norm, 'intent_scholarship_requirement')) {
            return 'Scholarship Requirements';
        }

        if (str_contains($norm, 'intent_scholarship_information')) {
            return 'Scholarship Information';
        }

        if (str_contains($norm, 'intent_scholarship_deadline')) {
            return 'Scholarship Deadlines';
        }

        if (str_contains($norm, 'intent_scholar_status')) {
            return 'Scholar Status';
        }

        if (str_contains($norm, 'intent_scholarship_benefit')) {
            return 'Scholarship Benefits';
        }

        if (str_contains($norm, 'intent_scholarship_application')) {
            return 'Scholarship Application';
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

    public function destroyMyQuestion(Question $question)
    {
        // Make sure students can only delete their own question
        if ($question->user_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        $question->delete();

        return redirect()
            ->route('questions.my')
            ->with('success', 'Your question was deleted successfully.');
    }
}