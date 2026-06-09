<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('quiz_events', function (Blueprint $table) {
            $table->id();
            $table->foreignId('collection_id')->constrained()->restrictOnDelete();
            $table->uuid('session_id');
            $table->enum('event_type', [
                'quiz_started', 'question_answered', 'question_skipped',
                'form_skipped_from', 'p1_eliminated', 'p1_completed',
                'p2_completed', 'quiz_completed', 'onedoc_clicked',
            ]);
            $table->tinyInteger('part')->nullable();
            $table->string('question_slug')->nullable();
            $table->enum('answer_result', ['correct', 'incorrect'])->nullable();
            $table->timestamp('created_at')->useCurrent();
            $table->index(['collection_id', 'event_type', 'session_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('quiz_events');
    }
};
