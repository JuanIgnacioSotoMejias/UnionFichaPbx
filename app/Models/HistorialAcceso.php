<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class HistorialAcceso extends Model
{
    protected $table = 'historial_accesos';
    public $timestamps = false; // Ya usamos created_at por defecto en la migración

    protected $fillable = [
        'operador_config_id',
        'evento',
        'origen_ip',
    ];

    public function operador(): BelongsTo
    {
        return $this->belongsTo(OperadorConfig::class, 'operador_config_id');
    }
}