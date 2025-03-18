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
        Schema::create('letras', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->unsignedBigInteger('n_linea');
            $table->text('texto')->nullable();
            $table->index('n_linea');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('letras');
    }
};
