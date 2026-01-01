<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;  // Ensure this is imported for DB::statement

return new class extends Migration
{
    public function up(): void
    {
        // Drop the existing check constraint (if it exists)
        DB::statement('ALTER TABLE notifications DROP CONSTRAINT IF EXISTS notifications_related_type_check;');
        
        // Add the updated check constraint allowing 'announcement', 'stipend', 'scholarship'
        DB::statement("ALTER TABLE notifications ADD CONSTRAINT notifications_related_type_check CHECK (related_type IN ('announcement', 'stipend', 'scholarship'));");
    }

    public function down(): void
    {
        // Revert: Drop the new constraint and restore the original (adjust values if your original was different)
        DB::statement('ALTER TABLE notifications DROP CONSTRAINT IF EXISTS notifications_related_type_check;');
        DB::statement("ALTER TABLE notifications ADD CONSTRAINT notifications_related_type_check CHECK (related_type IN ('announcement', 'stipend'));");  // Example: revert to original allowed values
    }
};