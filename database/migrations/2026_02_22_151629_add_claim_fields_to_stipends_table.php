<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('stipends', function (Blueprint $table) {
            $table->timestamp('claimed_at')->nullable()->after('received_at');
            $table->unsignedBigInteger('claimed_by')->nullable()->after('claimed_at');
            // optional FK if you want:
            // $table->foreign('claimed_by')->references('id')->on('users')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('stipends', function (Blueprint $table) {
            // optional FK drop first if you added it:
            // $table->dropForeign(['claimed_by']);
            $table->dropColumn(['claimed_at', 'claimed_by']);
        });
    }
};