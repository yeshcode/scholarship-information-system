<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
{
    Schema::table('user_types', function (Blueprint $table) {
        $table->string('type')->unique()->after('id');  // Add 'type' column after 'id'
        $table->string('description')->nullable()->after('type');  // Add 'description' column
    });
}

public function down(): void
{
    Schema::table('user_types', function (Blueprint $table) {
        $table->dropColumn(['type', 'description']);
    });
}
};
