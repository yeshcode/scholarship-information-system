<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('stipends', function (Blueprint $table) {
            // Rename column
            $table->renameColumn('scholars_id', 'scholar_id');
            // Drop old FK and add new one (if needed; assumes you want to update the constraint)
            $table->dropForeign(['scholars_id']);  // Drop old FK
            $table->foreign('scholar_id')->references('id')->on('scholars')->onDelete('cascade');  // Add new FK
            // Update FK for stipend_release_id to match new table name
            $table->dropForeign(['stipend_release_id']);
            $table->foreign('stipend_release_id')->references('id')->on('stipend_releases')->onDelete('cascade');
        });
    }

    public function down(): void
{
    Schema::table('stipends', function (Blueprint $table) {
        // Reverse the FK for stipend_release_id to the correct table name
        $table->dropForeign(['stipend_release_id']);
        $table->foreign('stipend_release_id')->references('id')->on('stipend_releases')->onDelete('cascade');  // Changed from 'stipends_release' to 'stipend_releases'
        // Reverse the column rename
        $table->renameColumn('scholar_id', 'scholars_id');
        // Reverse the FK for scholar_id
        $table->dropForeign(['scholar_id']);
        $table->foreign('scholars_id')->references('id')->on('scholars')->onDelete('cascade');  // Note: Using 'scholars_id' here since we're reversing
    });
}
};
