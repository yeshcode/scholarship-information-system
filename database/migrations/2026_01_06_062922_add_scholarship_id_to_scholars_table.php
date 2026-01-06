<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;  // Add this import for the update query

return new class extends Migration
{
    public function up(): void
    {
        // Step 1: Add the column as nullable to avoid NOT NULL violation
        Schema::table('scholars', function (Blueprint $table) {
            $table->foreignId('scholarship_id')->nullable()->constrained('scholarships')->onDelete('cascade')->after('batch_id');
        });

        // Step 2: Populate existing scholars with scholarship_id based on their batch_id
        // This assumes scholarship_batches have a scholarship_id (as per your models)
        DB::statement("
            UPDATE scholars 
            SET scholarship_id = (SELECT scholarship_id FROM scholarship_batches WHERE scholarship_batches.id = scholars.batch_id) 
            WHERE scholarship_id IS NULL
        ");

        // Step 3: Now make the column NOT NULL (required for FK)
        Schema::table('scholars', function (Blueprint $table) {
            $table->bigInteger('scholarship_id')->nullable(false)->change();
        });
    }

    public function down(): void
    {
        Schema::table('scholars', function (Blueprint $table) {
            $table->dropForeign(['scholarship_id']);
            $table->dropColumn('scholarship_id');
        });
    }
};