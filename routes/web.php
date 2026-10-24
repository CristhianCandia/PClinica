<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegistroController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\UsuarioController;
use App\Http\Controllers\Admin\EspecialidadController;
use App\Http\Controllers\PacienteController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\MedicoController;
use App\Http\Controllers\Admin\HorarioController;
// =====================================================================
// Rutas de Autenticación (Públicas)
// =====================================================================
Route::get('/', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/', [LoginController::class, 'login']);
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

// Rutas de registro de paciente (CU-05)
Route::get('/registro', [RegistroController::class, 'showRegistroForm'])->name('registro');
Route::post('/registro', [RegistroController::class, 'registrar']);

// =====================================================================
// Rutas de Citas (CU-09 y CU-10)
// =====================================================================
Route::prefix('citas')->name('citas.')->group(function () {
    // CU-09: Consultar disponibilidad
    Route::get('/disponibilidad', [App\Http\Controllers\CitaController::class, 'consultarDisponibilidad'])->name('disponibilidad');
    Route::post('/citas/horarios-disponibles', [App\Http\Controllers\CitaController::class, 'obtenerHorariosDisponibles'])->name('horarios-disponibles');
    
    // CU-10: Programar cita
    Route::get('/programar', [App\Http\Controllers\CitaController::class, 'programarCita'])->name('programar');
    Route::post('/programar', [App\Http\Controllers\CitaController::class, 'guardarCita']);
    
    // Listar citas
    Route::get('/', [App\Http\Controllers\CitaController::class, 'index'])->name('index');
});

// =====================================================================
// Rutas del Administrador
// =====================================================================
Route::prefix('admin')->name('admin.')->group(function () {
    // Dashboard principal
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    
    // Gestión de Usuarios (CU-03)
    Route::get('/usuarios', [UsuarioController::class, 'index'])->name('usuarios');
    Route::post('/usuarios', [UsuarioController::class, 'store'])->name('usuarios.store');
    Route::get('/usuarios/{id}/editar', [UsuarioController::class, 'edit'])->name('usuarios.editar');
    Route::put('/usuarios/{id}', [UsuarioController::class, 'update'])->name('usuarios.update');
    Route::post('/usuarios/{id}/toggle', [UsuarioController::class, 'toggleEstado'])->name('usuarios.toggle');
    
    // Gestión de Especialidades (CU-06)
    Route::get('/especialidades', [EspecialidadController::class, 'index'])->name('especialidades');
    Route::post('/especialidades', [EspecialidadController::class, 'store'])->name('especialidades.store');
    Route::get('/especialidades/{id}/editar', [EspecialidadController::class, 'edit'])->name('especialidades.editar');
    Route::put('/especialidades/{id}', [EspecialidadController::class, 'update'])->name('especialidades.update');
    Route::post('/especialidades/{id}/toggle', [EspecialidadController::class, 'toggleEstado'])->name('especialidades.toggle');
    
    // Gestión de Médicos (CU-07)
    Route::get('/medicos', [MedicoController::class, 'index'])->name('medicos');
    Route::get('/medicos/crear', [MedicoController::class, 'create'])->name('medicos.crear');
    Route::post('/medicos', [MedicoController::class, 'store'])->name('medicos.store');
    Route::get('/medicos/{id}/editar', [MedicoController::class, 'edit'])->name('medicos.editar');
    Route::put('/medicos/{id}', [MedicoController::class, 'update'])->name('medicos.update');
    Route::post('/medicos/{id}/toggle', [MedicoController::class, 'toggleEstado'])->name('medicos.toggle');
    
    // Gestión de Horarios (CU-08)
    Route::get('/horarios', [HorarioController::class, 'index'])->name('horarios');
    Route::get('/horarios/medico/{id}', [HorarioController::class, 'verHorarios'])->name('horarios.ver');
    Route::post('/horarios', [HorarioController::class, 'store'])->name('horarios.store');
    Route::get('/horarios/{id}/editar', [HorarioController::class, 'edit'])->name('horarios.editar');
    Route::put('/horarios/{id}', [HorarioController::class, 'update'])->name('horarios.update');
    Route::post('/horarios/{id}/toggle', [HorarioController::class, 'toggleEstado'])->name('horarios.toggle');
    Route::delete('/horarios/{id}', [HorarioController::class, 'destroy'])->name('horarios.destroy');
    
    // Gestión de Pacientes (CU-04)
    Route::get('/pacientes', [PacienteController::class, 'index'])->name('pacientes');
    Route::get('/pacientes/crear', [PacienteController::class, 'create'])->name('pacientes.crear');
    Route::post('/pacientes', [PacienteController::class, 'store'])->name('pacientes.store');
    Route::get('/pacientes/{id}/editar', [PacienteController::class, 'edit'])->name('pacientes.editar');
    Route::put('/pacientes/{id}', [PacienteController::class, 'update'])->name('pacientes.update');
    Route::post('/pacientes/{id}/toggle', [PacienteController::class, 'toggleEstado'])->name('pacientes.toggle');
    
    // Otras rutas (temporales)
    Route::get('/disponibilidad', [DashboardController::class, 'disponibilidad'])->name('disponibilidad');
    Route::get('/citas', [DashboardController::class, 'citas'])->name('citas');
    Route::get('/agenda', [DashboardController::class, 'agenda'])->name('agenda');
    Route::get('/reportes', [DashboardController::class, 'reportes'])->name('reportes');
});

// =====================================================================
// Rutas del Recepcionista
// =====================================================================
Route::prefix('recepcionista')->name('recepcionista.')->group(function () {
    Route::get('/dashboard', function() {
        return view('recepcionista.dashboard');
    })->name('dashboard');
    
    // Gestión de Pacientes (mismo controlador que el admin)
    Route::get('/pacientes', [PacienteController::class, 'index'])->name('pacientes');
    Route::get('/pacientes/crear', [PacienteController::class, 'create'])->name('pacientes.crear');
    Route::post('/pacientes', [PacienteController::class, 'store'])->name('pacientes.store');
    Route::get('/pacientes/{id}/editar', [PacienteController::class, 'edit'])->name('pacientes.editar');
    Route::put('/pacientes/{id}', [PacienteController::class, 'update'])->name('pacientes.update');
    Route::post('/pacientes/{id}/toggle', [PacienteController::class, 'toggleEstado'])->name('pacientes.toggle');
    
    // Otras rutas (temporales)
    Route::get('/disponibilidad', function() { return 'Disponibilidad (próximamente)'; })->name('disponibilidad');
    Route::get('/programar-cita', function() { return 'Programar cita (próximamente)'; })->name('programar-cita');
    Route::get('/citas', function() { return 'Citas registradas (próximamente)'; })->name('citas');
    Route::get('/agenda', function() { return 'Agenda médica (próximamente)'; })->name('agenda');
    Route::get('/historial', function() { return 'Historial de citas (próximamente)'; })->name('historial');
});

// =====================================================================
// Rutas temporales para otros roles
// =====================================================================
Route::get('/medico/dashboard', function() {
    return 'Dashboard Médico (próximamente)';
})->name('medico.dashboard');

Route::get('/paciente/dashboard', function() {
    return 'Dashboard Paciente (próximamente)';
})->name('paciente.dashboard');