<?php

namespace App\Http\Controllers;

use App\Models\Cita;
use App\Models\Medico;
use App\Models\Paciente;
use App\Models\Especialidad;
use App\Models\HorarioAtencion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class CitaController extends Controller
{
    // Consultar disponibilidad (CU-09)
    public function consultarDisponibilidad()
    {
        $especialidades = Especialidad::where('estado', true)
            ->orderBy('nombre')
            ->get();
        
        $medicos = Medico::where('estado', true)
            ->orderBy('apellidos')
            ->orderBy('nombres')
            ->get();
        
        return view('citas.disponibilidad', compact('especialidades', 'medicos'));
    }

    // Obtener horarios disponibles (AJAX)
    public function obtenerHorariosDisponibles(Request $request)
    {
        $request->validate([
            'id_medico' => 'required|exists:medico,id_medico',
            'fecha' => 'required|date|after_or_equal:today',
        ]);

        $idMedico = $request->id_medico;
        $fecha = $request->fecha;
        
        // Obtener día de la semana (1=Lunes, 7=Domingo)
        $diaSemana = Carbon::parse($fecha)->dayOfWeek;
        if ($diaSemana == 0) {
            $diaSemana = 7;
        }
        
        // Obtener horarios de atención del médico para ese día
        $horariosAtencion = HorarioAtencion::where('id_medico', $idMedico)
            ->where('dia_semana', $diaSemana)
            ->where('estado', true)
            ->get();
        
        if ($horariosAtencion->isEmpty()) {
            return response()->json(['horarios' => []]);
        }
        
        // Obtener citas ya programadas para ese día
        $citasOcupadas = Cita::where('id_medico', $idMedico)
            ->where('fecha', $fecha)
            ->whereIn('estado', ['PROGRAMADA', 'CONFIRMADA'])
            ->get();
        
        // Generar todos los horarios disponibles
        $horariosDisponibles = [];
        
        foreach ($horariosAtencion as $horario) {
            $horaInicio = strtotime($horario->hora_inicio);
            $horaFin = strtotime($horario->hora_fin);
            $duracion = $horario->duracion_cita_min * 60;
            
            while (($horaInicio + $duracion) <= $horaFin) {
                $horaActual = date('H:i', $horaInicio);
                
                $ocupado = false;
                foreach ($citasOcupadas as $cita) {
                    $inicioCita = $cita->hora_inicio;
                    $finCita = $cita->hora_fin;
                    
                    if (($horaActual >= $inicioCita && $horaActual < $finCita) ||
                        (date('H:i', $horaInicio) >= $inicioCita && date('H:i', $horaInicio) < $finCita)) {
                        $ocupado = true;
                        break;
                    }
                }
                
                if (!$ocupado) {
                    $horariosDisponibles[] = $horaActual;
                }
                
                $horaInicio += $duracion;
            }
        }
        
        sort($horariosDisponibles);
        
        return response()->json([
            'horarios' => $horariosDisponibles,
            'fecha' => $fecha,
            'dia_semana' => $diaSemana
        ]);
    }

    // Mostrar formulario de programación (CU-10)
    public function programarCita()
    {
        $pacientes = Paciente::where('estado', true)
            ->orderBy('apellidos')
            ->orderBy('nombres')
            ->get();
        
        $medicos = Medico::where('estado', true)
            ->orderBy('apellidos')
            ->orderBy('nombres')
            ->get();
        
        $especialidades = Especialidad::where('estado', true)
            ->orderBy('nombre')
            ->get();
        
        return view('citas.programar', compact('pacientes', 'medicos', 'especialidades'));
    }

    // Guardar cita programada
    public function guardarCita(Request $request)
    {
        $request->validate([
            'id_paciente' => 'required|exists:paciente,id_paciente',
            'id_medico' => 'required|exists:medico,id_medico',
            'id_especialidad' => 'required|exists:especialidad,id_especialidad',
            'fecha' => 'required|date|after_or_equal:today',
            'hora_inicio' => 'required',
            'motivo' => 'nullable|string|max:200',
        ]);

        // Verificar que el médico tenga esa especialidad
        $tieneEspecialidad = DB::table('medico_especialidad')
            ->where('id_medico', $request->id_medico)
            ->where('id_especialidad', $request->id_especialidad)
            ->exists();
        
        if (!$tieneEspecialidad) {
            return back()->withErrors([
                'id_especialidad' => 'El médico seleccionado no tiene esta especialidad.'
            ])->withInput();
        }

        // Convertir día de la semana correctamente
        $fechaCarbon = Carbon::parse($request->fecha);
        $diaSemana = $fechaCarbon->dayOfWeek;
        if ($diaSemana == 0) {
            $diaSemana = 7;
        }

        // Buscar horario de atención
        $horario = HorarioAtencion::where('id_medico', $request->id_medico)
            ->where('dia_semana', $diaSemana)
            ->where('estado', true)
            ->first();
        
        if (!$horario) {
            return back()->withErrors([
                'hora_inicio' => 'No hay horario de atención disponible para esta fecha.'
            ])->withInput();
        }

        // Calcular hora fin
        $horaInicio = strtotime($request->hora_inicio);
        $horaFin = date('H:i:s', strtotime("+{$horario->duracion_cita_min} minutes", $horaInicio));

        // Verificar que no exista una cita en ese horario
        $citaExistente = Cita::where('id_medico', $request->id_medico)
            ->where('fecha', $request->fecha)
            ->whereIn('estado', ['PROGRAMADA', 'CONFIRMADA'])
            ->where(function($query) use ($request, $horaFin) {
                $query->whereBetween('hora_inicio', [$request->hora_inicio, $horaFin])
                      ->orWhereBetween('hora_fin', [$request->hora_inicio, $horaFin])
                      ->orWhere(function($q) use ($request, $horaFin) {
                          $q->where('hora_inicio', '<=', $request->hora_inicio)
                            ->where('hora_fin', '>=', $horaFin);
                      });
            })
            ->first();
        
        if ($citaExistente) {
            return back()->withErrors([
                'hora_inicio' => 'El horario seleccionado ya está ocupado.'
            ])->withInput();
        }

        // Generar código de cita
        $ultimoNumero = Cita::max('id_cita');
        $numero = $ultimoNumero ? $ultimoNumero + 1 : 1;
        $codigo = 'CM-' . str_pad($numero, 4, '0', STR_PAD_LEFT);

        // Obtener ID del usuario autenticado
        $usuarioId = 1;
        if (Auth::check()) {
            $usuarioId = Auth::user()->id_usuario;
        }

        // Crear la cita
        Cita::create([
            'codigo' => $codigo,
            'id_paciente' => $request->id_paciente,
            'id_medico' => $request->id_medico,
            'id_especialidad' => $request->id_especialidad,
            'fecha' => $request->fecha,
            'hora_inicio' => $request->hora_inicio,
            'hora_fin' => $horaFin,
            'motivo' => $request->motivo,
            'estado' => 'PROGRAMADA',
            'id_usuario_registro' => $usuarioId,
            'fecha_creacion' => now(),
        ]);

        return redirect()->route('citas.programar')->with('success', "Cita programada exitosamente. Código: {$codigo}");
    }

    // Listar citas
    public function index()
    {
        $citas = Cita::with(['paciente', 'medico', 'especialidad', 'usuarioRegistro'])
            ->orderBy('fecha', 'desc')
            ->orderBy('hora_inicio', 'desc')
            ->paginate(20);
        
        return view('citas.index', compact('citas'));
    }
}