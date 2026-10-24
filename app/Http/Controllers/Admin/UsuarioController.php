<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Usuario;
use App\Models\Rol;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UsuarioController extends Controller
{
    // Mostrar lista de usuarios
    public function index()
    {
        $usuarios = Usuario::with('rol')->orderBy('id_usuario', 'desc')->get();
        $roles = Rol::where('estado', true)->get();
        
        return view('admin.usuarios', compact('usuarios', 'roles'));
    }

    // Crear nuevo usuario
    public function store(Request $request)
    {
        $request->validate([
            'nombre_usuario' => 'required|string|max:50|unique:usuario,nombre_usuario',
            'correo' => 'required|email|max:120|unique:usuario,correo',
            'contrasena' => 'required|string|min:6',
            'id_rol' => 'required|exists:rol,id_rol',
        ]);

        Usuario::create([
            'nombre_usuario' => $request->nombre_usuario,
            'correo' => $request->correo,
            'contrasena_hash' => Hash::make($request->contrasena),
            'id_rol' => $request->id_rol,
            'estado' => true,
            'fecha_creacion' => now(),
        ]);

        return redirect()->route('admin.usuarios')->with('success', 'Usuario creado exitosamente.');
    }

    // Mostrar formulario de edición
    public function edit($id)
    {
        $usuario = Usuario::findOrFail($id);
        $roles = Rol::where('estado', true)->get();
        
        return view('admin.usuarios-editar', compact('usuario', 'roles'));
    }

    // Actualizar usuario
    public function update(Request $request, $id)
    {
        $request->validate([
            'nombre_usuario' => 'required|string|max:50|unique:usuario,nombre_usuario,' . $id . ',id_usuario',
            'correo' => 'required|email|max:120|unique:usuario,correo,' . $id . ',id_usuario',
            'id_rol' => 'required|exists:rol,id_rol',
        ]);

        $usuario = Usuario::findOrFail($id);
        $usuario->nombre_usuario = $request->nombre_usuario;
        $usuario->correo = $request->correo;
        $usuario->id_rol = $request->id_rol;
        
        if ($request->filled('contrasena')) {
            $usuario->contrasena_hash = Hash::make($request->contrasena);
        }
        
        $usuario->save();

        return redirect()->route('admin.usuarios')->with('success', 'Usuario actualizado exitosamente.');
    }

    // Activar/Desactivar usuario
    public function toggleEstado($id)
    {
        $usuario = Usuario::findOrFail($id);
        $usuario->estado = !$usuario->estado;
        $usuario->save();

        return redirect()->route('admin.usuarios')->with('success', 'Estado del usuario actualizado.');
    }
}