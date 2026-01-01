<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
{
    Schema::table('scholars', function (Blueprint $table) {
        // Drop existing FKs
        $table->dropForeign(['student_id']);
        $table->dropForeign(['updated_by']);

        // Change to bigint to match users.id
        $table->bigInteger('student_id')->change();
        $table->bigInteger('updated_by')->change();  // If updating this too

        // Recreate FKs: Both reference users.id
        $table->foreign('student_id')->references('id')->on('users')->onDelete('cascade');
        $table->foreign('updated_by')->references('id')->on('users')->onDelete('cascade');
    });
}
    public function down(): void
    {
        Schema::table('scholars', function (Blueprint $table) {
            $table->dropForeign(['student_id']);
            $table->dropForeign(['updated_by']);
            $table->bigInteger('student_id')->change();  // Keep as bigint
            $table->string('updated_by')->change();  // Revert to string
            $table->foreign('student_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('updated_by')->references('id')->on('users')->onDelete('cascade');
        });
    }
};