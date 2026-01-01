<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('notifications', function (Blueprint $table) {
            // Safely drop FKs if they exist
            DB::statement('ALTER TABLE notifications DROP CONSTRAINT IF EXISTS notifications_created_by_foreign;');
            DB::statement('ALTER TABLE notifications DROP CONSTRAINT IF EXISTS notifications_recipient_user_id_foreign;');
            
            // Change to bigint
            $table->bigInteger('recipient_user_id')->change();
            $table->bigInteger('created_by')->change();
            
            // Recreate FKs
            $table->foreign('recipient_user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('created_by')->references('id')->on('users')->onDelete('cascade');
        });

        // Add this: Update the check constraint for related_type
        DB::statement('ALTER TABLE notifications DROP CONSTRAINT IF EXISTS notifications_related_type_check;');
        DB::statement("ALTER TABLE notifications ADD CONSTRAINT notifications_related_type_check CHECK (related_type IN ('announcement', 'stipend', 'scholarship'));");
    }

    public function down(): void
    {
        // Optional: Revert the check constraint (add this if you want to restore the original constraint in down())
        DB::statement('ALTER TABLE notifications DROP CONSTRAINT IF EXISTS notifications_related_type_check;');
        DB::statement("ALTER TABLE notifications ADD CONSTRAINT notifications_related_type_check CHECK (related_type IN ('announcement', 'stipend'));");  // Adjust to your original allowed values

        Schema::table('notifications', function (Blueprint $table) {
            $table->dropForeign(['recipient_user_id']);
            $table->dropForeign(['created_by']);
            $table->string('recipient_user_id')->change();  // Revert to string
            $table->string('created_by')->change();
            $table->foreign('recipient_user_id')->references('user_id')->on('users')->onDelete('cascade');
            $table->foreign('created_by')->references('user_id')->on('users')->onDelete('cascade');
        });
    }
};