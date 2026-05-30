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
        // SQLite ne supporte pas ->change(), on recrée la colonne
        Schema::table('collections', function (Blueprint $table) {
            $table->dropColumn('logo_url');
        });
        Schema::table('collections', function (Blueprint $table) {
            $table->string('logo_url')->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('collections', function (Blueprint $table) {
            $table->dropColumn('logo_url');
        });
        Schema::table('collections', function (Blueprint $table) {
            $table->string('logo_url');
        });
    }
};
