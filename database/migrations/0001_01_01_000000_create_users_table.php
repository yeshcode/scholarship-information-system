<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('user_id')->unique();          // Student number / staff ID
            $table->string('bisu_email')->unique();       // Unique email
            $table->string('firstname');                  // Required
            $table->string('lastname');                   // Required
            $table->string('status')->default('active');  // With default
            $table->string('contact_no');                 // Required
            $table->string('student_id')->nullable();     // For students
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');                   // Required
            $table->unsignedBigInteger('user_type_id');  // Foreign key (required)
            $table->rememberToken();
            $table->timestamps();

            // Fixed: Reference 'user_type_id' (was 'user_type')
            $table->foreign('user_type_id')->references('id')->on('user_types')->onDelete('cascade');
        });

        // Other tables unchanged
        Schema::create('password_reset_tokens', function (Blueprint $table) {
            $table->string('email')->primary();
            $table->string('token');
            $table->timestamp('created_at')->nullable();
        });

        Schema::create('sessions', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->foreignId('user_id')->nullable()->index();
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->longText('payload');
            $table->integer('last_activity')->index();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sessions');
        Schema::dropIfExists('password_reset_tokens');
        Schema::dropIfExists('users');
    }
};