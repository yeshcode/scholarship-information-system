<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {

            // ✅ add middlename if missing
            if (!Schema::hasColumn('users', 'middlename')) {
                $table->string('middlename', 255)->nullable()->after('firstname');
            }

            // ✅ add suffix if missing (Jr, Sr, III...)
            if (!Schema::hasColumn('users', 'suffix')) {
                $table->string('suffix', 50)->nullable()->after('middlename');
            }
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            if (Schema::hasColumn('users', 'suffix')) {
                $table->dropColumn('suffix');
            }
            if (Schema::hasColumn('users', 'middlename')) {
                $table->dropColumn('middlename');
            }
        });
    }
};
