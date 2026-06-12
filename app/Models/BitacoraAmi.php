<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BitacoraAmi extends Model
{
    protected $table = 'bitacora_ami';
    protected $fillable = [
        'comando_enviado',
        'extension',
        'respuesta_asterisk',
        'status',
        'hora_inicio_esperada',
        'hora_fin_esperada',
        'estado_actual',
    ];
}