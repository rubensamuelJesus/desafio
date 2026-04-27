<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Cria a tabela de developers na base de dados.
     * UUID como chave primária conforme especificado no desafio.
     */
    public function up(): void
    {
        Schema::create('developers', function (Blueprint $table) {
            // UUID como chave primária
            $table->uuid('id')->primary();

            // Nickname único e obrigatório, máximo 32 caracteres
            $table->string('nickname', 32)->unique();

            // Nome obrigatório, máximo 100 caracteres
            $table->string('name', 100);

            // Data de nascimento como string no formato YYYY-MM-DD
            $table->string('birth_date', 10);

            // Stack guardada como JSON, pode ser nula
            $table->json('stack')->nullable();

            // Coluna de texto gerada para facilitar a busca full-text
            $table->string('search_text')->nullable();

            $table->timestamps();
        });

        // Índice na coluna search_text para acelerar as buscas
        DB::statement('CREATE INDEX idx_developers_search ON developers (search_text)');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('developers');
    }
};
