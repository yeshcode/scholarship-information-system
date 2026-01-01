<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::rename('stipends_release', 'stipend_releases');  // Rename table
    }

    public function down(): void
    {
        Schema::rename('stipend_releases', 'stipends_release');  // Reverse if rolling back
    }
};