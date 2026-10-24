<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Medico;
use App\Models\Especialidad;
use App\Models\Paciente;
use App\Models\Cita;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        // Contar datos para las estadísticas
        $medicosActivos = Medico::where('estado', true)->count();
        $especialidades = Especialidad::where('estado', true)->count();
        $pacientesActivos = Paciente::where('estado', true)->count();
        $citasHoy = Cita::where('fecha', date('Y-m-d'))->count();

        return view('admin.dashboard', compact(
            'medicosActivos',
            'especialidades',
            'pacientesActivos',
            'citasHoy'
        ));
    }

    // Métodos temporales para las rutas del menú
    
    
    public function especialidades() {
        return redirect()->route('admin.especialidades');
    }
    
    public function medicos() {
        return redirect()->route('admin.medicos');
    }
    
    public function horarios() {
    return redirect()->route('admin.horarios');
    }
    
    public function pacientes() {
        return redirect()->route('admin.pacientes');
    }
    
    public function disponibilidad() {
        return redirect()->route('admin.disponibilidad');
    }
    
    public function citas() {
        return redirect()->route('admin.citas');
    }
    
    public function agenda() {
        return redirect()->route('admin.agenda');
    }
    
    public function reportes() {
        return 'Página de Reportes (próximamente)';
    }
}