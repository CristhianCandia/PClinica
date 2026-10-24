@extends('layouts.app')

@section('title', 'Gestionar Usuarios - Administrador')

@section('content')
<div class="content-header">
    <h1>Gestionar usuarios</h1>
    <p>Registrar, modificar, asignar rol y activar o desactivar cuentas</p>
</div>

{{-- Mensaje de éxito --}}
@if(session('success'))
    <div style="background: #d4edda; color: #155724; padding: 12px; border-radius: 5px; margin-bottom: 20px; border: 1px solid #c3e6cb;">
        {{ session('success') }}
    </div>
@endif

{{-- Mensajes de error de validación --}}
@if($errors->any())
    <div style="background: #f8d7da; color: #721c24; padding: 12px; border-radius: 5px; margin-bottom: 20px; border: 1px solid #f5c6cb;">
        <ul style="margin: 0; padding-left: 20px;">
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<!-- FORMULARIO NUEVO USUARIO -->
<div style="background: white; padding: 25px; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.05); margin-bottom: 30px;">
    <h3 style="margin-bottom: 20px; color: #333; font-size: 18px;">Nuevo usuario</h3>
    <form method="POST" action="{{ route('admin.usuarios.store') }}">
        @csrf
        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
            <div>
                <label style="display: block; margin-bottom: 8px; color: #333; font-weight: 500; font-size: 14px;">Nombre de usuario *</label>
                <input type="text" name="nombre_usuario" required style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 5px; font-size: 14px;">
            </div>
            <div>
                <label style="display: block; margin-bottom: 8px; color: #333; font-weight: 500; font-size: 14px;">Correo electrónico *</label>
                <input type="email" name="correo" required style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 5px; font-size: 14px;">
            </div>
            <div>
                <label style="display: block; margin-bottom: 8px; color: #333; font-weight: 500; font-size: 14px;">Contraseña *</label>
                <input type="password" name="contrasena" required style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 5px; font-size: 14px;">
            </div>
            <div>
                <label style="display: block; margin-bottom: 8px; color: #333; font-weight: 500; font-size: 14px;">Rol *</label>
                <select name="id_rol" required style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 5px; font-size: 14px; background: white;">
                    <option value="">Seleccione un rol...</option>
                    @foreach($roles as $rol)
                        <option value="{{ $rol->id_rol }}">{{ $rol->nombre }}</option>
                    @endforeach
                </select>
            </div>
        </div>
        <button type="submit" style="margin-top: 20px; padding: 12px 30px; background: #1e5799; color: white; border: none; border-radius: 5px; cursor: pointer; font-size: 14px; font-weight: 600; transition: background 0.2s;">Guardar usuario</button>
    </form>
</div>

<!-- TABLA DE USUARIOS -->
<div style="background: white; padding: 25px; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.05); overflow-x: auto;">
    <h3 style="margin-bottom: 20px; color: #333; font-size: 18px;">Usuarios registrados</h3>
    <table style="width: 100%; border-collapse: collapse; min-width: 800px;">
        <thead>
            <tr style="border-bottom: 2px solid #e0e0e0;">
                <th style="padding: 12px; text-align: left; color: #666; font-weight: 600; font-size: 14px;">Usuario</th>
                <th style="padding: 12px; text-align: left; color: #666; font-weight: 600; font-size: 14px;">Correo</th>
                <th style="padding: 12px; text-align: left; color: #666; font-weight: 600; font-size: 14px;">Rol</th>
                <th style="padding: 12px; text-align: left; color: #666; font-weight: 600; font-size: 14px;">Último acceso</th>
                <th style="padding: 12px; text-align: left; color: #666; font-weight: 600; font-size: 14px;">Estado</th>
                <th style="padding: 12px; text-align: left; color: #666; font-weight: 600; font-size: 14px;">Acciones</th>
            </tr>
        </thead>
        <tbody>
            @forelse($usuarios as $usuario)
            <tr style="border-bottom: 1px solid #e0e0e0;">
                <td style="padding: 12px; font-size: 14px;">{{ $usuario->nombre_usuario }}</td>
                <td style="padding: 12px; font-size: 14px;">{{ $usuario->correo }}</td>
                <td style="padding: 12px; font-size: 14px;">{{ $usuario->rol->nombre }}</td>
                <td style="padding: 12px; font-size: 14px;">
                    @if($usuario->ultimo_acceso)
                        {{ \Illuminate\Support\Carbon::parse($usuario->ultimo_acceso)->format('d/m/Y H:i') }}
                    @else
                        <span style="color: #999;">Nunca</span>
                    @endif
                </td>
                <td style="padding: 12px;">
                    @if($usuario->estado)
                        <span style="padding: 4px 12px; border-radius: 4px; font-size: 12px; font-weight: 600; background: #d4edda; color: #155724;">
                            Activo
                        </span>
                    @else
                        <span style="padding: 4px 12px; border-radius: 4px; font-size: 12px; font-weight: 600; background: #f8d7da; color: #721c24;">
                            Inactivo
                        </span>
                    @endif
                </td>
                <td style="padding: 12px;">
                    <a href="{{ route('admin.usuarios.editar', $usuario->id_usuario) }}" style="padding: 6px 12px; background: #28a745; color: white; text-decoration: none; border-radius: 4px; font-size: 12px; margin-right: 5px; display: inline-block;">
                        Editar
                    </a>
                    
                    <form method="POST" action="{{ route('admin.usuarios.toggle', $usuario->id_usuario) }}" style="display: inline;">
                        @csrf
                        
                        @if($usuario->estado)
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
                <td colspan="6" style="padding: 20px; text-align: center; color: #666; font-size: 14px;">
                    No hay usuarios registrados en el sistema.
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection