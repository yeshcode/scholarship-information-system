<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    public function up(): void
    {
        DB::statement("ALTER TABLE notifications DROP CONSTRAINT IF EXISTS notifications_type_check");

        DB::statement("
            ALTER TABLE notifications
            ADD CONSTRAINT notifications_type_check
            CHECK (type::text = ANY (ARRAY[
                'announcement'::character varying,
                'stipend'::character varying,
                'scholarship'::character varying,
                'stipend_claim'::character varying
            ]::text[]))
        ");
    }

    public function down(): void
    {
        DB::statement("ALTER TABLE notifications DROP CONSTRAINT IF EXISTS notifications_type_check");

        DB::statement("
            ALTER TABLE notifications
            ADD CONSTRAINT notifications_type_check
            CHECK (type::text = ANY (ARRAY[
                'announcement'::character varying,
                'stipend'::character varying,
                'scholarship'::character varying
            ]::text[]))
        ");
    }
};