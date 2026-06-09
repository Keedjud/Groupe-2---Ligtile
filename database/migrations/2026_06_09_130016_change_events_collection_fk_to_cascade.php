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
        Schema::table('quiz_events', function (Blueprint $table) {
            $table->dropForeign(['collection_id']);
            $table->foreign('collection_id')->references('id')->on('collections')->cascadeOnDelete();
        });

        Schema::table('page_events', function (Blueprint $table) {
            $table->dropForeign(['collection_id']);
            $table->foreign('collection_id')->references('id')->on('collections')->cascadeOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('quiz_events', function (Blueprint $table) {
            $table->dropForeign(['collection_id']);
            $table->foreign('collection_id')->references('id')->on('collections')->restrictOnDelete();
        });

        Schema::table('page_events', function (Blueprint $table) {
            $table->dropForeign(['collection_id']);
            $table->foreign('collection_id')->references('id')->on('collections')->restrictOnDelete();
        });
    }
};
