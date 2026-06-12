<?php

namespace App\Events;

use App\Models\OperadorConfig;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

/**
 * Evento de Broadcasting para notificar al dashboard en tiempo real
 * cuando un operador inicia o cierra sesión vía la API telefónica.
 * 
 * Este evento se transmite por un canal público 'dashboard' para que
 * todos los supervisores conectados lo reciban sin necesidad de F5.
 */
class OperadorSesionEvent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public string $evento;
    public string $operador;
    public string $extension;
    public string $cola;
    public string $timestamp;

    public function __construct(string $evento, OperadorConfig $operador)
    {
        $this->evento    = $evento;
        $this->operador  = $operador->nombre_operador ?? $operador->ficha_username;
        $this->extension = (string) ($operador->extension ?? '0000');
        $this->cola      = (string) ($operador->queue_name ?? 'default');
        $this->timestamp = now()->toIso8601String();
    }

    /**
     * Canal público para que cualquier supervisor conectado reciba la actualización.
     */
    public function broadcastOn(): array
    {
        return [
            new Channel('dashboard'),
        ];
    }

    /**
     * Nombre del evento tal como lo escuchará Echo en el frontend.
     */
    public function broadcastAs(): string
    {
        return 'operador.sesion';
    }
}
