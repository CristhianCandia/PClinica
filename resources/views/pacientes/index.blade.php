@extends('layouts.' . (Auth::user()->rol->nombre === 'RECEPCIONISTA' ? 'recepcionista' : 'app'))

@section('title', 'Gestionar Pacientes')

@section('content')
<div class="content-header">
    <h1>Gestionar pacientes</h1>
    <p>Buscar por CI, nombre o apellido</p>
</div>

@if(session('success'))
    <div style="background: #d4edda; color: #155724; padding: 12px; border-radius: 5px; margin-bottom: 20px; border: 1px solid #c3e6cb;">
        {{ session('success') }}
    </div>
@endif

<!-- BUSCADOR -->
<div style="background: white; padding: 25px; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.05); margin-bottom: 30px;">
    <form method="GET" action="{{ route(Auth::user()->rol->nombre === 'RECEPCIONISTA' ? 'recepcionista.pacientes' : 'admin.pacientes') }}" style="display: flex; gap: 10px;">
        <input 
            type="text" 
            name="buscar" 
            value="{{ request('buscar') }}" 
            placeholder="Buscar por CI, nombre o apellido..." 
            style="flex: 1; padding: 10px; border: 1px solid #ddd; border-radius: 5px; font-size: 14px;"
        >
        <button type="submit" style="padding: 10px 25px; background: #1e5799; color: white; border: none; border-radius: 5px; cursor: pointer; font-weight: 600;">Buscar</button>
        <a href="{{ route(Auth::user()->rol->nombre === 'RECEPCIONISTA' ? 'recepcionista.pacientes.crear' : 'admin.pacientes.crear') }}" style="padding: 10px 25px; background: #28a745; color: white; text-decoration: none; border-radius: 5px; font-weight: 600; display: inline-block;">+ Nuevo paciente</a>
    </form>
</div>

<!-- TABLA DE PACIENTES -->
<div style="background: white; padding: 25px; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.05); overflow-x: auto;">
    <table style="width: 100%; border-collapse: collapse; min-width: 900px;">
        <thead>
            <tr style="border-bottom: 2px solid #e0e0e0;">
                <th style="padding: 12px; text-align: left; color: #666; font-weight: 600; font-size: 14px;">Código</th>
                <th style="padding: 12px; text-align: left; color: #666; font-weight: 600; font-size: 14px;">CI</th>
                <th style="padding: 12px; text-align: left; color: #666; font-weight: 600; font-size: 14px;">Nombre completo</th>
                <th style="padding: 12px; text-align: left; color: #666; font-weight: 600; font-size: 14px;">Teléfono</th>
                <th style="padding: 12px; text-align: left; color: #666; font-weight: 600; font-size: 14px;">Registro</th>
                <th style="padding: 12px; text-align: left; color: #666; font-weight: 600; font-size: 14px;">Estado</th>
                <th style="padding: 12px; text-align: left; color: #666; font-weight: 600; font-size: 14px;">Acciones</th>
            </tr>
        </thead>
        <tbody>
            @forelse($pacientes as $paciente)
            <tr style="border-bottom: 1px solid #e0e0e0;">
                <td style="padding: 12px; font-size: 14px;">{{ $paciente->codigo }}</td>
                <td style="padding: 12px; font-size: 14px;">{{ $paciente->ci }}</td>
                <td style="padding: 12px; font-size: 14px;">{{ $paciente->nombres }} {{ $paciente->apellidos }}</td>
                <td style="padding: 12px; font-size: 14px;">{{ $paciente->telefono }}</td>
                <td style="padding: 12px; font-size: 14px;">
                    {{ \Illuminate\Support\Carbon::parse($paciente->fecha_registro)->format('d/m/Y') }}
                </td>
                <td style="padding: 12px;">
                    @if($paciente->estado)
                        <span style="padding: 4px 12px; border-radius: 4px; font-size: 12px; font-weight: 600; background: #d4edda; color: #155724;">Activo</span>
                    @else
                        <span style="padding: 4px 12px; border-radius: 4px; font-size: 12px; font-weight: 600; background: #f8d7da; color: #721c24;">Inactivo</span>
                    @endif
                </td>
                <td style="padding: 12px;">
                    <a href="{{ route(Auth::user()->rol->nombre === 'RECEPCIONISTA' ? 'recepcionista.pacientes.editar' : 'admin.pacientes.editar', $paciente->id_paciente) }}" 
                       style="padding: 6px 12px; background: #17a2b8; color: white; text-decoration: none; border-radius: 4px; font-size: 12px; margin-right: 5px; display: inline-block;">
                        Editar / Citas
                    </a>
                    
                    <form method="POST" action="{{ route(Auth::user()->rol->nombre === 'RECEPCIONISTA' ? 'recepcionista.pacientes.toggle' : 'admin.pacientes.toggle', $paciente->id_paciente) }}" style="display: inline;">
                        @csrf
                        @if($paciente->estado)
                            <button type="submit" style="padding: 6px 12px; background: #dc3545; color: white; border: none; border-radius: 4px; font-size: 12px; cursor: pointer;">Desactivar</button>
                        @else
                            <button type="submit" style="padding: 6px 12px; background: #28a745; color: white; border: none; border-radius: 4px; font-size: 12px; cursor: pointer;">Activar</button>
                        @endif
                    </form>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="7" style="padding: 20px; text-align: center; color: #666; font-size: 14px;">
                    No se encontraron pacientes registrados.
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection