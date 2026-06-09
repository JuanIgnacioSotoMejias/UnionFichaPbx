<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

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
        'horario_turno',
        'horario_comida',
        'horario_descanso',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function historial(): HasMany
    {
        return $this->hasMany(HistorialAcceso::class, 'operador_config_id');
    }

    /**
     * Extensiones a las que está asignado este operador.
     */
    public function extensiones(): BelongsToMany
    {
        return $this->belongsToMany(Extension::class, 'ext_operador', 'operador_config_id', 'extension_id')->withTimestamps();
    }

    /**
     * Verifica si el operador está disponible (sin extensiones asignadas)
     */
    public function getIsDisponibleAttribute(): bool
    {
        return $this->extensiones()->count() === 0;
    }

    /**
     * Busca un operador activo por su username de Ficha.
     */
    public static function findByFichaUsername(string $username): ?self
    {
        return self::where('ficha_username', $username)->first();
    }
}