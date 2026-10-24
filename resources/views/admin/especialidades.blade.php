@extends('layouts.app')

@section('title', 'Gestionar Especialidades')

@section('content')
<div class="content-header">
    <h1>Gestionar especialidades</h1>
    <p>El nombre no puede repetirse, sin importar mayúsculas o espacios</p>
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

<!-- FORMULARIO NUEVA ESPECIALIDAD -->
<div style="background: white; padding: 25px; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.05); margin-bottom: 30px;">
    <h3 style="margin-bottom: 20px; color: #333; font-size: 16px;">Nueva especialidad</h3>
    <form method="POST" action="{{ route('admin.especialidades.store') }}">
        @csrf
        <div style="display: grid; grid-template-columns: 1fr 2fr auto; gap: 15px; align-items: end;">
            <div>
                <label style="display: block; margin-bottom: 8px; color: #333; font-weight: 500; font-size: 14px;">Nombre *</label>
                <input type="text" name="nombre" value="{{ old('nombre') }}" required maxlength="60" 
                       style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 5px; font-size: 14px;">
            </div>
            <div>
                <label style="display: block; margin-bottom: 8px; color: #333; font-weight: 500; font-size: 14px;">Descripción</label>
                <input type="text" name="descripcion" value="{{ old('descripcion') }}" maxlength="200" 
                       style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 5px; font-size: 14px;">
            </div>
            <div>
                <button type="submit" style="padding: 10px 25px; background: #1e5799; color: white; border: none; border-radius: 5px; cursor: pointer; font-size: 14px; font-weight: 600;">
                    Guardar
                </button>
            </div>
        </div>
    </form>
</div>

<!-- TABLA DE ESPECIALIDADES -->
<div style="background: white; padding: 25px; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.05); overflow-x: auto;">
    <table style="width: 100%; border-collapse: collapse; min-width: 800px;">
        <thead>
            <tr style="border-bottom: 2px solid #e0e0e0;">
                <th style="padding: 12px; text-align: left; color: #666; font-weight: 600; font-size: 14px;">Especialidad</th>
                <th style="padding: 12px; text-align: left; color: #666; font-weight: 600; font-size: 14px;">Descripción</th>
                <th style="padding: 12px; text-align: center; color: #666; font-weight: 600; font-size: 14px;">Médicos</th>
                <th style="padding: 12px; text-align: left; color: #666; font-weight: 600; font-size: 14px;">Estado</th>
                <th style="padding: 12px; text-align: left; color: #666; font-weight: 600; font-size: 14px;">Acciones</th>
            </tr>
        </thead>
        <tbody>
            @forelse($especialidades as $esp)
            <tr style="border-bottom: 1px solid #e0e0e0;">
                <td style="padding: 12px; font-size: 14px; font-weight: 600;">{{ $esp->nombre }}</td>
                <td style="padding: 12px; font-size: 14px; color: #666;">{{ $esp->descripcion ?: '—' }}</td>
                <td style="padding: 12px; text-align: center; font-size: 14px;">
                    <span style="background: #e8f0fe; color: #1e5799; padding: 4px 12px; border-radius: 12px; font-weight: 600;">
                        {{ $esp->medicos_count }}
                    </span>
                </td>
                <td style="padding: 12px;">
                    @if($esp->estado)
                        <span style="padding: 4px 12px; border-radius: 4px; font-size: 12px; font-weight: 600; background: #d4edda; color: #155724;">Activa</span>
                    @else
                        <span style="padding: 4px 12px; border-radius: 4px; font-size: 12px; font-weight: 600; background: #f8d7da; color: #721c24;">Inactiva</span>
                    @endif
                </td>
                <td style="padding: 12px;">
                    <a href="{{ route('admin.especialidades.editar', $esp->id_especialidad) }}" 
                       style="padding: 6px 12px; background: #17a2b8; color: white; text-decoration: none; border-radius: 4px; font-size: 12px; margin-right: 5px; display: inline-block;">
                        Editar
                    </a>
                    
                    <form method="POST" action="{{ route('admin.especialidades.toggle', $esp->id_especialidad) }}" style="display: inline;">
                        @csrf
                        @if($esp->estado)
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
                <td colspan="5" style="padding: 20px; text-align: center; color: #666; font-size: 14px;">
                    No hay especialidades registradas.
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection