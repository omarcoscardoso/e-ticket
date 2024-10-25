<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Inscrito extends Model
{
    use HasFactory;
    
    protected $table = 'inscritos';

    protected $primaryKey = 'id';

    protected $fillable = [
        'nome',
        'evento_id',
        'ingresso_id',
        'data_nascimento',
        'sexo',
        'endereco',
        'celular',
        'batizado',
        'igreja_id',
        'tipo_pagamento',
        'situacao_pagamento',
        'observacao',
    ];

    public function evento()
    {
        return $this->BelongsTo(Evento::class);
    }

    public function igreja()
    {
        return $this->BelongsTo(Igreja::class);
    }
    public function ingresso()
    {
        return $this->BelongsTo(Ingresso::class);
    }
    
}
