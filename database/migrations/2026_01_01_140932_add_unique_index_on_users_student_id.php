<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Add a partial unique index on student_id where it's not null (for students only)
        DB::statement('CREATE UNIQUE INDEX IF NOT EXISTS users_student_id_unique ON users (student_id) WHERE student_id IS NOT NULL;');
    }

    public function down(): void
    {
        // Drop the index
        DB::statement('DROP INDEX IF EXISTS users_student_id_unique');
    }
};