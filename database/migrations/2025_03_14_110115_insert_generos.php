<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        DB::statement("INSERT INTO generos (nombre) VALUES
            ('Pop'),
            ('Rock'),
            ('Jazz'),
            ('Hip Hop'),
            ('Electrónica'),
            ('Reggae'),
            ('Blues'),
            ('Country'),
            ('R&B'),
            ('Folk'),
            ('Clásica'),
            ('Metal'),
            ('Punk'),
            ('Salsa'),
            ('Bachata'),
            ('Reggaeton'),
            ('Indie'),
            ('Ambient'),
            ('Gospel'),
            ('Funk');
            ");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
