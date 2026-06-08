<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Extension extends Model
{
    use HasFactory;

    protected $fillable = [
        'numero',
        'descripcion',
        'nombre_freepbx',
        'tipo_tecnologia',
        'estado',
        'operador_config_id',
        'grupo_horario',
        'sincronizado_at',
    ];

    protected $casts = [
        'sincronizado_at' => 'datetime',
    ];

    // =========================================================================
    // Relaciones
    // =========================================================================

    /**
     * Operador asignado a esta extensión.
     */
    public function operador(): BelongsTo
    {
        return $this->belongsTo(OperadorConfig::class, 'operador_config_id');
    }

    // =========================================================================
    // Scopes
    // =========================================================================

    /**
     * Extensiones libres disponibles para asignación.
     */
    public function scopeLibres($query)
    {
        return $query->where('estado', 'libre')->whereNull('operador_config_id');
    }

    /**
     * Extensiones en uso (asignadas a un operador).
     */
    public function scopeEnUso($query)
    {
        return $query->where('estado', 'en_uso')->whereNotNull('operador_config_id');
    }

    // =========================================================================
    // Helpers de Estado
    // =========================================================================

    /**
     * Marca la extensión como en uso y asigna un operador.
     */
    public function asignarA(OperadorConfig $operador): void
    {
        $this->update([
            'estado'            => 'en_uso',
            'operador_config_id' => $operador->id,
        ]);
    }

    /**
     * Marca la extensión como en uso (sin cambiar operador).
     */
    public function markAsInUse(): void
    {
        $this->update(['estado' => 'en_uso']);
    }

    /**
     * Libera la extensión: la marca como libre y desasocia al operador.
     */
    public function markAsFree(): void
    {
        $this->update([
            'estado'            => 'libre',
            'operador_config_id' => null,
        ]);
    }

    /**
     * Asignación automática: busca la primera extensión libre y la asigna al operador.
     * Retorna la extensión asignada o null si no hay disponibles.
     */
    public static function autoAsignar(OperadorConfig $operador): ?self
    {
        $extension = self::libres()->orderBy('numero')->first();

        if ($extension) {
            $extension->asignarA($operador);
            $operador->update(['extension' => $extension->numero]);
        }

        return $extension;
    }
}
