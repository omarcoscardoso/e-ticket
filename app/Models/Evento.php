<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Evento extends Model
{
    use HasFactory;

    protected $table = 'eventos';

    protected $primaryKey = 'id';

    protected $fillable = [
        'nome_evento',
        'data_ini',
        'data_fim',
        'observacao',
    ];

    public function inscrito()
    {
        return $this->hasMany(Inscrito::class);
    }
    public function ingresso()
    {
        return $this->hasMany(Ingresso::class);
    }
}
