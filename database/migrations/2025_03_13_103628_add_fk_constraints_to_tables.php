<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use app\Models\User;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('canciones', function (Blueprint $table) {
            $table->unsignedBigInteger('user_id');
            $table->foreign('user_id')->references("id")->on('users')->onDelete('cascade');
            $table->unsignedBigInteger('artista_id');
            $table->foreign('artista_id')->references("id")->on('artistas');
            $table->unsignedBigInteger('genero_id');
            $table->foreign('genero_id')->references("id")->on('generos');
            $table->unsignedBigInteger('tonalidade_id');
            $table->foreign('tonalidade_id')->references("id")->on('tonalidades');
            $table->foreignId('cancion_original_id')->nullable()->constrained('canciones')->onDelete('set null'); // Relación con la canción original
        });
        Schema::table('acordes', function (Blueprint $table) {
            $table->unsignedBigInteger('tonalidade_id');
            $table->foreign('tonalidade_id')->references("id")->on('tonalidades');
        });
        Schema::table('lineas', function (Blueprint $table) {
            $table->unsignedBigInteger('acorde_id');
            $table->foreign('acorde_id')->references("id")->on('acordes');
            $table->unsignedBigInteger('cancione_id');
            $table->foreign('cancione_id')->references("id")->on('canciones')->onDelete('cascade');
        });
        Schema::table('letras', function (Blueprint $table) {
            $table->unsignedBigInteger('cancione_id');
            $table->foreign('cancione_id')->references("id")->on('canciones')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tables', function (Blueprint $table) {
            //
        });
    }
};
