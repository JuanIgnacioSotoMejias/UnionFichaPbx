<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Extension extends Model
{
    use HasFactory;

    protected $fillable = [
        'numero',
        'descripcion',
        'nombre_freepbx',
        'tipo_tecnologia',
        'estado',
        'grupo_horario',
        'sincronizado_at',
        'is_active',
        'motivo_inactividad',
    ];

    protected $casts = [
        'sincronizado_at' => 'datetime',
    ];

    // =========================================================================
    // Relaciones
    // =========================================================================

    /**
     * Operadores asignados a esta extensión (Max 12).
     */
    public function operadores(): BelongsToMany
    {
        return $this->belongsToMany(OperadorConfig::class, 'ext_operador', 'extension_id', 'operador_config_id')->withTimestamps();
    }

    // =========================================================================
    // Scopes
    // =========================================================================

    /**
     * Extensiones libres disponibles para asignación (sin operadores).
     */
    public function scopeLibres($query)
    {
        return $query->where('estado', 'libre')->doesntHave('operadores');
    }

    /**
     * Extensiones en uso (con al menos un operador asignado).
     */
    public function scopeEnUso($query)
    {
        return $query->where('estado', 'en_uso')->has('operadores');
    }

    // =========================================================================
    // Helpers de Estado
    // =========================================================================

    /**
     * Asigna un operador a la extensión.
     */
    public function asignarA(OperadorConfig $operador): void
    {
        if ($this->operadores()->count() < 12) {
            $this->operadores()->syncWithoutDetaching([$operador->id]);
            $this->update(['estado' => 'en_uso']);
        }
    }

    /**
     * Libera un operador específico de la extensión.
     */
    public function liberarOperador(OperadorConfig $operador): void
    {
        $this->operadores()->detach($operador->id);
        
        if ($this->operadores()->count() === 0) {
            $this->update(['estado' => 'libre']);
        }
    }

    /**
     * Libera la extensión por completo (quita a todos los operadores).
     */
    public function markAsFree(): void
    {
        $this->operadores()->detach();
        $this->update(['estado' => 'libre']);
    }

    /**
     * Marca la extensión como en uso manualmente.
     */
    public function markAsInUse(): void
    {
        $this->update(['estado' => 'en_uso']);
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
