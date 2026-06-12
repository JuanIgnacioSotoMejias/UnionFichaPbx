@extends('reportes.pdf_template', ['tituloReporte' => 'Reporte Específico de Operador', 'filtro' => 'Operador: ' . $operador->nombre_operador])

@section('content')
    <div style="margin-bottom: 20px;">
        <h3 style="color: #1e293b; margin-bottom: 5px;">Datos del Operador</h3>
        <p style="margin: 0; padding-bottom: 3px;"><strong>Nombre:</strong> {{ $operador->nombre_operador }}</p>
        <p style="margin: 0; padding-bottom: 3px;"><strong>Ficha / Username:</strong> {{ $operador->ficha_username ?? 'N/A' }}</p>
        <p style="margin: 0;"><strong>Horario Asignado:</strong> {{ $operador->horario_turno ?? 'Sin horario definido' }}</p>
    </div>

    <div style="background-color: #f8fafc; border: 1px solid #e2e8f0; padding: 15px; margin-bottom: 20px;">
        <h3 style="margin-top: 0; color: #1e293b;">Resumen de Rendimiento</h3>
        
        <table style="width: 100%; border: none; margin-bottom: 0;">
            <tr style="border: none;">
                <td style="border: none; padding: 5px 0;"><strong>Turnos Trabajados:</strong> {{ $turnosTrabajados }}</td>
                <td style="border: none; padding: 5px 0;"><strong>Tiempo en Línea (Acumulado):</strong> {{ $tiempoTotalHoras }} Horas</td>
                <td style="border: none; padding: 5px 0; text-align: right;">
                    <strong>Rendimiento:</strong> 
                    <span class="badge {{ $badgeClass }}">{{ $rendimiento }}</span>
                </td>
            </tr>
        </table>
    </div>

    <h3 style="color: #1e293b;">Historial de Accesos (Logins / Logouts)</h3>
    <table>
        <thead>
            <tr>
                <th style="width: 30%;">Fecha y Hora</th>
                <th style="width: 30%;">Evento</th>
                <th style="width: 40%;">Origen IP</th>
            </tr>
        </thead>
        <tbody>
            @forelse($historial as $acceso)
                <tr>
                    <td>{{ is_string($acceso->created_at) ? date('d/m/Y H:i:s', strtotime($acceso->created_at)) : $acceso->created_at->format('d/m/Y H:i:s') }}</td>
                    <td>
                        <span class="badge {{ $acceso->evento == 'LOGIN' ? 'badge-green' : 'badge-amber' }}">
                            {{ $acceso->evento }}
                        </span>
                    </td>
                    <td>{{ $acceso->origen_ip ?? 'Desconocida' }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="3" class="text-center" style="color: #64748b; font-style: italic;">No hay registros de accesos para este operador en el período consultado.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
@endsection
