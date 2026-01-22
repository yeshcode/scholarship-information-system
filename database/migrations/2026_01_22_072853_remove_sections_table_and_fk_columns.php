<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Remove users.section_id FK + column
        if (Schema::hasColumn('users', 'section_id')) {
            Schema::table('users', function (Blueprint $table) {
                // Based on your users migration:
                // $table->foreign('section_id')->references('id')->on('sections')
                // Default FK name becomes: users_section_id_foreign
                $table->dropForeign('users_section_id_foreign');
                $table->dropColumn('section_id');
            });
        }

        // Remove enrollments.section_id FK + column
        if (Schema::hasColumn('enrollments', 'section_id')) {
            Schema::table('enrollments', function (Blueprint $table) {
                // Based on your enrollments migration:
                // $table->foreignId('section_id')->constrained('sections')
                // Default FK name becomes: enrollments_section_id_foreign
                $table->dropForeign('enrollments_section_id_foreign');
                $table->dropColumn('section_id');
            });
        }

        // Drop sections table
        if (Schema::hasTable('sections')) {
            Schema::drop('sections');
        }
    }

    public function down(): void
    {
        // Optional rollback (recreate sections minimal)
        Schema::create('sections', function (Blueprint $table) {
            $table->id();
            $table->string('section_name');
            $table->unsignedBigInteger('course_id');
            $table->unsignedBigInteger('year_level_id');
            $table->timestamps();

            $table->foreign('course_id')->references('id')->on('courses')->onDelete('cascade');
            $table->foreign('year_level_id')->references('id')->on('year_levels')->onDelete('cascade');
        });

        // Restore users.section_id
        Schema::table('users', function (Blueprint $table) {
            $table->unsignedBigInteger('section_id')->nullable()->after('year_level_id');
            $table->foreign('section_id')->references('id')->on('sections')->onDelete('set null');
        });

        // Restore enrollments.section_id
        Schema::table('enrollments', function (Blueprint $table) {
            $table->unsignedBigInteger('section_id')->after('user_id');
            $table->foreign('section_id')->references('id')->on('sections')->onDelete('cascade');
        });
    }
};
