<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Safety check
        if (!Schema::hasTable('sections')) {
            return;
        }

        /**
         * USERS: copy course_id from sections
         * PostgreSQL syntax uses UPDATE ... FROM
         */
        DB::statement("
            UPDATE users
            SET course_id = sections.course_id
            FROM sections
            WHERE sections.id = users.section_id
              AND users.course_id IS NULL
              AND users.section_id IS NOT NULL
        ");

        /**
         * ENROLLMENTS: copy course_id from sections
         */
        DB::statement("
            UPDATE enrollments
            SET course_id = sections.course_id
            FROM sections
            WHERE sections.id = enrollments.section_id
              AND enrollments.course_id IS NULL
              AND enrollments.section_id IS NOT NULL
        ");
    }

    public function down(): void
    {
        // No rollback needed
    }
};
