@extends('layouts.app')

@section('title', 'Dashboard - Administrador')

@section('content')
<div class="content-header">
    <h1>Bienvenido, {{ Auth::user()->nombre_usuario }}</h1>
    <p>Opciones disponibles según su rol</p>
</div>

<!-- TARJETAS DE ESTADÍSTICAS -->
<div class="stats-grid">
    <div class="stat-card">
        <div class="stat-number">{{ $medicosActivos }}</div>
        <div class="stat-label">Médicos activos</div>
    </div>
    <div class="stat-card">
        <div class="stat-number">{{ $especialidades }}</div>
        <div class="stat-label">Especialidades</div>
    </div>
    <div class="stat-card">
        <div class="stat-number">{{ $pacientesActivos }}</div>
        <div class="stat-label">Pacientes activos</div>
    </div>
    <div class="stat-card">
        <div class="stat-number">{{ $citasHoy }}</div>
        <div class="stat-label">Citas para hoy</div>
    </div>
</div>

<!-- ACCESOS RÁPIDOS -->
<div class="quick-access">
    <h3>Accesos rápidos</h3>
    <div class="quick-access-buttons">
        <a href="{{ route('admin.usuarios') }}" class="btn-quick">Usuarios</a>
        <a href="{{ route('admin.medicos') }}" class="btn-quick">Médicos</a>
        <a href="{{ route('admin.especialidades') }}" class="btn-quick">Especialidades</a>
        <a href="{{ route('admin.horarios') }}" class="btn-quick">Horarios de atención</a>
        <a href="{{ route('admin.reportes') }}" class="btn-quick">Reportes</a>
    </div>
</div>
@endsection