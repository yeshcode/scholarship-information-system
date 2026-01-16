<?php

// database/migrations/2026_01_16_000001_create_question_clusters_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('question_clusters', function (Blueprint $table) {
            $table->id();
            $table->string('label')->nullable(); // e.g. "TDP Deadline"
            $table->text('representative_question')->nullable(); // sample question
            $table->text('cluster_answer')->nullable(); // staff answer for the whole group
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('question_clusters');
    }
};
