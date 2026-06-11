@extends('reportes.pdf_template', ['tituloReporte' => 'Reporte Específico de Extensión', 'filtro' => 'Extensión ' . $extension->numero])

@section('content')
    <div style="margin-bottom: 20px;">
        <h3 style="color: #1e293b; margin-bottom: 5px;">Detalles de la Extensión</h3>
        <p style="margin: 0; padding-bottom: 3px;"><strong>Número:</strong> {{ $extension->numero }}</p>
        <p style="margin: 0; padding-bottom: 3px;"><strong>Descripción:</strong> {{ $extension->descripcion ?? 'N/A' }}</p>
        <p style="margin: 0;"><strong>Estado de Configuración:</strong> 
            <span class="badge {{ $extension->is_active ? 'badge-green' : 'badge-red' }}">
                {{ $extension->is_active ? 'Activa' : 'Inactiva' }}
            </span>
        </p>
    </div>

    <div style="background-color: #f8fafc; border: 1px solid #e2e8f0; padding: 15px; margin-bottom: 20px;">
        <h3 style="margin-top: 0; color: #b91c1c;">Resumen de Caídas (OFFLINE)</h3>
        <p style="font-size: 14px; margin: 0;">
            Número total de veces que la extensión cambió a estado OFFLINE: 
            <strong style="font-size: 18px; color: #b91c1c;">{{ $caidasOffline }}</strong>
        </p>
    </div>

    <h3 style="color: #1e293b;">Actividad Detallada</h3>
    <table>
        <thead>
            <tr>
                <th style="width: 25%;">Fecha y Hora</th>
                <th style="width: 35%;">Comando / Evento</th>
                <th style="width: 20%;">Respuesta PBX</th>
                <th style="width: 20%; text-align: center;">Estado AMI</th>
            </tr>
        </thead>
        <tbody>
            @forelse($actividad as $log)
                <tr>
                    <td>{{ $log->created_at->format('d/m/Y H:i:s') }}</td>
                    <td>{{ $log->comando_enviado ?? 'Evento del Sistema' }}</td>
                    <td>{{ $log->respuesta_asterisk ?? 'N/A' }}</td>
                    <td class="text-center">
                        @php
                            $estado = strtolower($log->estado_actual ?? '');
                            $claseBadge = 'badge-blue';
                            if (str_contains($estado, 'offline') || str_contains(strtolower($log->respuesta_asterisk ?? ''), 'offline')) {
                                $claseBadge = 'badge-red';
                            } elseif (str_contains($estado, 'online') || str_contains($estado, 'ok')) {
                                $claseBadge = 'badge-green';
                            }
                        @endphp
                        <span class="badge {{ $claseBadge }}">
                            {{ $log->estado_actual ?? 'DESCONOCIDO' }}
                        </span>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="4" class="text-center" style="color: #64748b; font-style: italic;">No hay registros de actividad para esta extensión en la bitácora.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
@endsection
