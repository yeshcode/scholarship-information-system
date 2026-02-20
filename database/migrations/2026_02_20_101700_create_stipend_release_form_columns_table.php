<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('stipend_release_form_columns', function (Blueprint $table) {
            $table->id();
            $table->string('label');      // Column header shown in print/excel
            $table->string('key');        // Used by system mapping (firstname, lastname, signature, etc.)
            $table->integer('sort_order')->default(1);
            $table->boolean('is_active')->default(true);
            $table->integer('width')->nullable(); // optional for Excel
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('stipend_release_form_columns');
    }
};