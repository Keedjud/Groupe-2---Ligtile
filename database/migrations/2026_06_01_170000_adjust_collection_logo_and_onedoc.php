<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('collections', function (Blueprint $table) {
            $table->dropColumn(['logo_url', 'onedoc_url']);
        });
        Schema::table('collections', function (Blueprint $table) {
            $table->longText('logo_url')->nullable();
            $table->string('onedoc_url')->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('collections', function (Blueprint $table) {
            $table->dropColumn(['logo_url', 'onedoc_url']);
        });
        Schema::table('collections', function (Blueprint $table) {
            $table->string('logo_url')->nullable();
            $table->string('onedoc_url');
        });
    }
};
