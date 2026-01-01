<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
{
    Schema::table('stipends', function (Blueprint $table) {
        // Safely drop FKs
        DB::statement('ALTER TABLE stipends DROP CONSTRAINT IF EXISTS stipends_created_by_foreign;');
        DB::statement('ALTER TABLE stipends DROP CONSTRAINT IF EXISTS stipends_updated_by_foreign;');
        DB::statement('ALTER TABLE stipends DROP CONSTRAINT IF EXISTS stipends_student_id_foreign;');
        
        // Change to bigint
        $table->bigInteger('student_id')->change();
        $table->bigInteger('created_by')->change();
        $table->bigInteger('updated_by')->change();
        
        // Recreate FKs
        $table->foreign('student_id')->references('id')->on('users')->onDelete('cascade');
        $table->foreign('created_by')->references('id')->on('users')->onDelete('cascade');
        $table->foreign('updated_by')->references('id')->on('users')->onDelete('cascade');
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
{
    Schema::table('stipends', function (Blueprint $table) {
        $table->dropForeign(['student_id']);
        $table->dropForeign(['created_by']);
        $table->dropForeign(['updated_by']);
        $table->string('student_id')->change();
        $table->string('created_by')->change();
        $table->string('updated_by')->change();
        $table->foreign('student_id')->references('user_id')->on('users')->onDelete('cascade');
        $table->foreign('created_by')->references('user_id')->on('users')->onDelete('cascade');
        $table->foreign('updated_by')->references('user_id')->on('users')->onDelete('cascade');
    });
}
};
