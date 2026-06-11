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
        Schema::create('collections', function (Blueprint $table) {
            $table->id();
            $table->foreignId('company_id')->constrained()->restrictOnDelete();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->string('contact_email', 100);
            $table->string('contact_phone', 50);
            $table->string('venue_street', 255);
            $table->string('venue_number', 20);
            $table->string('venue_postal_code', 10);
            $table->string('venue_city', 100);
            $table->datetime('start_date');
            $table->datetime('end_date');
            $table->integer('capacity');
            $table->string('primary_color');
            $table->string('logo_url');
            $table->string('onedoc_url');
            $table->string('kit_url');
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
