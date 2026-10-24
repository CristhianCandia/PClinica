<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Paciente;
use App\Models\Usuario;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class RegistroController extends Controller
{
    // Mostrar formulario de registro
    public function showRegistroForm()
    {
        return view('auth.registro');
    }

    // Procesar el registro
    public function registrar(Request $request)
    {
        // Validación
        $request->validate([
            'ci' => 'required|string|max:15|unique:paciente,ci',
            'nombres' => 'required|string|max:60',
            'apellidos' => 'required|string|max:60',
            'fecha_nacimiento' => 'required|date|before:today',
            'sexo' => 'required|in:M,F,O',
            'telefono' => 'required|string|max:15',
            'correo' => 'required|email|max:120|unique:usuario,correo|unique:paciente,correo',
            'direccion' => 'nullable|string|max:150',
            'contrasena' => 'required|string|min:6|confirmed',
        ], [
            'ci.unique' => 'Ya existe un paciente registrado con el CI ' . $request->ci . '.',
            'correo.unique' => 'El correo electrónico ya está registrado.',
            'contrasena.confirmed' => 'Las contraseñas no coinciden.',
            'contrasena.min' => 'La contraseña debe tener al menos 6 caracteres.',
        ]);

        // Transacción para asegurar que se creen ambos registros
        DB::beginTransaction();

        try {
            // 1. Crear el usuario (rol PACIENTE = 4)
            $usuario = Usuario::create([
                'id_rol' => 4,
                'nombre_usuario' => $request->ci, // El CI será el nombre de usuario
                'correo' => $request->correo,
                'contrasena_hash' => Hash::make($request->contrasena),
                'estado' => true,
                'fecha_creacion' => now(),
            ]);

            // 2. Generar código automático para el paciente
            $ultimoPaciente = Paciente::orderBy('id_paciente', 'desc')->first();
            $numero = $ultimoPaciente ? intval(substr($ultimoPaciente->codigo, 4)) + 1 : 1;
            $codigo = 'PAC-' . str_pad($numero, 4, '0', STR_PAD_LEFT);

            // 3. Crear el paciente vinculado al usuario
            Paciente::create([
                'codigo' => $codigo,
                'ci' => $request->ci,
                'nombres' => $request->nombres,
                'apellidos' => $request->apellidos,
                'fecha_nacimiento' => $request->fecha_nacimiento,
                'sexo' => $request->sexo,
                'telefono' => $request->telefono,
                'correo' => $request->correo,
                'direccion' => $request->direccion,
                'id_usuario' => $usuario->id_usuario,
                'estado' => true,
                'fecha_registro' => now(),
            ]);

            DB::commit();

            return redirect()->route('login')->with('success', 'Cuenta creada exitosamente. Ahora puedes iniciar sesión.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['error' => 'Ocurrió un error al crear la cuenta. Intenta nuevamente.']);
        }
    }
}