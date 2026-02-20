<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('stipend_release_forms', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('stipend_release_id');
            $table->string('original_name');
            $table->string('path');
            $table->string('mime', 80)->nullable();
            $table->unsignedBigInteger('uploaded_by')->nullable();
            $table->timestamps();

            $table->foreign('stipend_release_id')->references('id')->on('stipend_releases')->onDelete('cascade');
            $table->foreign('uploaded_by')->references('id')->on('users')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('stipend_release_forms');
    }
};