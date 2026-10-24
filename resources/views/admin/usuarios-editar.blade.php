@extends('layouts.app')

@section('title', 'Editar Usuario - Administrador')

@section('content')
<div class="content-header">
    <h1>Editar usuario</h1>
    <p>Modificar datos del usuario: {{ $usuario->nombre_usuario }}</p>
</div>

@if($errors->any())
    <div style="background: #f8d7da; color: #721c24; padding: 12px; border-radius: 5px; margin-bottom: 20px;">
        <ul style="margin: 0; padding-left: 20px;">
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<div style="background: white; padding: 25px; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.05);">
    <form method="POST" action="{{ route('admin.usuarios.update', $usuario->id_usuario) }}">
        @csrf
        @method('PUT')
        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
            <div>
                <label style="display: block; margin-bottom: 8px; color: #333; font-weight: 500;">Nombre de usuario *</label>
                <input type="text" name="nombre_usuario" value="{{ $usuario->nombre_usuario }}" required style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 5px;">
            </div>
            <div>
                <label style="display: block; margin-bottom: 8px; color: #333; font-weight: 500;">Correo electrónico *</label>
                <input type="email" name="correo" value="{{ $usuario->correo }}" required style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 5px;">
            </div>
            <div>
                <label style="display: block; margin-bottom: 8px; color: #333; font-weight: 500;">Contraseña (dejar vacío para no cambiar)</label>
                <input type="password" name="contrasena" style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 5px;">
            </div>
            <div>
                <label style="display: block; margin-bottom: 8px; color: #333; font-weight: 500;">Rol *</label>
                <select name="id_rol" required style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 5px;">
                    @foreach($roles as $rol)
                        <option value="{{ $rol->id_rol }}" {{ $usuario->id_rol == $rol->id_rol ? 'selected' : '' }}>
                            {{ $rol->nombre }}
                        </option>
                    @endforeach
                </select>
            </div>
        </div>
        <div style="margin-top: 20px;">
            <button type="submit" style="padding: 12px 30px; background: #1e5799; color: white; border: none; border-radius: 5px; cursor: pointer; font-size: 14px; font-weight: 600; margin-right: 10px;">Actualizar</button>
            <a href="{{ route('admin.usuarios') }}" style="padding: 12px 30px; background: #6c757d; color: white; text-decoration: none; border-radius: 5px; font-size: 14px;">Cancelar</a>
        </div>
    </form>
</div>
@endsection