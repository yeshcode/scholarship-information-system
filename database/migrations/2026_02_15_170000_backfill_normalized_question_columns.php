<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration {

    private function normalize(string $text): string
    {
        $text = mb_strtolower(trim($text));
        $text = preg_replace('/[^\p{L}\p{N}\s]+/u', ' ', $text);
        $text = preg_replace('/\s+/', ' ', $text);

        $stop = [
            'what','whats','is','are','the','a','an','of','for','to','please',
            'unsa','unsay','sa','para','palihug',
            'ano','ang','mga','po'
        ];

        $words = array_values(array_filter(
            explode(' ', $text),
            fn ($w) => $w !== '' && !in_array($w, $stop, true)
        ));

        sort($words);

        return implode(' ', $words);
    }

    public function up(): void
    {
        $questions = DB::table('questions')->select('id','question_text')->get();

        foreach ($questions as $q) {
            DB::table('questions')
                ->where('id', $q->id)
                ->update([
                    'question_text_norm' => $this->normalize($q->question_text),
                ]);
        }

        $clusters = DB::table('question_clusters')->select('id','representative_question')->get();

        foreach ($clusters as $c) {
            DB::table('question_clusters')
                ->where('id', $c->id)
                ->update([
                    'representative_question_norm' => $this->normalize($c->representative_question ?? ''),
                ]);
        }
    }

    public function down(): void
    {
        DB::table('questions')->update(['question_text_norm' => null]);
        DB::table('question_clusters')->update(['representative_question_norm' => null]);
    }
};
