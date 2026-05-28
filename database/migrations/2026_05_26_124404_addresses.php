<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
{
    Schema::create('addresses', function (Blueprint $table) {
        $table->id();
        $table->string('postal_code',10);
        $table->string('city', 50);
        $table->string('street', 50);
        $table->string('number',10);
        $table->timestamps();
    });
}


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('addresses');
    }
};
