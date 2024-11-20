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
        Schema::table('inscritos', function (Blueprint $table) {
            $table->enum('tipo_pagamento', ['pix', 'cartao_credito', 'isento', 'local'])->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('inscritos', function (Blueprint $table) {
            $table->enum('tipo_pagamento', ['pix', 'cartao_credito', 'isento'])->change();
        });
    }
};
