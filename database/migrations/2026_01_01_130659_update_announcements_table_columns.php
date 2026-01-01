<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('announcements', function (Blueprint $table) {
            // Rename column to fix typo (if it exists; skip if already correct)
            if (Schema::hasColumn('announcements', 'desriptions')) {
                $table->renameColumn('desriptions', 'description');
            }
            // Add or update columns as needed (e.g., ensure posted_at is a timestamp)
            if (!Schema::hasColumn('announcements', 'posted_at')) {
                $table->timestamp('posted_at')->nullable()->after('description');
            }
            // Drop unnecessary columns if they exist (e.g., 'when_released', 'when_received' from your original)
            if (Schema::hasColumn('announcements', 'when_released')) {
                $table->dropColumn('when_released');
            }
            if (Schema::hasColumn('announcements', 'when_received')) {
                $table->dropColumn('when_received');
            }
        });
    }

    public function down(): void
    {
        Schema::table('announcements', function (Blueprint $table) {
            // Reverse the changes
            if (!Schema::hasColumn('announcements', 'desriptions')) {
                $table->renameColumn('description', 'desriptions');
            }
            if (Schema::hasColumn('announcements', 'posted_at')) {
                $table->dropColumn('posted_at');
            }
            // Re-add dropped columns if needed (but keep simple)
        });
    }
};