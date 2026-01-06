<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Create the custom enum type for PostgreSQL
        DB::statement("CREATE TYPE enrollment_status AS ENUM ('enrolled', 'graduated');");

        // Alter the column to use the enum type, with casting for existing data
        DB::statement("ALTER TABLE enrollments ALTER COLUMN status TYPE enrollment_status USING status::enrollment_status;");

        // Set the default value
        DB::statement("ALTER TABLE enrollments ALTER COLUMN status SET DEFAULT 'enrolled';");
    }

    public function down(): void
    {
        // Revert the column back to VARCHAR
        DB::statement("ALTER TABLE enrollments ALTER COLUMN status TYPE VARCHAR(255);");

        // Drop the custom enum type
        DB::statement("DROP TYPE IF EXISTS enrollment_status;");
    }
};