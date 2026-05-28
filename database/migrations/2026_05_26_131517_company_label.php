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
        $table->datetime('start_date');//doit correspondre à la date de collection
        $table->datetime('end_date'); //2 ans après
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
