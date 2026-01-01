<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('notifications', function (Blueprint $table) {
            // Rename columns to fix typos (if they exist; skip if already correct)
            if (Schema::hasColumn('notifications', 'recipent_user_id')) {
                $table->renameColumn('recipent_user_id', 'recipient_user_id');
            }
            if (Schema::hasColumn('notifications', 'messages')) {
                $table->renameColumn('messages', 'message');
            }
            // Ensure FKs are correct (drop and re-add if needed for consistency)
            $table->dropForeign(['recipent_user_id']);  // Drop old FK if it exists
            $table->foreign('recipient_user_id')->references('id')->on('users')->onDelete('cascade');
            // Note: Other FKs (e.g., created_by) should already be correct from the original migration
        });
    }

    public function down(): void
    {
        Schema::table('notifications', function (Blueprint $table) {
            // Reverse the changes
            if (!Schema::hasColumn('notifications', 'recipent_user_id')) {
                $table->renameColumn('recipient_user_id', 'recipent_user_id');
            }
            if (!Schema::hasColumn('notifications', 'messages')) {
                $table->renameColumn('message', 'messages');
            }
            $table->dropForeign(['recipient_user_id']);
            $table->foreign('recipent_user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }
};