<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    public function up(): void
    {
        // Drop old constraint (name from your error)
        DB::statement("ALTER TABLE announcements DROP CONSTRAINT IF EXISTS announcements_audience_check");

        // Add correct constraint
        DB::statement("
            ALTER TABLE announcements
            ADD CONSTRAINT announcements_audience_check
            CHECK (audience IN ('all_students','all_scholars','specific_students','specific_scholars'))
        ");
    }

    public function down(): void
    {
        DB::statement("ALTER TABLE announcements DROP CONSTRAINT IF EXISTS announcements_audience_check");

        // (Optional) If you know the old allowed values, put them back here.
        DB::statement("
            ALTER TABLE announcements
            ADD CONSTRAINT announcements_audience_check
            CHECK (audience IN ('all_students','all_scholars','specific_students','specific_scholars'))
        ");
    }
};
