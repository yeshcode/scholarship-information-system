<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('announcements', function (Blueprint $table) {
            // Safely drop FK if it exists
            DB::statement('ALTER TABLE announcements DROP CONSTRAINT IF EXISTS announcements_created_by_foreign;');
            
            // Change to bigint
            $table->bigInteger('created_by')->change();
            
            // Recreate FK
            $table->foreign('created_by')->references('id')->on('users')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::table('announcements', function (Blueprint $table) {
            $table->dropForeign(['created_by']);
            $table->string('created_by')->change();  // Revert to string
            $table->foreign('created_by')->references('user_id')->on('users')->onDelete('cascade');
        });
    }
};