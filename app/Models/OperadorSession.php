<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class OperadorSession extends Model
{
    protected $table = 'operador_sessions';

    protected $fillable = [
        'operador_config_id',
        'extension',
        'fecha_inicio',
        'fecha_fin',
        'hora_inicio_esperada',
        'hora_fin_esperada',
        'estado_actual',
        'total_active_seconds',
    ];

    protected $casts = [
        'fecha_inicio' => 'datetime',
        'fecha_fin' => 'datetime',
        'total_active_seconds' => 'integer',
    ];

    public function operador(): BelongsTo
    {
        return $this->belongsTo(OperadorConfig::class, 'operador_config_id');
    }
}
