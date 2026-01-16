<?php
// database/migrations/2026_01_16_000002_create_questions_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('questions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); // students
            $table->foreignId('cluster_id')->nullable()->constrained('question_clusters')->nullOnDelete();
            $table->text('question_text');
            $table->string('status')->default('unanswered'); // unanswered / answered
            $table->text('answer')->nullable(); // optional – copy of cluster_answer
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('questions');
    }
};

