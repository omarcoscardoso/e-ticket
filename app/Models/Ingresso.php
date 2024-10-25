<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Ingresso extends Model
{
    protected $table = 'ingressos';

    protected $primaryKey = 'id';

    protected $fillable = [
        'nome',
        'evento_id',
        'custo',
        'ativo',
    ];

    public function evento()
    {
        return $this->BelongsTo(Evento::class);
    }
    public function ingresso()
    {
        return $this->BelongsTo(Ingresso::class);
    }
}
