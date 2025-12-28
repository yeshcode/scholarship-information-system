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
        Schema::create('stipends', function (Blueprint $table) {
            $table->id();
            $table->foreignId('stipend_release_id')->constrained('stipends_release')->onDelete('cascade');
            $table->foreignId('scholars_id')->constrained('scholars')->onDelete('cascade');
            $table->foreignId('student_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('created_by')->constrained('users')->onDelete('cascade');
            $table->foreignId('updated_by')->constrained('users')->onDelete('cascade');

            $table->decimal('amount_received', 10, 2);
            $table->enum('status', ['for_release','released','returned','waiting']);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('stipends');
    }
};
