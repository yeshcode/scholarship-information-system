<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('question_clusters', function (Blueprint $table) {
            $table->timestamp('cluster_answered_at')->nullable()->after('cluster_answer');
            $table->unsignedBigInteger('cluster_answered_by')->nullable()->after('cluster_answered_at');
        });
    }

    public function down(): void
    {
        Schema::table('question_clusters', function (Blueprint $table) {
            $table->dropColumn(['cluster_answered_at', 'cluster_answered_by']);
        });
    }
};
