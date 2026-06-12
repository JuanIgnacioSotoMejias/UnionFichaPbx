<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ApiErrorLog extends Model
{
    use HasFactory;

    protected $table = 'api_error_logs';

    protected $fillable = [
        'timestamp',
        'codigo_http',
        'endpoint',
        'mensaje_error',
        'payload',
        'resuelto',
    ];

    protected $casts = [
        'timestamp' => 'datetime',
        'payload'   => 'array',
        'resuelto'  => 'boolean',
    ];

    /**
     * Scope: solo errores de hoy
     */
    public function scopeHoy($query)
    {
        return $query->whereDate('timestamp', today());
    }

    /**
     * Scope: errores no resueltos
     */
    public function scopePendientes($query)
    {
        return $query->where('resuelto', false);
    }
}
