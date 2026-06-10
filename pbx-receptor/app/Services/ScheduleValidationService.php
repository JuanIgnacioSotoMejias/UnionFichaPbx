<?php

namespace App\Services;

use App\Models\OperadorConfig;
use Illuminate\Support\Carbon;

class ScheduleValidationService
{
    /**
     * Determina el estado de descanso actual de un operador basándose en su grupo horario.
     * 
     * @param OperadorConfig $operador
     * @return string 'activo', 'comida', 'descanso'
     */
    public function getCurrentState(OperadorConfig $operador): string
    {
        if (!$operador->grupo_horario) {
            return 'activo'; // Si no tiene grupo, asumimos activo siempre
        }

        $now = Carbon::now();
        $hour = $now->hour;
        $minute = $now->minute;

        // Convertir la hora actual a un valor decimal para comparaciones más fáciles (ej: 13.5 para 1:30 PM)
        $currentTime = $hour + ($minute / 60);

        if ($operador->grupo_horario === 1) {
            // Grupo 1:
            // Comida 1: 12:00 PM a 1:00 PM (12.0 a 13.0)
            // Comida 2: 7:00 PM a 8:00 PM (19.0 a 20.0)
            // Sueño: 10:00 PM a 2:00 AM (22.0 a 24.0 o 0.0 a 2.0)
            
            if ($currentTime >= 12.0 && $currentTime < 13.0) return 'comida';
            if ($currentTime >= 19.0 && $currentTime < 20.0) return 'comida';
            if ($currentTime >= 22.0 || $currentTime < 2.0) return 'descanso';

        } elseif ($operador->grupo_horario === 2) {
            // Grupo 2:
            // Comida 1: 1:00 PM a 2:00 PM (13.0 a 14.0)
            // Comida 2: 8:00 PM a 9:00 PM (20.0 a 21.0)
            // Sueño: 2:00 AM a 6:00 AM (2.0 a 6.0)

            if ($currentTime >= 13.0 && $currentTime < 14.0) return 'comida';
            if ($currentTime >= 20.0 && $currentTime < 21.0) return 'comida';
            if ($currentTime >= 2.0 && $currentTime < 6.0) return 'descanso';
        }

        return 'activo';
    }

    /**
     * Obtiene una etiqueta visual para mostrar en la UI basada en el estado.
     */
    public function getStateLabel(string $state): array
    {
        return match($state) {
            'comida' => [
                'text' => 'En Comida',
                'class' => 'bg-amber-100 text-amber-700 border border-amber-200'
            ],
            'descanso' => [
                'text' => 'Durmiendo',
                'class' => 'bg-indigo-100 text-indigo-700 border border-indigo-200'
            ],
            default => [
                'text' => 'Activo',
                'class' => 'bg-emerald-100 text-emerald-700 border border-emerald-200'
            ]
        };
    }
}
