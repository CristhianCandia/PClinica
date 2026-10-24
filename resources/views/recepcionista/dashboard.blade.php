@extends('layouts.recepcionista')

@section('title', 'Dashboard - Recepcionista')

@section('content')
<div class="content-header">
    <h1>Bienvenido, {{ Auth::user()->nombre_usuario }}</h1>
    <p>Opciones disponibles según su rol</p>
</div>

<div style="background: white; padding: 25px; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.05);">
    <h3 style="margin-bottom: 20px; color: #333;">Accesos rápidos</h3>
    <div style="display: flex; flex-wrap: wrap; gap: 10px;">
        <a href="{{ route('recepcionista.pacientes') }}" style="padding: 12px 25px; background: #1e5799; color: white; text-decoration: none; border-radius: 5px; font-weight: 600;">Pacientes</a>
        <a href="{{ route('recepcionista.programar-cita') }}" style="padding: 12px 25px; background: #28a745; color: white; text-decoration: none; border-radius: 5px; font-weight: 600;">Programar cita</a>
        <a href="{{ route('recepcionista.disponibilidad') }}" style="padding: 12px 25px; background: #17a2b8; color: white; text-decoration: none; border-radius: 5px; font-weight: 600;">Disponibilidad</a>
    </div>
</div>
@endsection