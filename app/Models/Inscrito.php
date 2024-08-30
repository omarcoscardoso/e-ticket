<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Inscrito extends Model
{
    use HasFactory;
    
    protected $table = 'inscritos';

    protected $primaryKey = 'id_inscrito';

    protected $fillable = [
        'nome',
        'data_nascimento',
        'sexo',
        'endereco',
        'celular',
        'batizado',
        'igreja',
        'tipo_pagamento',
        'situacao_pagamento',
        'observacao',
    ];
}
