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
        DB::statement("INSERT INTO users (name,email,password) VALUES
            ('admin','1@email.com','1234567'),
            ('user','2@email.com','1234567');
            ");
        DB::statement("INSERT INTO ARTISTAS (nombre) VALUES ('Fulgencio')");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
