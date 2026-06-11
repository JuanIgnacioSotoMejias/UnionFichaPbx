<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AlertaProductividad extends Model
{
    protected $table = 'alertas_productividad';

    protected $fillable = [
        'operador_config_id',
        'tipo_alerta',
        'nivel',
        'descripcion',
        'metadatos',
        'leida',
    ];

    protected $casts = [
        'metadatos' => 'array',
        'leida' => 'boolean',
    ];

    public function operador(): BelongsTo
    {
        return $this->belongsTo(OperadorConfig::class, 'operador_config_id');
    }
}
