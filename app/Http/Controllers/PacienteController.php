<?php

namespace App\Http\Controllers;

use App\Models\Paciente;
use Illuminate\Http\Request;
use Carbon\Carbon;

class PacienteController extends Controller
{
    // Mostrar lista de pacientes con búsqueda
    public function index(Request $request)
    {
        $query = Paciente::query();

        // Búsqueda por CI, nombre o apellido
        if ($request->filled('buscar')) {
            $buscar = $request->buscar;
            $query->where(function($q) use ($buscar) {
                $q->where('ci', 'like', "%{$buscar}%")
                  ->orWhere('nombres', 'like', "%{$buscar}%")
                  ->orWhere('apellidos', 'like', "%{$buscar}%")
                  ->orWhere('codigo', 'like', "%{$buscar}%");
            });
        }

        $pacientes = $query->orderBy('id_paciente', 'desc')->get();

        return view('pacientes.index', compact('pacientes'));
    }

    // Mostrar formulario de creación
    public function create()
    {
        return view('pacientes.create');
    }

    // Guardar nuevo paciente
 public function store(Request $request)
{
    $request->validate([
        'ci' => 'required|string|max:15|unique:paciente,ci',
        'nombres' => 'required|string|max:60',
        'apellidos' => 'required|string|max:60',
        'fecha_nacimiento' => 'required|date|before:today',
        'sexo' => 'required|in:M,F,O',
        'telefono' => 'required|string|max:15',
        'correo' => 'nullable|email|max:120',
        'direccion' => 'nullable|string|max:150',
    ], [
        'ci.unique' => 'Ya existe un paciente registrado con el CI ' . $request->ci . '.',
    ]);

    // Generar código automático
    $ultimoPaciente = Paciente::orderBy('id_paciente', 'desc')->first();
    $numero = $ultimoPaciente ? intval(substr($ultimoPaciente->codigo, 4)) + 1 : 1;
    $codigo = 'PAC-' . str_pad($numero, 4, '0', STR_PAD_LEFT);

    Paciente::create([
        'codigo' => $codigo,
        'ci' => $request->ci,
        'nombres' => $request->nombres,
        'apellidos' => $request->apellidos,
        'fecha_nacimiento' => $request->fecha_nacimiento,
        'sexo' => $request->sexo,
        'telefono' => $request->telefono,
        'correo' => $request->correo,
        'direccion' => $request->direccion,
        'estado' => true,
        'fecha_registro' => now(),
    ]);

    return redirect()->route('recepcionista.pacientes')->with('success', 'Paciente registrado exitosamente.');
}

    // Mostrar formulario de edición
    public function edit($id)
    {
        $paciente = Paciente::findOrFail($id);
        return view('pacientes.edit', compact('paciente'));
    }

    // Actualizar paciente
    public function update(Request $request, $id)
    {
        $request->validate([
            'codigo' => 'required|string|max:10|unique:paciente,codigo,' . $id . ',id_paciente',
            'ci' => 'required|string|max:15|unique:paciente,ci,' . $id . ',id_paciente',
            'nombres' => 'required|string|max:60',
            'apellidos' => 'required|string|max:60',
            'fecha_nacimiento' => 'required|date|before:today',
            'sexo' => 'required|in:M,F,O',
            'telefono' => 'required|string|max:15',
            'correo' => 'nullable|email|max:120',
            'direccion' => 'nullable|string|max:150',
            'tipo_sangre' => 'nullable|in:A+,A-,B+,B-,AB+,AB-,O+,O-',
            'alergias' => 'nullable|string',
            'enfermedades_cronicas' => 'nullable|string',
            'medicamentos' => 'nullable|string',
            'antecedentes' => 'nullable|string',
        ]);

        $paciente = Paciente::findOrFail($id);
        $paciente->update($request->all());

        return redirect()->route('pacientes.index')->with('success', 'Paciente actualizado exitosamente.');
    }

    // Activar/Desactivar paciente
    public function toggleEstado($id)
    {
        $paciente = Paciente::findOrFail($id);
        $paciente->estado = !$paciente->estado;
        $paciente->save();

        return redirect()->route('pacientes.index')->with('success', 'Estado del paciente actualizado.');
    }
}