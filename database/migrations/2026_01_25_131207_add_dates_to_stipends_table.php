<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('stipends', function (Blueprint $table) {
            if (!Schema::hasColumn('stipends', 'date_release')) {
                $table->date('date_release')->nullable(); // scheduled release date per scholar
            }
            if (!Schema::hasColumn('stipends', 'received_at')) {
                $table->timestamp('received_at')->nullable(); // actual received timestamp
            }
        });
    }

    public function down(): void
    {
        Schema::table('stipends', function (Blueprint $table) {
            if (Schema::hasColumn('stipends', 'date_release')) $table->dropColumn('date_release');
            if (Schema::hasColumn('stipends', 'received_at')) $table->dropColumn('received_at');
        });
    }
};
