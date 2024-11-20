<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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
        'cpf',
        'batizado',
        'igreja_id',
        'tipo_pagamento',
        'tamanho_camiseta',
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
    public function pagamento()
    {
        return $this->hasOne(Pagamento::class);
    }
    protected static function booted()
    {
        // static::retrieved(function ($inscrito) {
        //     if ($inscrito->pagamento) {
        //         $inscrito->pagamento->atualizarStatus($inscrito);  // Chama o método diretamente
        //     }
        // });
    }

    public function getCustoAttribute()
    {
        // Supondo que 'ingresso_id' esteja disponível no objeto
        $ingresso = Ingresso::find($this->ingresso_id);
        return $ingresso ? $ingresso->custo : null;
    }
}
