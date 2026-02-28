<?php

// database/migrations/xxxx_add_date_removed_to_scholars_table.php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('scholars', function (Blueprint $table) {
            if (!Schema::hasColumn('scholars', 'date_removed')) {
                $table->date('date_removed')->nullable()->after('date_added');
            }
            // optional: if status exists but no default
            // $table->string('status')->default('active')->change();
        });
    }

    public function down(): void
    {
        Schema::table('scholars', function (Blueprint $table) {
            if (Schema::hasColumn('scholars', 'date_removed')) {
                $table->dropColumn('date_removed');
            }
        });
    }
};
