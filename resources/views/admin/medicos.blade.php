@extends('layouts.app')

@section('title', 'Gestionar Médicos')

@section('content')
<div class="content-header">
    <h1>Gestionar médicos</h1>
    <p>Administrar médicos y sus especialidades</p>
</div>

@if(session('success'))
    <div style="background: #d4edda; color: #155724; padding: 12px; border-radius: 5px; margin-bottom: 20px; border: 1px solid #c3e6cb;">
        {{ session('success') }}
    </div>
@endif

@if($errors->any())
    <div style="background: #f8d7da; color: #721c24; padding: 12px; border-radius: 5px; margin-bottom: 20px; border: 1px solid #f5c6cb;">
        {{ $errors->first() }}
    </div>
@endif

<div style="margin-bottom: 20px;">
    <a href="{{ route('admin.medicos.crear') }}" style="padding: 10px 25px; background: #28a745; color: white; text-decoration: none; border-radius: 5px; font-weight: 600; display: inline-block;">
        + Nuevo médico
    </a>
</div>

<!-- TABLA DE MÉDICOS -->
<div style="background: white; padding: 25px; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.05); overflow-x: auto;">
    <table style="width: 100%; border-collapse: collapse; min-width: 900px;">
        <thead>
            <tr style="border-bottom: 2px solid #e0e0e0;">
                <th style="padding: 12px; text-align: left; color: #666; font-weight: 600; font-size: 14px;">Código</th>
                <th style="padding: 12px; text-align: left; color: #666; font-weight: 600; font-size: 14px;">Registro Prof.</th>
                <th style="padding: 12px; text-align: left; color: #666; font-weight: 600; font-size: 14px;">Nombre completo</th>
                <th style="padding: 12px; text-align: left; color: #666; font-weight: 600; font-size: 14px;">Teléfono</th>
                <th style="padding: 12px; text-align: left; color: #666; font-weight: 600; font-size: 14px;">Correo</th>
                <th style="padding: 12px; text-align: left; color: #666; font-weight: 600; font-size: 14px;">Especialidades</th>
                <th style="padding: 12px; text-align: left; color: #666; font-weight: 600; font-size: 14px;">Estado</th>
                <th style="padding: 12px; text-align: left; color: #666; font-weight: 600; font-size: 14px;">Acciones</th>
            </tr>
        </thead>
        <tbody>
            @forelse($medicos as $medico)
            <tr style="border-bottom: 1px solid #e0e0e0;">
                <td style="padding: 12px; font-size: 14px;">{{ $medico->codigo }}</td>
                <td style="padding: 12px; font-size: 14px;">{{ $medico->registro_profesional }}</td>
                <td style="padding: 12px; font-size: 14px;">{{ $medico->nombres }} {{ $medico->apellidos }}</td>
                <td style="padding: 12px; font-size: 14px;">{{ $medico->telefono }}</td>
                <td style="padding: 12px; font-size: 14px;">{{ $medico->correo }}</td>
                <td style="padding: 12px; font-size: 13px;">
                    @forelse($medico->especialidades as $esp)
                        <span style="background: #e8f0fe; color: #1e5799; padding: 3px 8px; border-radius: 3px; margin-right: 3px; display: inline-block; margin-bottom: 2px;">
                            {{ $esp->nombre }}
                        </span>
                    @empty
                        <span style="color: #999;">Sin especialidades</span>
                    @endforelse
                </td>
                <td style="padding: 12px;">
                    @if($medico->estado)
                        <span style="padding: 4px 12px; border-radius: 4px; font-size: 12px; font-weight: 600; background: #d4edda; color: #155724;">Activo</span>
                    @else
                        <span style="padding: 4px 12px; border-radius: 4px; font-size: 12px; font-weight: 600; background: #f8d7da; color: #721c24;">Inactivo</span>
                    @endif
                </td>
                <td style="padding: 12px;">
                    <a href="{{ route('admin.medicos.editar', $medico->id_medico) }}" 
                       style="padding: 6px 12px; background: #17a2b8; color: white; text-decoration: none; border-radius: 4px; font-size: 12px; margin-right: 5px; display: inline-block;">
                        Editar
                    </a>
                    
                    <form method="POST" action="{{ route('admin.medicos.toggle', $medico->id_medico) }}" style="display: inline;">
                        @csrf
                        @if($medico->estado)
                            <button type="submit" style="padding: 6px 12px; background: #dc3545; color: white; border: none; border-radius: 4px; font-size: 12px; cursor: pointer;">
                                Desactivar
                            </button>
                        @else
                            <button type="submit" style="padding: 6px 12px; background: #28a745; color: white; border: none; border-radius: 4px; font-size: 12px; cursor: pointer;">
                                Activar
                            </button>
                        @endif
                    </form>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="8" style="padding: 20px; text-align: center; color: #666; font-size: 14px;">
                    No hay médicos registrados.
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection