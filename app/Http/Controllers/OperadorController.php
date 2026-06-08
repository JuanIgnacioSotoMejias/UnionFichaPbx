<?php

namespace App\Http\Controllers;

use App\Models\OperadorConfig;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class OperadorController extends Controller
{
    public function index(): View
    {
        $operadores = OperadorConfig::withCount('historial')
            ->orderBy('nombre_operador')
            ->paginate(20);

        return view('operadores.index', compact('operadores'));
    }

    public function create(): View
    {
        return view('operadores.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'ficha_username'  => ['required', 'string', 'max:50', 'unique:operadores_config,ficha_username'],
            'nombre_operador' => ['required', 'string', 'max:100'],
            'extension'       => ['required', 'string', 'max:10'],
            'queue_name'      => ['required', 'string', 'max:20'],
        ]);

        OperadorConfig::create($validated);

        return redirect()->route('operadores.index')
            ->with('success', "Operador '{$validated['nombre_operador']}' registrado correctamente.");
    }

    public function edit(OperadorConfig $operador): View
    {
        $historial = $operador->historial()->latest('created_at')->take(20)->get();
        return view('operadores.edit', compact('operador', 'historial'));
    }

    public function update(Request $request, OperadorConfig $operador): RedirectResponse
    {
        $validated = $request->validate([
            'ficha_username'  => ['required', 'string', 'max:50', "unique:operadores_config,ficha_username,{$operador->id}"],
            'nombre_operador' => ['required', 'string', 'max:100'],
            'extension'       => ['required', 'string', 'max:10'],
            'queue_name'      => ['required', 'string', 'max:20'],
        ]);

        $operador->update($validated);

        return redirect()->route('operadores.index')
            ->with('success', "Operador '{$operador->nombre_operador}' actualizado.");
    }

    public function destroy(OperadorConfig $operador): RedirectResponse
    {
        $nombre = $operador->nombre_operador;
        $operador->delete();

        return redirect()->route('operadores.index')
            ->with('success', "Operador '{$nombre}' eliminado.");
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
