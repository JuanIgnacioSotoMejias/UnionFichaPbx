<?php

namespace App\Http\Controllers;

use App\Models\Extension;
use App\Models\OperadorConfig;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class OperadorController extends Controller
{
    /**
     * Muestra la lista de operadores (Módulo de Personal)
     */
    public function index(): View
    {
        $operadores = OperadorConfig::orderBy('nombre_operador')->paginate(15);
        $extensionesLibres = Extension::where('numero', 'like', '8%')
            ->where('estado', 'libre')
            ->orderBy('numero')
            ->get();

        return view('operadores.index', compact('operadores', 'extensionesLibres'));
    }

    /**
     * Actualiza la extensión y/o el grupo horario de un operador
     */
    public function update(\App\Http\Requests\UpdateOperadorRequest $request, OperadorConfig $operador): RedirectResponse
    {
        $newExtension = $request->input('extension');
        $grupoHorario = $request->input('grupo_horario');

        // Si se seleccionó una extensión y NO se seleccionó un grupo manualmente, lo hereda de la extensión.
        if ($newExtension !== '0000' && empty($grupoHorario)) {
            $extNew = Extension::where('numero', $newExtension)->first();
            if ($extNew) {
                $grupoHorario = $extNew->grupo_horario;
            }
        }

        $operador->update([
            'extension'     => $newExtension,
            'grupo_horario' => $grupoHorario,
            'is_active'     => $request->input('is_active'),
        ]);

        return redirect()->route('operadores.index')->with('success', 'Operador actualizado exitosamente.');
    }

    /**
     * Activar / desactivar manualmente (sin pasar por AMI).
     * Útil para corrección administrativa.
     */
    public function toggleActivo(OperadorConfig $operador): RedirectResponse
    {
        $operador->update(['is_active' => !$operador->is_active]);
        $estado = $operador->is_active ? 'activado' : 'desactivado';

        return back()->with('success', "Operador '{$operador->nombre_operador}' {$estado} manualmente.");
    }
}
