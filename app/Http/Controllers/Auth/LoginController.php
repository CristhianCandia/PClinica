<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Usuario;
use App\Models\Rol;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class LoginController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'usuario_o_correo' => 'required|string',
            'contrasena' => 'required|string',
        ]);

        $usuario = Usuario::where('nombre_usuario', $request->usuario_o_correo)
            ->orWhere('correo', $request->usuario_o_correo)
            ->first();

        if ($usuario && Hash::check($request->contrasena, $usuario->contrasena_hash)) {
            if (!$usuario->estado) {
                return back()->withErrors([
                    'usuario_o_correo' => 'Tu cuenta está desactivada.',
                ]);
            }

            Auth::login($usuario);

            $usuario->ultimo_acceso = now();
            $usuario->save();

            return $this->redirigirSegunRol($usuario);
        }

        return back()->withErrors([
            'usuario_o_correo' => 'Las credenciales no coinciden.',
        ]);
    }

    private function redirigirSegunRol(Usuario $usuario)
    {
        $rol = $usuario->rol->nombre;

        switch ($rol) {
            case 'ADMINISTRADOR':
                return redirect()->route('admin.dashboard');
            case 'RECEPCIONISTA':
                return redirect()->route('recepcionista.dashboard');
            case 'MEDICO':
                return redirect()->route('medico.dashboard');
            case 'PACIENTE':
                return redirect()->route('paciente.dashboard');
            default:
                return redirect('/');
        }
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }
}