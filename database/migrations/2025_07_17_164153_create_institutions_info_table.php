<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('institutions_info', function (Blueprint $table) {
            $table->id();
            $table->longText('institution_about')->nullable();
            $table->longText('institution_history')->nullable();
            $table->longText('institution_head_teacher_advice')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('institutions_info');
    }
};
