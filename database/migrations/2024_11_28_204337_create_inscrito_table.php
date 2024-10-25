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
        Schema::create('inscritos', function (Blueprint $table) {
            $table->id('id');
            $table->foreignId('evento_id')->constrained()->cascadeOnDelete();
            $table->foreignId('ingresso_id')->constrained()->cascadeOnDelete();
            $table->string('nome');
            $table->date('data_nascimento');
            $table->enum('sexo', ['masculino', 'feminino']);
            $table->string('endereco')->nullable();
            $table->string('celular');
            $table->boolean('batizado');
            $table->foreignId('igreja_id')->constrained()->cascadeOnDelete();
            $table->text('observacao')->nullable();
            $table->enum('tipo_pagamento', ['pix', 'cartao_credito']);
            $table->enum('situacao_pagamento', ['pago', 'aberto'])->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('inscritos');
    }
};
