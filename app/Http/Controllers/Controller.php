<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\OperadorConfig;
use App\Models\LogApiReceptor;
use App\Models\HistorialAcceso;
use Illuminate\Support\Facades\Log;

class TelefoniaController extends Controller
{
    public function gestionarSesion(Request $request)
    {
        // 1. Guardar log de la petición cruda para auditoría de TI
        $log = LogApiReceptor::create([
            'payload_recibido' => $request->all(),
            'codigo_respuesta' => 200 // Por defecto
        ]);

        // 2. Validar los datos mínimos necesarios
        $request->validate([
            'external_user_id' => 'required|integer',
            'accion' => 'required|in:login,logout',
        ]);

        // 3. Buscar al operador en nuestra configuración
        $operador = OperadorConfig::where('external_user_id', $request->external_user_id)->first();

        if (!$operador) {
            $log->update(['codigo_respuesta' => 404, 'mensaje_error' => 'Operador no configurado en el sistema receptor']);
            return response()->json(['error' => 'Operador no encontrado'], 404);
        }

        // 4. Lógica de activación/desactivación
        $nuevoEstado = ($request->accion === 'login') ? true : false;
        $evento = ($request->accion === 'login') ? 'LOGIN' : 'LOGOUT';

        // Actualizar estado del operador
        $operador->update(['is_active' => $nuevoEstado]);

        // Registrar en historial
        $operador->historial()->create([
            'evento' => $evento,
            'origen_ip' => $request->ip()
        ]);

        // --- AQUÍ IRÁ LA LLAMADA A LA API AMI EN EL SIGUIENTE PASO ---
        // Por ahora, simulamos que todo salió bien con Asterisk
        
        return response()->json([
            'mensaje' => "Extensión {$operador->extension} actualizada a " . ($nuevoEstado ? 'Activa' : 'Inactiva'),
            'operador' => $operador->nombre_operador
        ], 200);
    }
}