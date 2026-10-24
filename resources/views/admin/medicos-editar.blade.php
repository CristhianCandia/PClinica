@extends('layouts.app')

@section('title', 'Editar Médico')

@section('content')
<div class="content-header">
    <h1>Editar médico</h1>
    <p>Modificar datos de: {{ $medico->nombres }} {{ $medico->apellidos }}</p>
</div>

@if($errors->any())
    <div style="background: #f8d7da; color: #721c24; padding: 12px; border-radius: 5px; margin-bottom: 20px; border: 1px solid #f5c6cb;">
        <ul style="margin: 0; padding-left: 20px;">
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<div style="background: white; padding: 30px; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.05); max-width: 900px;">
    <form method="POST" action="{{ route('admin.medicos.update', $medico->id_medico) }}">
        @csrf
        @method('PUT')
        
        <div style="display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 20px; margin-bottom: 20px;">
            <div>
                <label style="display: block; margin-bottom: 8px; color: #333; font-weight: 500; font-size: 14px;">Código</label>
                <input type="text" name="codigo" value="{{ $medico->codigo }}" required maxlength="10" 
                       style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 5px; font-size: 14px;">
            </div>
            <div>
                <label style="display: block; margin-bottom: 8px; color: #333; font-weight: 500; font-size: 14px;">Registro profesional *</label>
                <input type="text" name="registro_profesional" value="{{ $medico->registro_profesional }}" required maxlength="20" 
                       style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 5px; font-size: 14px;">
            </div>
            <div>
                <label style="display: block; margin-bottom: 8px; color: #333; font-weight: 500; font-size: 14px;">Nombres *</label>
                <input type="text" name="nombres" value="{{ $medico->nombres }}" required maxlength="60" 
                       style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 5px; font-size: 14px;">
            </div>
        </div>

        <div style="display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 20px; margin-bottom: 20px;">
            <div>
                <label style="display: block; margin-bottom: 8px; color: #333; font-weight: 500; font-size: 14px;">Apellidos *</label>
                <input type="text" name="apellidos" value="{{ $medico->apellidos }}" required maxlength="60" 
                       style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 5px; font-size: 14px;">
            </div>
            <div>
                <label style="display: block; margin-bottom: 8px; color: #333; font-weight: 500; font-size: 14px;">Teléfono *</label>
                <input type="text" name="telefono" value="{{ $medico->telefono }}" required maxlength="15" 
                       style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 5px; font-size: 14px;">
            </div>
            <div>
                <label style="display: block; margin-bottom: 8px; color: #333; font-weight: 500; font-size: 14px;">Correo electrónico *</label>
                <input type="email" name="correo" value="{{ $medico->correo }}" required maxlength="120" 
                       style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 5px; font-size: 14px;">
            </div>
        </div>

        <div style="margin-bottom: 20px;">
            <label style="display: block; margin-bottom: 8px; color: #333; font-weight: 500; font-size: 14px;">Cuenta de usuario (rol Médico)</label>
            <select name="id_usuario" style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 5px; font-size: 14px; background: white;">
                <option value="">Sin cuenta</option>
                @foreach($usuarios as $usuario)
                    <option value="{{ $usuario->id_usuario }}" {{ $medico->id_usuario == $usuario->id_usuario ? 'selected' : '' }}>
                        {{ $usuario->nombre_usuario }}
                    </option>
                @endforeach
            </select>
        </div>

        <div style="margin-bottom: 30px;">
            <label style="display: block; margin-bottom: 8px; color: #333; font-weight: 500; font-size: 14px;">Especialidades *</label>
            <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(200px, 1fr)); gap: 10px; padding: 15px; background: #f8f9fa; border-radius: 5px;">
                @foreach($especialidades as $esp)
                    <label style="display: flex; align-items: center; gap: 8px; cursor: pointer; font-size: 14px;">
                        <input type="checkbox" name="especialidades[]" value="{{ $esp->id_especialidad }}" 
                               {{ $medico->especialidades->contains($esp->id_especialidad) ? 'checked' : '' }}
                               style="width: 18px; height: 18px; cursor: pointer;">
                        <span>{{ $esp->nombre }}</span>
                    </label>
                @endforeach
            </div>
            @error('especialidades')
                <span style="color: #dc3545; font-size: 12px; margin-top: 5px; display: block;">{{ $message }}</span>
            @enderror
        </div>

        <div style="display: flex; gap: 10px;">
            <button type="submit" style="padding: 10px 25px; background: #1e5799; color: white; border: none; border-radius: 5px; cursor: pointer; font-size: 14px; font-weight: 600;">
                Actualizar
            </button>
            <a href="{{ route('admin.medicos') }}" style="padding: 10px 25px; background: #6c757d; color: white; text-decoration: none; border-radius: 5px; font-size: 14px; font-weight: 600;">
                Cancelar
            </a>
        </div>
    </form>
</div>
@endsection