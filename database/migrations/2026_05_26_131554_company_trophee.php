<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
{
    Schema::create('company_trophee', function (Blueprint $table) {
        $table->id();
        $table->foreignId('company_id')->constrained()->restrictOnDelete();
        $table->foreignId('trophee_id')->constrained()->restrictOnDelete();
        $table->unique(['company_id', 'trophee_id']);
        $table->integer('rank');
        $table->timestamps();
    });
}


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('company_trophee');
    }
};
