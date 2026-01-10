<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    public function up(): void {
        // Step 1: Drop the existing check constraint FIRST (so we can update the data)
        DB::statement('ALTER TABLE notifications DROP CONSTRAINT IF EXISTS notifications_related_type_check;');
        
        // Step 2: Now update existing rows to match the new class name format
        DB::statement("UPDATE notifications SET related_type = 'App\\\\Models\\\\Announcement' WHERE related_type = 'announcement';");
        DB::statement("UPDATE notifications SET related_type = 'App\\\\Models\\\\Stipend' WHERE related_type = 'stipend';");
        DB::statement("UPDATE notifications SET related_type = 'App\\\\Models\\\\Scholarship' WHERE related_type = 'scholarship';");
        DB::statement("UPDATE notifications SET related_type = 'App\\\\Models\\\\StipendsRelease' WHERE related_type = 'stipend_release';");
        // If you have other values in related_type, add more UPDATE lines here (check your database if needed)
        
        // Step 3: Add the updated check constraint allowing full class names
        DB::statement("ALTER TABLE notifications ADD CONSTRAINT notifications_related_type_check CHECK (related_type IN ('App\\\\Models\\\\Announcement', 'App\\\\Models\\\\Stipend', 'App\\\\Models\\\\Scholarship', 'App\\\\Models\\\\StipendsRelease'));");
    }

    public function down(): void {
        // Step 1: Drop the new constraint
        DB::statement('ALTER TABLE notifications DROP CONSTRAINT IF EXISTS notifications_related_type_check;');
        
        // Step 2: Revert the updates (change class names back to simple strings)
        DB::statement("UPDATE notifications SET related_type = 'announcement' WHERE related_type = 'App\\\\Models\\\\Announcement';");
        DB::statement("UPDATE notifications SET related_type = 'stipend' WHERE related_type = 'App\\\\Models\\\\Stipend';");
        DB::statement("UPDATE notifications SET related_type = 'scholarship' WHERE related_type = 'App\\\\Models\\\\Scholarship';");
        DB::statement("UPDATE notifications SET related_type = 'stipend_release' WHERE related_type = 'App\\\\Models\\\\StipendsRelease';");
        
        // Step 3: Restore the simple string constraint
        DB::statement("ALTER TABLE notifications ADD CONSTRAINT notifications_related_type_check CHECK (related_type IN ('announcement', 'stipend', 'scholarship', 'stipend_release'));");
    }
};