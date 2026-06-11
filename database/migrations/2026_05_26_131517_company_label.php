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
        Schema::create('company_label', function (Blueprint $table) {
            $table->id();
            $table->foreignId('label_id')->constrained()->restrictOnDelete();
            $table->foreignId('company_id')->constrained()->restrictOnDelete();
            // Pas de contrainte d'unicité (label_id, company_id) : une entreprise
            // peut avoir plusieurs périodes de labellisation successives dans le temps
            // (historique reconstruit par ManageCollectionController::synchroniserLabel).
            $table->datetime('start_date'); // date de fin de la première collecte de la période
            $table->datetime('end_date');   // 2 ans après la dernière collecte de la période
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('company_label');
    }
};
