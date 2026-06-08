<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LogApiReceptor extends Model
{
    protected $table = 'logs_api_receptor';
    protected $fillable = ['payload_recibido', 'codigo_respuesta', 'mensaje_error'];
    protected $casts = ['payload_recibido' => 'array'];
}