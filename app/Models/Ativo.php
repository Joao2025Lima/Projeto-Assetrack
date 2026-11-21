<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Ativo extends Model
{
    protected $table = 'ativos';

    protected $fillable = [
        'nome',
        'patrimonio',
        'numero_serie',
        'categoria_id',
        'local_id',
        'user_id',
        'observacoes',
        'status',
        'qr_code',
    ];

    public function categoria(): BelongsTo
    {
        return $this->belongsTo(Categoria::class);
    }

    public function local(): BelongsTo
    {
        return $this->belongsTo(Local::class);
    }

    public function usuario(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function historicos(): HasMany
    {
        return $this->hasMany(Historico::class);
    }
}
