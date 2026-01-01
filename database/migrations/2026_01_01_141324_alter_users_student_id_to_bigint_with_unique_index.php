<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
{
    // Cast student_id to bigint (assumes data is clean/integer)
    DB::statement('ALTER TABLE users ALTER COLUMN student_id TYPE BIGINT USING student_id::BIGINT');
    
    // Ensure it's nullable
    Schema::table('users', function (Blueprint $table) {
        $table->bigInteger('student_id')->nullable()->change();
    });

    // Add partial unique index on student_id where it's not null
    DB::statement('CREATE UNIQUE INDEX users_student_id_unique ON users (student_id) WHERE student_id IS NOT NULL');
}

    public function down(): void
    {
        // Drop the index
        DB::statement('DROP INDEX IF EXISTS users_student_id_unique');

        // Revert student_id to string
        Schema::table('users', function (Blueprint $table) {
            $table->string('student_id')->nullable()->change();
        });
    }
};