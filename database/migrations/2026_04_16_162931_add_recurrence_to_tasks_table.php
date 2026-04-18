<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('tasks', function (Blueprint $table) {
            $table->enum('recurrence', ['none', 'daily', 'weekly', 'monthly', 'custom'])->default('none')->after('position');
            $table->unsignedInteger('recurrence_interval')->nullable()->after('recurrence'); // for custom: every X days
        });
    }

    public function down(): void
    {
        Schema::table('tasks', function (Blueprint $table) {
            $table->dropColumn(['recurrence', 'recurrence_interval']);
        });
    }
};
