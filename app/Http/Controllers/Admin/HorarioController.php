<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\HorarioAtencion;
use App\Models\Medico;
use Illuminate\Http\Request;

class HorarioController extends Controller
{
    // Mostrar vista inicial de horarios
    public function index()
    {
        $medicos = Medico::where('estado', true)
            ->orderBy('apellidos')
            ->orderBy('nombres')
            ->get();
        
        return view('admin.horarios', compact('medicos'));
    }

    // Ver horarios de un médico específico
    public function verHorarios($id)
    {
        // Usamos find() en lugar de findOrFail() para evitar errores 500 si el ID no existe
        $medico = Medico::find($id);
        
        if (!$medico) {
            return redirect()->route('admin.horarios')->withErrors(['error' => 'El médico seleccionado no existe o fue eliminado.']);
        }

        $horarios = HorarioAtencion::where('id_medico', $id)
            ->orderBy('dia_semana')
            ->orderBy('hora_inicio')
            ->get();
        
        $medicos = Medico::where('estado', true)
            ->orderBy('apellidos')
            ->orderBy('nombres')
            ->get();
        
        return view('admin.horarios', compact('medicos', 'medico', 'horarios'));
    }

    // Crear nuevo horario
    public function store(Request $request)
    {
        $request->validate([
            'id_medico' => 'required|exists:medico,id_medico',
            'dia_semana' => 'required|integer|between:1,7',
            'hora_inicio' => 'required',
            'hora_fin' => 'required|after:hora_inicio',
            'duracion_cita_min' => 'required|integer|between:10,120',
        ], [
            'hora_fin.after' => 'La hora final debe ser posterior a la hora inicial.',
        ]);

        // Validar que no se cruce con otro horario del mismo médico y día
        $cruza = HorarioAtencion::where('id_medico', $request->id_medico)
            ->where('dia_semana', $request->dia_semana)
            ->where('estado', true)
            ->where(function($query) use ($request) {
                $query->whereBetween('hora_inicio', [$request->hora_inicio, $request->hora_fin])
                      ->orWhereBetween('hora_fin', [$request->hora_inicio, $request->hora_fin])
                      ->orWhere(function($q) use ($request) {
                          $q->where('hora_inicio', '<=', $request->hora_inicio)
                            ->where('hora_fin', '>=', $request->hora_fin);
                      });
            })
            ->exists();

        if ($cruza) {
            $diaNombre = $this->getDiaNombre($request->dia_semana);
            return back()->withErrors([
                'hora_inicio' => "El horario se cruza con un bloque de {$diaNombre} ya registrado."
            ])->withInput();
        }

        HorarioAtencion::create([
            'id_medico' => $request->id_medico,
            'dia_semana' => $request->dia_semana,
            'hora_inicio' => $request->hora_inicio,
            'hora_fin' => $request->hora_fin,
            'duracion_cita_min' => $request->duracion_cita_min,
            'estado' => true,
        ]);

        return redirect()->route('admin.horarios.ver', $request->id_medico)->with('success', 'Horario registrado exitosamente.');
    }

    // Mostrar formulario de edición
    public function edit($id)
    {
        $horario = HorarioAtencion::find($id);
        
        if (!$horario) {
            return redirect()->route('admin.horarios')->withErrors(['error' => 'Horario no encontrado.']);
        }

        $medico = $horario->medico;
        $medicos = Medico::where('estado', true)->get();
        
        return view('admin.horarios-editar', compact('horario', 'medico', 'medicos'));
    }

    // Actualizar horario
    public function update(Request $request, $id)
    {
        $request->validate([
            'dia_semana' => 'required|integer|between:1,7',
            'hora_inicio' => 'required',
            'hora_fin' => 'required|after:hora_inicio',
            'duracion_cita_min' => 'required|integer|between:10,120',
        ], [
            'hora_fin.after' => 'La hora final debe ser posterior a la hora inicial.',
        ]);

        $horario = HorarioAtencion::find($id);
        if (!$horario) {
            return back()->withErrors(['error' => 'Horario no encontrado.']);
        }

        // Validar que no se cruce con OTRO horario (excluyendo el actual)
        $cruza = HorarioAtencion::where('id_medico', $horario->id_medico)
            ->where('id_horario', '!=', $id)
            ->where('dia_semana', $request->dia_semana)
            ->where('estado', true)
            ->where(function($query) use ($request) {
                $query->whereBetween('hora_inicio', [$request->hora_inicio, $request->hora_fin])
                      ->orWhereBetween('hora_fin', [$request->hora_inicio, $request->hora_fin]);
            })
            ->exists();

        if ($cruza) {
            $diaNombre = $this->getDiaNombre($request->dia_semana);
            return back()->withErrors([
                'hora_inicio' => "El horario se cruza con un bloque de {$diaNombre} ya registrado."
            ])->withInput();
        }

        $horario->update([
            'dia_semana' => $request->dia_semana,
            'hora_inicio' => $request->hora_inicio,
            'hora_fin' => $request->hora_fin,
            'duracion_cita_min' => $request->duracion_cita_min,
        ]);

        return redirect()->route('admin.horarios.ver', $horario->id_medico)->with('success', 'Horario actualizado.');
    }

    // Activar/Desactivar horario
    public function toggleEstado($id)
    {
        $horario = HorarioAtencion::find($id);
        if ($horario) {
            $horario->estado = !$horario->estado;
            $horario->save();
        }

        return redirect()->route('admin.horarios.ver', $horario->id_medico ?? 0)->with('success', 'Estado del horario actualizado.');
    }

    // Helper para nombre del día
    private function getDiaNombre($dia)
    {
        $dias = [
            1 => 'Lunes', 2 => 'Martes', 3 => 'Miércoles', 4 => 'Jueves',
            5 => 'Viernes', 6 => 'Sábado', 7 => 'Domingo'
        ];
        return $dias[$dia] ?? 'el día seleccionado';
    }
}