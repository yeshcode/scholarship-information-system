<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
{
    Schema::table('questions', function (Blueprint $table) {
        if (!Schema::hasColumn('questions', 'answer')) {
            $table->text('answer')->nullable();
        }
        if (!Schema::hasColumn('questions', 'answered_at')) {
            $table->timestamp('answered_at')->nullable();
        }
        if (!Schema::hasColumn('questions', 'answered_by')) {
            $table->unsignedBigInteger('answered_by')->nullable();
        }
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('questions', function (Blueprint $table) {
            //
        });
    }
};
