<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;  // Ensure this is imported for DB::statement

return new class extends Migration
{
    public function up(): void
    {
        // Drop the existing check constraint for type (if it exists)
        DB::statement('ALTER TABLE notifications DROP CONSTRAINT IF EXISTS notifications_type_check;');
        
        // Add the updated check constraint allowing 'announcement', 'stipend', 'scholarship'
        DB::statement("ALTER TABLE notifications ADD CONSTRAINT notifications_type_check CHECK (type IN ('announcement', 'stipend', 'scholarship'));");
    }

    public function down(): void
    {
        // Revert: Drop the new constraint and restore the original (adjust values to match your original constraint)
        DB::statement('ALTER TABLE notifications DROP CONSTRAINT IF EXISTS notifications_type_check;');
        DB::statement("ALTER TABLE notifications ADD CONSTRAINT notifications_type_check CHECK (type IN ('info', 'alert'));");  // Example: adjust to your original allowed values (e.g., 'info', 'alert' or whatever it was)
    }
};