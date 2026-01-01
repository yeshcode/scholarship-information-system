<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;  // Add this for raw SQL

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('stipend_releases', function (Blueprint $table) {
            // Safely drop FKs if they exist (using raw SQL for PostgreSQL)
            DB::statement('ALTER TABLE stipend_releases DROP CONSTRAINT IF EXISTS stipend_releases_created_by_foreign;');
            DB::statement('ALTER TABLE stipend_releases DROP CONSTRAINT IF EXISTS stipend_releases_updated_by_foreign;');
            
            // Change to bigint
            $table->bigInteger('created_by')->change();
            $table->bigInteger('updated_by')->change();
            
            // Recreate FKs
            $table->foreign('created_by')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('updated_by')->references('id')->on('users')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::table('stipend_releases', function (Blueprint $table) {
            // Drop new FKs
            $table->dropForeign(['created_by']);
            $table->dropForeign(['updated_by']);
            
            // Change back to string (assuming original was string)
            $table->string('created_by')->change();
            $table->string('updated_by')->change();
            
            // Recreate original FKs (to users.user_id)
            $table->foreign('created_by')->references('user_id')->on('users')->onDelete('cascade');
            $table->foreign('updated_by')->references('user_id')->on('users')->onDelete('cascade');
        });
    }
};