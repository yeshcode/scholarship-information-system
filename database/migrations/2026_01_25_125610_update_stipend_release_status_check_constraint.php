<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    public function up(): void
    {
        // Drop the old check constraint
        DB::statement("ALTER TABLE stipend_releases DROP CONSTRAINT IF EXISTS stipends_release_status_check");

        // Add the new allowed statuses
        DB::statement("
            ALTER TABLE stipend_releases
            ADD CONSTRAINT stipends_release_status_check
            CHECK (status IN ('for_billing','for_check','for_release','received'))
        ");
    }

    public function down(): void
    {
        DB::statement("ALTER TABLE stipend_releases DROP CONSTRAINT IF EXISTS stipends_release_status_check");

        // revert to your old statuses (adjust if yours was different)
        DB::statement("
            ALTER TABLE stipend_releases
            ADD CONSTRAINT stipends_release_status_check
            CHECK (status IN ('pending','released'))
        ");
    }
};
