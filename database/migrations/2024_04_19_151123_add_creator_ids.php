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
        Schema::table('pathways', function (Blueprint $table) {
            $table->foreignId('creator_id')->after('slug')->constrained('users')->nullable();
        });

        Schema::table('modules', function (Blueprint $table) {
            $table->foreignId('creator_id')->after('slug')->constrained('users')->nullable();
        });

        Schema::table('sections', function (Blueprint $table) {
            $table->foreignId('creator_id')->after('module_id')->constrained('users')->nullable();
        });

        Schema::table('activities', function (Blueprint $table) {
            $table->foreignId('creator_id')->after('trove_id')->constrained('users')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // 
    }
};
