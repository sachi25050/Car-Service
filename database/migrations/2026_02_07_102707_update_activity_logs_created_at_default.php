<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // For MySQL, we need to alter the column to set default
        if (DB::getDriverName() === 'mysql') {
            DB::statement('ALTER TABLE activity_logs MODIFY created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP');
        } else {
            // For other databases, use Schema
            Schema::table('activity_logs', function (Blueprint $table) {
                $table->timestamp('created_at')->useCurrent()->change();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (DB::getDriverName() === 'mysql') {
            DB::statement('ALTER TABLE activity_logs MODIFY created_at TIMESTAMP NULL');
        } else {
            Schema::table('activity_logs', function (Blueprint $table) {
                $table->timestamp('created_at')->nullable()->change();
            });
        }
    }
};
