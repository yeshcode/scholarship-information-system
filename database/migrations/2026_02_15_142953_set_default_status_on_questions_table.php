<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    public function up(): void
    {
        // fix existing bad rows
        DB::statement("UPDATE questions SET status = 'unanswered' WHERE status IS NULL");

        // enforce default going forward
        DB::statement("ALTER TABLE questions ALTER COLUMN status SET DEFAULT 'unanswered'");
    }

    public function down(): void
    {
        DB::statement("ALTER TABLE questions ALTER COLUMN status DROP DEFAULT");
    }
};

