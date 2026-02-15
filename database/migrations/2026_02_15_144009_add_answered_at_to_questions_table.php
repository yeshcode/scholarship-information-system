<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('questions', function (Blueprint $table) {
            if (!Schema::hasColumn('questions', 'answered_at')) {
                $table->timestamp('answered_at')->nullable()->after('answer');
            }

            if (!Schema::hasColumn('questions', 'answered_by')) {
                $table->unsignedBigInteger('answered_by')->nullable()->after('answered_at');
            }
        });
    }

    public function down(): void
    {
        Schema::table('questions', function (Blueprint $table) {
            if (Schema::hasColumn('questions', 'answered_by')) {
                $table->dropColumn('answered_by');
            }
            if (Schema::hasColumn('questions', 'answered_at')) {
                $table->dropColumn('answered_at');
            }
        });
    }
};

