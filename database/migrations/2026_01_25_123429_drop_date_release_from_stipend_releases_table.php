<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('stipend_releases', function (Blueprint $table) {
            if (Schema::hasColumn('stipend_releases', 'date_release')) {
                $table->dropColumn('date_release');
            }
        });
    }

    public function down(): void
    {
        Schema::table('stipend_releases', function (Blueprint $table) {
            $table->date('date_release')->nullable(); // rollback
        });
    }
};
