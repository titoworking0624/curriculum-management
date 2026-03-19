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
        // Schema::table('participant_chapters', function (Blueprint $table) {
        //     $table->foreignId('current_participant_curriculum_id')
        //         ->after('chapter_id')
        //         ->nullable()
        //         ->constrained('participant_curriculum')
        //         ->nullOnDelete();
        // });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('participant_chapters', function (Blueprint $table) {
            //
        });
    }
};
