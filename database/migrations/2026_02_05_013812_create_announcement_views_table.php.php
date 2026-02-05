<?php

// database/migrations/xxxx_xx_xx_create_announcement_views_table.php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('announcement_views', function (Blueprint $table) {
            $table->id();
            $table->foreignId('announcement_id')->constrained('announcements')->cascadeOnDelete();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->timestamp('seen_at')->useCurrent();
            $table->timestamps();

            $table->unique(['announcement_id', 'user_id']); // 1 view per user
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('announcement_views');
    }
};

