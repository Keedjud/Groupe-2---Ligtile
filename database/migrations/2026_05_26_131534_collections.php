<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
{
    Schema::create('collections', function (Blueprint $table) {
        $table->id();
        $table->foreignId('company_id')->constrained()->restrictOnDelete();
        $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
        $table->foreignId('address_id')->constrained()->restrictOnDelete();
        $table->datetime('start_date');
        $table->datetime('end_date');
        $table->integer('nb_registered')->nullable();
        $table->string('primary_color');
        $table->string('secondary_color');
        $table->string('logo_url');
        $table->string('onedoc_url');
        $table->string('public_token');
        $table->timestamps();
    });
}


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('collections');
    }
};
