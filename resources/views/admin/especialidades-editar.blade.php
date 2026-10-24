@extends('layouts.app')

@section('title', 'Editar Especialidad')

@section('content')
<div class="content-header">
    <h1>Editar especialidad</h1>
    <p>Modificar datos de: {{ $especialidad->nombre }}</p>
</div>

@if($errors->any())
    <div style="background: #f8d7da; color: #721c24; padding: 12px; border-radius: 5px; margin-bottom: 20px; border: 1px solid #f5c6cb;">
        {{ $errors->first() }}
    </div>
@endif

<div style="background: white; padding: 25px; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.05); max-width: 600px;">
    <form method="POST" action="{{ route('admin.especialidades.update', $especialidad->id_especialidad) }}">
        @csrf
        @method('PUT')
        
        <div style="margin-bottom: 20px;">
            <label style="display: block; margin-bottom: 8px; color: #333; font-weight: 500; font-size: 14px;">Nombre *</label>
            <input type="text" name="nombre" value="{{ $especialidad->nombre }}" required maxlength="60" 
                   style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 5px; font-size: 14px;">
        </div>
        
        <div style="margin-bottom: 20px;">
            <label style="display: block; margin-bottom: 8px; color: #333; font-weight: 500; font-size: 14px;">Descripción</label>
            <textarea name="descripcion" rows="3" maxlength="200" 
                      style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 5px; font-size: 14px; resize: vertical;">{{ $especialidad->descripcion }}</textarea>
        </div>

        <div style="display: flex; gap: 10px;">
            <button type="submit" style="padding: 10px 25px; background: #1e5799; color: white; border: none; border-radius: 5px; cursor: pointer; font-size: 14px; font-weight: 600;">
                Actualizar
            </button>
            <a href="{{ route('admin.especialidades') }}" style="padding: 10px 25px; background: #6c757d; color: white; text-decoration: none; border-radius: 5px; font-size: 14px; font-weight: 600;">
                Cancelar
            </a>
        </div>
    </form>
</div>
@endsection