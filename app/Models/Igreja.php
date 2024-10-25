<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Igreja extends Model
{
    use HasFactory;

    protected $table = 'igrejas';

    protected $primaryKey = 'id';

    protected $fillable = [
        'nome',
        'ativo',
    ];

    public function inscrito()
    {
        return $this->hasMany(Inscrito::class);
    }
}
