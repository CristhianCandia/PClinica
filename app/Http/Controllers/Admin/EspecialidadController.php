<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Especialidad;
use Illuminate\Http\Request;

class EspecialidadController extends Controller
{
    // Mostrar lista de especialidades
    public function index()
    {
        $especialidades = Especialidad::withCount('medicos')->orderBy('nombre')->get();
        return view('admin.especialidades', compact('especialidades'));
    }

    // Crear nueva especialidad
    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:60|unique:especialidad,nombre',
            'descripcion' => 'nullable|string|max:200',
        ], [
            'nombre.unique' => 'Ya existe una especialidad con el nombre "' . $request->nombre . '".',
        ]);

        Especialidad::create([
            'nombre' => ucwords(strtolower(trim($request->nombre))),
            'descripcion' => trim($request->descripcion),
            'estado' => true,
        ]);

        return redirect()->route('admin.especialidades')->with('success', 'Especialidad registrada.');
    }

    // Mostrar formulario de edición
    public function edit($id)
    {
        $especialidad = Especialidad::findOrFail($id);
        return view('admin.especialidades-editar', compact('especialidad'));
    }

    // Actualizar especialidad
    public function update(Request $request, $id)
    {
        $request->validate([
            'nombre' => 'required|string|max:60|unique:especialidad,nombre,' . $id . ',id_especialidad',
            'descripcion' => 'nullable|string|max:200',
        ], [
            'nombre.unique' => 'Ya existe una especialidad con el nombre "' . $request->nombre . '".',
        ]);

        $especialidad = Especialidad::findOrFail($id);
        $especialidad->update([
            'nombre' => ucwords(strtolower(trim($request->nombre))),
            'descripcion' => trim($request->descripcion),
        ]);

        return redirect()->route('admin.especialidades')->with('success', 'Especialidad actualizada.');
    }

    // Activar/Desactivar especialidad
    public function toggleEstado($id)
    {
        $especialidad = Especialidad::findOrFail($id);
        $especialidad->estado = !$especialidad->estado;
        $especialidad->save();

        return redirect()->route('admin.especialidades')->with('success', 'Estado de especialidad actualizado.');
    }
}