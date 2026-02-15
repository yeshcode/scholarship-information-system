<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('questions', function (Blueprint $table) {
            $table->text('question_text_norm')->nullable()->after('question_text');
        });

        Schema::table('question_clusters', function (Blueprint $table) {
            $table->text('representative_question_norm')->nullable()->after('representative_question');
        });
    }

    public function down(): void
    {
        Schema::table('questions', function (Blueprint $table) {
            $table->dropColumn('question_text_norm');
        });

        Schema::table('question_clusters', function (Blueprint $table) {
            $table->dropColumn('representative_question_norm');
        });
    }
};
