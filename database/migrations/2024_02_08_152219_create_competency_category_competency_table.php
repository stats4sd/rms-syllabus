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
        Schema::create('competency_category_competency', function (Blueprint $table) {
            $table->id();
            $table->foreignId('competency_category_id')->constrained('competency_categories');
            $table->foreignId('competency_id')->constrained('competencies');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('competency_category_competency');
    }
};
