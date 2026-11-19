<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Historico extends Model
{
    protected $table = 'historicos';

    protected $fillable = [
        'ativo_id',
        'local_anterior_id',
        'local_novo_id',
        'user_id',
        'tipo',
        'descricao',
        'data_movimentacao',
    ];

    protected function casts(): array
    {
        return [
            'data_movimentacao' => 'datetime',
        ];
    }

    public function ativo(): BelongsTo
    {
        return $this->belongsTo(Ativo::class);
    }

    public function localAnterior(): BelongsTo
    {
        return $this->belongsTo(Local::class, 'local_anterior_id');
    }

    public function localNovo(): BelongsTo
    {
        return $this->belongsTo(Local::class, 'local_novo_id');
    }

    public function usuario(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
