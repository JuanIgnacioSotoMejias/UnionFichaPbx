<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class ExtensionOfflineAlert implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public string $extension;
    public string $descripcion;
    public string $timestamp;

    public function __construct(string $extension, string $descripcion)
    {
        $this->extension = $extension;
        $this->descripcion = $descripcion;
        $this->timestamp = now()->toDateTimeString();
    }

    public function broadcastOn(): array
    {
        return [
            new Channel('alertas'),
        ];
    }

    public function broadcastAs(): string
    {
        return 'extension.offline';
    }

    public function broadcastWith(): array
    {
        return [
            'extension' => $this->extension,
            'nivel' => 'WARNING',
            'tipo_alerta' => 'EXTENSIÓN OFFLINE',
            'descripcion' => $this->descripcion,
            'timestamp' => $this->timestamp,
        ];
    }
}
