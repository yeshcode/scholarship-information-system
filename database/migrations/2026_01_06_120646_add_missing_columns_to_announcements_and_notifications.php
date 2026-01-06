<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Add 'audience' to announcements
        Schema::table('announcements', function (Blueprint $table) {
            $table->enum('audience', ['all_students', 'specific_scholars'])->default('all_students')->after('posted_at');
        });

        // Add 'sent_at' to notifications (replace or supplement the old 'date' column if needed)
        Schema::table('notifications', function (Blueprint $table) {
            if (Schema::hasColumn('notifications', 'date')) {
                $table->dropColumn('date');  // Remove the old date column if it exists
            }
            $table->timestamp('sent_at')->nullable()->after('is_read');
        });
    }

    public function down(): void
    {
        // Reverse: Drop the new columns
        Schema::table('announcements', function (Blueprint $table) {
            $table->dropColumn('audience');
        });

        Schema::table('notifications', function (Blueprint $table) {
            $table->dropColumn('sent_at');
            // Optionally re-add 'date' if you want to revert fully
            $table->date('date')->nullable()->after('is_read');
        });
    }
};