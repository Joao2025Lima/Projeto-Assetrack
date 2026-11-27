<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Local extends Model
{
    protected $table = 'locals';

    protected $fillable = [
        'nome',
        'setor',
        'descricao',
        'latitude',
        'longitude',
    ];

    protected function casts(): array
    {
        return [
            'latitude' => 'float',
            'longitude' => 'float',
        ];
    }

    public function ativos(): HasMany
    {
        return $this->hasMany(Ativo::class);
    }

    public function historicosAnteriores(): HasMany
    {
        return $this->hasMany(Historico::class, 'local_anterior_id');
    }

    public function historicosNovos(): HasMany
    {
        return $this->hasMany(Historico::class, 'local_novo_id');
    }
}
