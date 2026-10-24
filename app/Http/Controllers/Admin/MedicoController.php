<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Medico;
use App\Models\Especialidad;
use App\Models\Usuario;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MedicoController extends Controller
{
    // Mostrar lista de médicos
    public function index()
    {
        $medicos = Medico::with(['usuario', 'especialidades'])->orderBy('apellidos')->get();
        return view('admin.medicos', compact('medicos'));
    }

    // Mostrar formulario de creación
    public function create()
    {
        $especialidades = Especialidad::where('estado', true)->orderBy('nombre')->get();
        $usuarios = Usuario::where('id_rol', 3)->where('estado', true)->orderBy('nombre_usuario')->get();
        
        return view('admin.medicos-crear', compact('especialidades', 'usuarios'));
    }

    // Guardar nuevo médico
    public function store(Request $request)
    {
        $request->validate([
            'codigo' => 'required|string|max:10|unique:medico,codigo',
            'registro_profesional' => 'required|string|max:20|unique:medico,registro_profesional',
            'nombres' => 'required|string|max:60',
            'apellidos' => 'required|string|max:60',
            'telefono' => 'required|string|max:15',
            'correo' => 'required|email|max:120|unique:medico,correo',
            'id_usuario' => 'nullable|exists:usuario,id_usuario',
            'especialidades' => 'required|array|min:1',
            'especialidades.*' => 'exists:especialidad,id_especialidad',
        ], [
            'especialidades.required' => 'Debe seleccionar al menos una especialidad.',
            'especialidades.min' => 'Debe seleccionar al menos una especialidad.',
        ]);

        DB::beginTransaction();

        try {
            // Generar código automático si no se proporciona
            $codigo = $request->codigo;
            if (empty($codigo)) {
                $ultimoMedico = Medico::orderBy('id_medico', 'desc')->first();
                $numero = $ultimoMedico ? intval(substr($ultimoMedico->codigo, 4)) + 1 : 1;
                $codigo = 'MED-' . str_pad($numero, 4, '0', STR_PAD_LEFT);
            }

            // Crear médico
            $medico = Medico::create([
                'codigo' => $codigo,
                'registro_profesional' => $request->registro_profesional,
                'nombres' => $request->nombres,
                'apellidos' => $request->apellidos,
                'telefono' => $request->telefono,
                'correo' => $request->correo,
                'id_usuario' => $request->id_usuario ?: null,
                'estado' => true,
                'fecha_registro' => now(),
            ]);

            // Asignar especialidades
            $medico->especialidades()->sync($request->especialidades);

            DB::commit();

            return redirect()->route('admin.medicos')->with('success', 'Médico registrado exitosamente.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['error' => 'Ocurrió un error al registrar el médico.'])->withInput();
        }
    }

    // Mostrar formulario de edición
    public function edit($id)
    {
        $medico = Medico::with('especialidades')->findOrFail($id);
        $especialidades = Especialidad::where('estado', true)->orderBy('nombre')->get();
        $usuarios = Usuario::where('id_rol', 3)->where('estado', true)->orderBy('nombre_usuario')->get();
        
        return view('admin.medicos-editar', compact('medico', 'especialidades', 'usuarios'));
    }

    // Actualizar médico
    public function update(Request $request, $id)
    {
        $request->validate([
            'codigo' => 'required|string|max:10|unique:medico,codigo,' . $id . ',id_medico',
            'registro_profesional' => 'required|string|max:20|unique:medico,registro_profesional,' . $id . ',id_medico',
            'nombres' => 'required|string|max:60',
            'apellidos' => 'required|string|max:60',
            'telefono' => 'required|string|max:15',
            'correo' => 'required|email|max:120|unique:medico,correo,' . $id . ',id_medico',
            'id_usuario' => 'nullable|exists:usuario,id_usuario',
            'especialidades' => 'required|array|min:1',
            'especialidades.*' => 'exists:especialidad,id_especialidad',
        ], [
            'especialidades.required' => 'Debe seleccionar al menos una especialidad.',
        ]);

        DB::beginTransaction();

        try {
            $medico = Medico::findOrFail($id);
            $medico->update([
                'codigo' => $request->codigo,
                'registro_profesional' => $request->registro_profesional,
                'nombres' => $request->nombres,
                'apellidos' => $request->apellidos,
                'telefono' => $request->telefono,
                'correo' => $request->correo,
                'id_usuario' => $request->id_usuario ?: null,
            ]);

            // Actualizar especialidades
            $medico->especialidades()->sync($request->especialidades);

            DB::commit();

            return redirect()->route('admin.medicos')->with('success', 'Médico actualizado exitosamente.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['error' => 'Ocurrió un error al actualizar el médico.'])->withInput();
        }
    }

    // Activar/Desactivar médico
    public function toggleEstado($id)
    {
        $medico = Medico::findOrFail($id);
        $medico->estado = !$medico->estado;
        $medico->save();

        return redirect()->route('admin.medicos')->with('success', 'Estado del médico actualizado.');
    }
}