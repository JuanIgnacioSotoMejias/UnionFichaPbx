<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BitacoraAmi extends Model
{
    protected $table = 'bitacora_ami';
    protected $fillable = ['comando_enviado', 'extension', 'respuesta_asterisk', 'status'];
}