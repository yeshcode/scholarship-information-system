<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        /**
         * 1) Fix columns + add sent_at
         */
        Schema::table('notifications', function (Blueprint $table) {

            // Rename typo columns if they still exist
            if (Schema::hasColumn('notifications', 'recipent_user_id') && !Schema::hasColumn('notifications', 'recipient_user_id')) {
                $table->renameColumn('recipent_user_id', 'recipient_user_id');
            }

            if (Schema::hasColumn('notifications', 'messages') && !Schema::hasColumn('notifications', 'message')) {
                $table->renameColumn('messages', 'message');
            }

            // Add sent_at if missing
            if (!Schema::hasColumn('notifications', 'sent_at')) {
                $table->timestamp('sent_at')->nullable();
            }

            // Ensure is_read default false (if column exists)
            if (Schema::hasColumn('notifications', 'is_read')) {
                $table->boolean('is_read')->default(false)->change();
            }
        });

        /**
         * 2) Drop old CHECK constraints (safe)
         */
        DB::statement('ALTER TABLE notifications DROP CONSTRAINT IF EXISTS notifications_type_check;');
        DB::statement('ALTER TABLE notifications DROP CONSTRAINT IF EXISTS notifications_related_type_check;');

        /**
         * 3) ✅ DATA CLEANUP (IMPORTANT)
         * Convert old morph/class related_type values to new allowed labels
         *
         * From your screenshot: related_type = 'App\Models\Announcement' (1735 rows)
         * We convert it to 'announcement'
         */
        DB::statement("
            UPDATE notifications
            SET related_type = 'announcement'
            WHERE related_type = 'App\\\\Models\\\\Announcement'
        ");

        // Optional extra safety: if some rows still use other variants
        DB::statement("
            UPDATE notifications
            SET related_type = 'announcement'
            WHERE related_type IN ('Announcement', 'announcement')
        ");

        // Optional fallback: force any remaining weird values into 'announcement'
        DB::statement("
            UPDATE notifications
            SET related_type = 'announcement'
            WHERE related_type IS NOT NULL
              AND related_type NOT IN ('announcement', 'stipend', 'scholarship')
        ");

        /**
         * 4) Add new CHECK constraints (allowed values)
         */
        DB::statement("
            ALTER TABLE notifications
            ADD CONSTRAINT notifications_type_check
            CHECK (type IN ('announcement', 'stipend', 'scholarship'))
        ");

        DB::statement("
            ALTER TABLE notifications
            ADD CONSTRAINT notifications_related_type_check
            CHECK (related_type IN ('announcement', 'stipend', 'scholarship'))
        ");
    }

    public function down(): void
    {
        // Drop new checks
        DB::statement('ALTER TABLE notifications DROP CONSTRAINT IF EXISTS notifications_type_check;');
        DB::statement('ALTER TABLE notifications DROP CONSTRAINT IF EXISTS notifications_related_type_check;');

        // (Optional) revert to old checks if you want
        DB::statement("
            ALTER TABLE notifications
            ADD CONSTRAINT notifications_type_check
            CHECK (type IN ('stipend_release', 'announcement'))
        ");

        DB::statement("
            ALTER TABLE notifications
            ADD CONSTRAINT notifications_related_type_check
            CHECK (related_type IN ('stipend_release', 'announcement'))
        ");

        // Optional: remove sent_at
        Schema::table('notifications', function (Blueprint $table) {
            if (Schema::hasColumn('notifications', 'sent_at')) {
                $table->dropColumn('sent_at');
            }
        });
    }
};
