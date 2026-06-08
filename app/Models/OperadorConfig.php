<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class OperadorConfig extends Model
{
    protected $table = 'operadores_config';

    protected $fillable = [
        'ficha_username',   // Campo 'usuario' del sistema Ficha (string)
        'nombre_operador',
        'extension',
        'queue_name',
        'is_active',
        'grupo_horario',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function historial(): HasMany
    {
        return $this->hasMany(HistorialAcceso::class, 'operador_config_id');
    }

    /**
     * Extensión actualmente asignada a este operador.
     */
    public function extensionAsignada(): \Illuminate\Database\Eloquent\Relations\HasOne
    {
        return $this->hasOne(Extension::class, 'operador_config_id');
    }

    /**
     * Busca un operador activo por su username de Ficha.
     */
    public static function findByFichaUsername(string $username): ?self
    {
        return self::where('ficha_username', $username)->first();
    }
}