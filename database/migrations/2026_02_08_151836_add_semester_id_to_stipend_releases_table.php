<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('stipend_releases', function (Blueprint $table) {
            $table->foreignId('semester_id')
                ->nullable()
                ->constrained('semesters')
                ->nullOnDelete()
                ->after('batch_id');
        });
    }

    public function down(): void
    {
        Schema::table('stipend_releases', function (Blueprint $table) {
            $table->dropConstrainedForeignId('semester_id');
        });
    }
};

